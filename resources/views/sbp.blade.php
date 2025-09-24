<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Music Recommendation</title>
  <link rel="stylesheet" href="{{ asset('css/musicrec.css') }}">
</head>
<body>
  <header>
    <h1>🎵 Music Recommendation</h1>
    <p>Find your next favorite track</p>
  </header>

  <main>
    <div class="search-box">
      <input type="text" placeholder="Search artist, genre, or mood...">
      <button>Search</button>
    </div>

    <section class="recommendations">
      <h2>Recommended for You</h2>
      <div class="card-grid">
        <div class="card">
          <img src="https://via.placeholder.com/150" alt="Album Cover">
          <h3>Song Title</h3>
          <p>Artist Name</p>
        </div>
        <div class="card">
          <img src="https://via.placeholder.com/150" alt="Album Cover">
          <h3>Another Song</h3>
          <p>Another Artist</p>
        </div>
        <div class="card">
          <img src="https://via.placeholder.com/150" alt="Album Cover">
          <h3>Chill Vibes</h3>
          <p>DJ Sample</p>
        </div>
      </div>
    </section>
  </main>

  <footer>
    <p>© 2025 Music Recommendation | Powered by AI</p>
  </footer>
</body>
</html>
