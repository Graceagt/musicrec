<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MusicExpertController extends Controller
{
    // 🔹 Rule basis pengetahuan pakar + CF
    private $cfRules = [
        ['if'=>['calm'],'then'=>'calm','cf'=>1.0],
        ['if'=>['happy'],'then'=>'happy','cf'=>1.0],
        ['if'=>['energetic'],'then'=>'energetic','cf'=>1.0],
        ['if'=>['party'],'then'=>'party','cf'=>1.0],
        ['if'=>['angry'],'then'=>'angry','cf'=>1.0],
        ['if'=>['sad'],'then'=>'sad','cf'=>1.0],
        ['if'=>['romantic'],'then'=>'romantic','cf'=>1.0],
        ['if'=>['focus'],'then'=>'focus','cf'=>1.0],
        ['if'=>['calm','focus'],'then'=>'focus','cf'=>0.8],
        ['if'=>['happy','romantic'],'then'=>'happy_romantic','cf'=>0.7],
        ['if'=>['energetic','party'],'then'=>'party','cf'=>0.8],
        ['if'=>['sad','romantic'],'then'=>'emotional','cf'=>0.85],
        ['if'=>['angry','energetic'],'then'=>'intense','cf'=>0.9],
        ['if'=>['calm','focus','romantic'],'then'=>'ambient_focus_romantic','cf'=>0.75],
        ['if'=>['happy','energetic','party'],'then'=>'party_pop','cf'=>0.8],
        ['if'=>['sad','romantic','calm'],'then'=>'mellow','cf'=>0.7],
        ['if'=>['happy','romantic','calm','focus'],'then'=>'happy_relaxed_focus','cf'=>0.65],
    ];

    private $validMoods = ['calm','happy','energetic','party','angry','sad','romantic','focus'];

    // 🔹 Forward chaining CF
    private function forwardChainingCF($facts)
    {
        $newFacts = $facts;
        $changed = true;
        $maxIterations = 50; // batas aman
        $iteration = 0;
    
        while($changed && $iteration < $maxIterations){
            $changed = false;
            $iteration++;
            foreach($this->cfRules as $rule){
                $premises=$rule['if'];
                $then=$rule['then'];
                $cfPakar=$rule['cf'];
                $cfPremis=[];
                foreach($premises as $p){
                    if(isset($newFacts[$p]) && $newFacts[$p]>0) $cfPremis[]=$newFacts[$p];
                    else { $cfPremis=[]; break; }
                }
                if(!empty($cfPremis)){
                    $cfRule=min($cfPremis)*$cfPakar;
                    if(isset($newFacts[$then])){
                        $newFacts[$then]=round($newFacts[$then]+$cfRule*(1-$newFacts[$then]),2);
                    } else { $newFacts[$then]=round($cfRule,2); }
                    $changed=true;
                }
            }
        }
    
        return $newFacts;
    }

    // 🔹 Halaman utama
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

    // 🔹 Rekomendasi lagu (Top 3 dari iTunes)
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

        // 🔹 Jalankan forward chaining pakar
        $derivedFacts = $this->forwardChainingCF($validMoods);

        // Ambil top 3 berdasarkan nilai CF tertinggi
        arsort($derivedFacts);
        $topMoods = array_slice(array_keys($derivedFacts), 0, 3);

        // 🔹 Ambil rekomendasi dari iTunes
        $topSongs = [];
        foreach ($topMoods as $mood) {
            $songs = $this->getItunesTracksByMood($mood);

            // fallback minimal 1 lagu dummy jika tidak ada
            if (empty($songs)) {
                $songs[] = [
                    'track_name'  => 'Song',
                    'artist_name' => 'Unknown Artist',
                    'preview_url' => null,
                    'artwork'     => 'default-artwork.png',
                    'itunes_url'  => '#',
                ];
            }

            $topSongs[$mood] = $songs;
        }

        return view('result', [
            'validMoods' => $validMoods,
            'derivedFacts' => $derivedFacts,
            'topSongs' => $topSongs
        ]);
    }

    // ===================== ITUNES HELPER =====================
    private function getItunesTracksByMood($mood)
    {
        // mapping mood derivatif ke keyword iTunes
        $keywordMap = [
            'happy_romantic' => 'happy',
            'ambient_focus_romantic' => 'ambient',
            'party_pop' => 'pop',
            'mellow' => 'chill',
            'happy_relaxed_focus' => 'happy',
            'emotional' => 'sad',
            'intense' => 'energetic',
        ];

        $query = $keywordMap[$mood] ?? $mood;
        $url = "https://itunes.apple.com/search?term=".urlencode($query)."&media=music&limit=3";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        $result = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($result,true);
        if(empty($data['results'])) return null;

        $songs = [];
        foreach($data['results'] as $track){
            $songs[] = [
                'track_name'  => $track['trackName'] ?? 'Unknown',
                'artist_name' => $track['artistName'] ?? 'Unknown',
                'preview_url' => $track['previewUrl'] ?? null,
                'artwork'     => $track['artworkUrl100'] ?? null,
                'itunes_url'  => $track['trackViewUrl'] ?? '#', // link ke iTunes
            ];
        }

        return $songs;
    }
}
