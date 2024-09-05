<?php include 'partials/header.php'; ?>

<section class="posts <?= $featured ? '' : 'section__extra-margin' ?>">
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Movie App</title>
        <style>
            .search {
                display: block;
                margin: auto;
                padding: 0.5rem;
                font-size: 1rem;
                border: 1px solid #ccc;
                border-radius: 4px;
                width: 300px; /* Adjust width as needed */
            }

            .movie-container {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 1rem;
                padding: 1rem;
                justify-items: center;
            }

            .movie {
                background-color: #2d2b7c;
                border-radius: 8px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                overflow: hidden;
                width: 100%;
            }

            .movie img {
                width: 100%;
                height: auto;
                border-top-left-radius: 8px;
                border-top-right-radius: 8px;
            }

            .movie-info {
                padding: 1rem;
                text-align: center;
                font-size: larger;
                color: #fff;
            }

            .movie-info h3 {
                font-size: larger;
                margin: 0;
                color: #fff;
            }

            .overview {
                padding: 1rem;
                font-size: larger;
                background-color: #2d2b7c;
                color: #fff; /* Text color */
            }

            .pagination {
                display: flex;
                justify-content: center;
                margin-top: 1rem;
                margin-bottom: 2rem;
            }

            .page,
            .current {
                padding: 0.5rem 1rem;
                margin: 0 0.5rem;
                cursor: pointer;
                border: 1px solid #ccc;
                border-radius: 4px;
            }

            .disabled {
                opacity: 0.5;
                pointer-events: none;
            }
        </style>
    </head>

    <body>
        
        <header style="text-align: center;">
            <form id="form">
                <input type="text" placeholder="Search" id="search" class="search">
            </form>
        </header>

        
        <section class="posts">
            <div class="movie-container" id="main"></div>

            
            <div class="pagination">
                <div class="page" id="prev">Previous Page</div>
                <div class="current" id="current">1</div>
                <div class="page" id="next">Next Page</div>
            </div>
        </section>

        <!-- JavaScript -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script>
            // Function to set a cookie
            function setCookie(name, value, days) {
                let expires = "";
                if (days) {
                    let date = new Date();
                    date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                    expires = "; expires=" + date.toUTCString();
                }
                document.cookie = name + "=" + encodeURIComponent(value) + expires + "; path=/";
            }

            // Function to get a cookie
            function getCookie(name) {
                let nameEQ = name + "=";
                let cookies = document.cookie.split(';');
                for (let cookie of cookies) {
                    while (cookie.charAt(0) === ' ') {
                        cookie = cookie.substring(1, cookie.length);
                    }
                    if (cookie.indexOf(nameEQ) === 0) {
                        return decodeURIComponent(cookie.substring(nameEQ.length, cookie.length));
                    }
                }
                return null;
            }

            // Function to delete a cookie
            function deleteCookie(name) {
                document.cookie = name + '=; Max-Age=-99999999;';
            }

            const API_KEY = 'api_key=1cf50e6248dc270629e802686245c2c8';
            const BASE_URL = 'https://api.themoviedb.org/3';
            const API_URL = BASE_URL + '/discover/movie?sort_by=popularity.desc&' + API_KEY;
            const IMG_URL = 'https://image.tmdb.org/t/p/w500';
            const searchURL = BASE_URL + '/search/movie?' + API_KEY;

            const main = $('#main');
            const form = $('#form');
            const searchInput = $('#search');

            let currentPage = 1;
            let nextPage = 2;
            let prevPage = 3;
            let lastUrl = '';
            let totalPages = 100;

            // Initial load based on cookie or default API call
            let lastSearch = getCookie('lastSearch');
            if (lastSearch) {
                let { searchTerm, rating } = JSON.parse(lastSearch);
                searchInput.val(searchTerm);
                getMovies(searchURL + '&query=' + searchTerm);
            } else {
                getMovies(API_URL);
            }

            // Form submit event listener
            form.submit(function(e) {
                e.preventDefault();
                const searchTerm = searchInput.val();
                if (searchTerm) {
                    // Fetch rating or set default if not available
                    let rating = 'Not Rated';
                    $.ajax({
                        url: searchURL + '&query=' + searchTerm,
                        method: 'GET',
                        success: function(data) {
                            if (data.results.length > 0) {
                                rating = data.results[0].vote_average;
                            }
                            // Store search term and rating in cookie for 10 days
                            setCookie('lastSearch', JSON.stringify({ searchTerm, rating }), 10);
                            getMovies(searchURL + '&query=' + searchTerm);
                        },
                        error: function(err) {
                            console.error('Error fetching rating:', err);
                            // Store search term and default rating in cookie for 10 days on error
                            setCookie('lastSearch', JSON.stringify({ searchTerm, rating }), 10);
                            getMovies(searchURL + '&query=' + searchTerm);
                        }
                    });
                } else {
                    getMovies(API_URL);
                }
            });

            function getMovies(url) {
                lastUrl = url;
                $.ajax({
                    url: url,
                    method: 'GET',
                    success: function(data) {
                        showMovies(data.results);
                        currentPage = data.page;
                        nextPage = currentPage + 1;
                        prevPage = currentPage - 1;
                        totalPages = data.total_pages;

                        $('#current').text(currentPage);

                        if (currentPage <= 1) {
                            $('#prev').addClass('disabled');
                        } else {
                            $('#prev').removeClass('disabled');
                        }

                        if (currentPage >= totalPages) {
                            $('#next').addClass('disabled');
                        } else {
                            $('#next').removeClass('disabled');
                        }
                    },
                    error: function(err) {
                        console.error('Error fetching movies:', err);
                    }
                });
            }

            // Function to display movies
            function showMovies(movies) {
                main.html('');
                $.each(movies, function(index, movie) {
                    const { title, poster_path, vote_average, overview } = movie;
                    const movieEl = $('<div>').addClass('movie')
                        .append($('<img>').attr('src', poster_path ? IMG_URL + poster_path : 'http://via.placeholder.com/500x750').attr('alt', title))
                        .append($('<div>').addClass('movie-info')
                            .append($('<h3>').text(title))
                            .append($('<span>').text(vote_average)))
                        .append($('<div>').addClass('overview')
                            .append($('<h3>').text('Overview'))
                            .append(overview));
                    main.append(movieEl);
                });
            }

            // Pagination handlers using AJAX
            $('#prev').click(function() {
                if (prevPage > 0) {
                    pageCall(prevPage);
                }
            });

            $('#next').click(function() {
                if (nextPage <= totalPages) {
                    pageCall(nextPage);
                }
            });

            // Function to handle pagination
            function pageCall(page) {
                const url = lastUrl.includes('&page=') ? lastUrl.split('&page=')[0] + '&page=' + page : lastUrl + '&page=' + page;
                getMovies(url);
            }
        </script>
    </body>

    </html>
</section>

<?php include 'partials/footer.php'; ?>
