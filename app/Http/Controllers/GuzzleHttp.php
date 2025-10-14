use GuzzleHttp\Client;

$client = new Client([
    'base_uri' => 'https://api.spotify.com/v1/',
    'headers' => [
        'Authorization' => 'Bearer ' . $yourSpotifyAccessToken
    ]
]);

foreach ($topSongs as &$song) {
    $query = urlencode($song['title'] . ' ' . $song['artist']);
    $response = $client->get("search?q={$query}&type=track&limit=1");
    $data = json_decode($response->getBody(), true);

    if (!empty($data['tracks']['items'])) {
        $track = $data['tracks']['items'][0];
        $song['spotify_id'] = $track['id'];               // untuk iframe embed
        $song['preview_url'] = $track['preview_url'];     // langsung mp3 30 detik
    }
}
