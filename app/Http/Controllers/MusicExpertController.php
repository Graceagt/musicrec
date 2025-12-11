<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MusicExpertController extends Controller
{
    // ... (cfRules dan validMoods tetap seperti sebelumnya) ...
    private $cfRules = [
        ['if' => ['calm'],        'then' => 'calm',        'cf' => 0.8],
        ['if' => ['happy'],       'then' => 'happy',        'cf' => 1.0],
        ['if' => ['energetic'],   'then' => 'energetic',        'cf' => 0.9],
        ['if' => ['party'],       'then' => 'party',        'cf' => 1.0],
        ['if' => ['angry'],       'then' => 'angry',      'cf' => 0.85],
        ['if' => ['sad'],         'then' => 'sad',          'cf' => 1.0],
        ['if' => ['romantic'],    'then' => 'romantic',     'cf' => 1.0],
        ['if' => ['focus'],       'then' => 'focus',        'cf' => 1.0],
        ['if' => ['calm','focus'],             'then' => 'focus',            'cf' => 0.75],
        ['if' => ['happy','romantic'],         'then' => 'happy_romantic',   'cf' => 0.8],
        ['if' => ['energetic','party'],        'then' => 'party_pop',        'cf' => 0.9],
        ['if' => ['sad','romantic'],           'then' => 'emotional',        'cf' => 0.85],
        ['if' => ['angry','energetic'],        'then' => 'intense',          'cf' => 0.9],
        ['if' => ['calm','focus','romantic'],  'then' => 'mellow',           'cf' => 0.75],
        ['if' => ['happy','energetic','party'],'then' => 'party_pop',        'cf' => 0.85],
        ['if' => ['sad','romantic','calm'],    'then' => 'mellow',           'cf' => 0.7],
        ['if' => ['happy','romantic','calm','focus'],'then' => 'happy_relaxed_focus','cf' => 0.65],
        

    ];

    private $validMoods = ['calm','happy','energetic','party','angry','sad','romantic','focus'];

    private function forwardChainingCF($facts)
    {
        $newFacts = $facts;
        $fired = []; 
        foreach ($this->cfRules as $idx => $rule) {
            $fired[$idx] = false;
        }

        $changed = true;
        $iteration = 0;

        while ($changed && $iteration < 50) {
            $changed = false;
            $iteration++;

            foreach ($this->cfRules as $idx => $rule) {

                if ($fired[$idx]) continue;

                $premises = $rule['if'];
                $then = $rule['then'];
                $cfPakar = $rule['cf'];
                $cfPremis = [];

                foreach ($premises as $p) {
                    if (isset($newFacts[$p]) && $newFacts[$p] > 0) {
                        $cfPremis[] = $newFacts[$p];
                    } else {
                        $cfPremis = [];
                        break;
                    }
                }

                // jika premis terpenuhi
                if (!empty($cfPremis)) {
                    $cfRule = min($cfPremis) * $cfPakar;

                    if (isset($newFacts[$then])) {
                        $newFacts[$then] = round(
                            $newFacts[$then] + $cfRule * (1 - $newFacts[$then]),
                            3
                        );
                    } else {
                        $newFacts[$then] = round($cfRule, 3);
                    }

                    // tandai rule ini sudah aktif → tidak boleh aktif lagi
                    $fired[$idx] = true;

                    $changed = true;
                }
            }
        }

        return $newFacts;
    }


    public function index()
    {
        $moods = $this->validMoods;
        $cfOptions = [
            '0'   => 'Tidak',
            '0.2' => 'Tidak tahu',
            '0.4' => 'Sedikit yakin',
            '0.6' => 'Cukup yakin',
            '0.8' => 'Yakin',
            '1'   => 'Sangat yakin',
        ];

        return view('music-expert', compact('moods', 'cfOptions'));
    }

    public function recommend(Request $request)
{
    $cfValues = $request->input('cf', []);
    $validMoods = [];

    foreach ($cfValues as $mood => $cf) {
        $cf = floatval($cf);
        if ($cf > 0 && in_array($mood, $this->validMoods)) {
            $validMoods[$mood] = $cf;
        }
    }

    if (empty($validMoods)) {
        return back()->with('error', 'Masukkan minimal satu nilai CF lebih dari 0.');
    }

    $derivedFacts = $this->forwardChainingCF($validMoods);

    arsort($derivedFacts);
    $topMoods = array_slice(array_keys($derivedFacts), 0, 3);

    // 🔹 Ambil lagu per mood, tapi tetap gabungkan untuk deduplikasi
    $groupedSongs = [];
    $allSongs = [];

    foreach ($topMoods as $mood) {
        $songs = $this->getItunesTracksByMood($mood);
        if (!empty($songs)) {
            // simpan per mood
            $groupedSongs[$mood] = $songs;
            // juga gabungkan untuk deduplikasi global
            $allSongs = array_merge($allSongs, $songs);
        }
    }

    // 🔹 Deduplicate berdasarkan track ID / nama lagu
    $collection = collect($allSongs);
    $unique = $collection->unique(function ($item) {
        return $item['track_id'] ?? strtolower($item['track_name'] ?? '');
    })->values()->all();

    // 🔹 Ambil 3 lagu terbaik total (boleh campur mood)
    $top3Global = array_slice($unique, 0, 3);

    // 🔹 Bentuk ulang ke format yang diminta UI
    // misal: semua lagu top3 masuk 1 kategori “Rekomendasi Utama”
    $topSongs = [
        'Rekomendasi Utama' => $top3Global
    ];

    return view('result', [
        'validMoods' => $validMoods,
        'derivedFacts' => $derivedFacts,
        'topMoods' => $topMoods,
        'topSongs' => $topSongs,
    ]);
}
    

    private function getItunesTracksByMood($mood)
    {
        $genreMap = [
            'calm' => 'Ambient',
            'happy' => 'Pop',
            'energetic' => 'Dance',
            'party' => 'Dance',
            'angry' => 'Rock',
            'sad' => 'Blues',
            'romantic' => 'R&B',
            'focus' => 'Instrumental',
            'emotional' => 'Soul',
            'intense' => 'Metal',
            'happy_romantic' => 'Pop',
            'party_pop' => 'Dance',
            'happy_relaxed_focus' => 'Pop',
            'mellow' => 'Chillout'
        ];

        $query = $genreMap[$mood] ?? $mood;
        $url = "https://itunes.apple.com/search?term=" . urlencode($query) . "&media=music&limit=10";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($result, true);
        if (empty($data['results']) || !is_array($data['results'])) {
            return [];
        }

        $songs = [];
        foreach ($data['results'] as $track) {
            // normalisasi: terima trackName (iTunes) atau track_name (jika sudah ter-normalisasi)
            $trackId = $track['trackId'] ?? $track['track_id'] ?? null;
            $trackName = $track['trackName'] ?? $track['track_name'] ?? null;
            $artist = $track['artistName'] ?? $track['artist_name'] ?? null;
            $preview = $track['previewUrl'] ?? $track['preview_url'] ?? null;
            $artwork = $track['artworkUrl100'] ?? $track['artwork'] ?? null;
            $itunesUrl = $track['trackViewUrl'] ?? $track['itunes_url'] ?? null;
            $genre = $track['primaryGenreName'] ?? $track['genre'] ?? null;

            $songs[] = [
                'track_id'   => $trackId,
                'track_name' => $trackName ?? 'Unknown',
                'artist_name'=> $artist ?? 'Unknown Artist',
                'preview_url'=> $preview,
                'artwork'    => $artwork,
                'itunes_url' => $itunesUrl,
                'genres'     => isset($genre) ? [$genre] : [], // jadikan array
            ];
            
            
        }

        return $songs;
    }
}