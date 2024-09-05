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
                width: 300px; 
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
                color: #fff; 
            }

            .pagination {
                display: flex;
                justify-content: center;
                margin-top: 1rem;
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

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script>
            $(document).ready(function() {
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

                form.submit(function(e) {
                    e.preventDefault();
                    const searchTerm = searchInput.val();
                    const url = searchTerm ? searchURL + '&query=' + searchTerm : API_URL;
                    getMovies(url);
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
                        }
                    });
                }

                function showMovies(movies) {
                    main.html('');
                    $.each(movies, function(index, movie) {
                        const {
                            title,
                            poster_path,   
                            vote_average,
                            overview
                        } = movie;
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
                getMovies(API_URL);

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

                function pageCall(page) {
                    const url = lastUrl.includes('&page=') ? lastUrl.split('&page=')[0] + '&page=' + page : lastUrl + '&page=' + page;
                    getMovies(url);
                }
            });
        </script>
    </body>

    </html>
</section>

<?php include 'partials/footer.php'; ?>
