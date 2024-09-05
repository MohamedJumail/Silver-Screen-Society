
<?php include 'partials/header.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Watchlist</title>
    <script>
        let ws;

        function connect() {
            ws = new WebSocket('ws://127.0.0.1:8080');

            ws.onopen = function() {
                console.log('Client connected');
            };

            ws.onmessage = function(event) {
                const response = JSON.parse(event.data);
                addToWatchlist(response.name, response.genre);
            };

            ws.onclose = function() {
                console.log('Client disconnected');
            };
        }

        function addMovie() {
            const name = document.getElementById('movieName').value;
            const genre = document.getElementById('movieGenre').value;
            const data = {
                action: 'add_movie',
                name: name,
                genre: genre
            };
            ws.send(JSON.stringify(data));
        }

        function addToWatchlist(name, genre) {
            const list = document.getElementById('watchlist');
            const item = document.createElement('li');
            item.textContent = `${name} (${genre})`;
            list.appendChild(item);
        }

        window.onload = function() {
            connect();
        }
    </script>
</head>

<body>
    <h1>Watchlist</h1>
    <form onsubmit="addMovie(); return false;">
        <input type="text" id="movieName" placeholder="Movie Name" required>
        <input type="text" id="movieGenre" placeholder="Genre" required>
        <button type="submit">Add Movie</button>
    </form>
    <ul id="watchlist"></ul>
</body>

</html>

<?php include 'partials/footer.php'; ?>