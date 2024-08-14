<?php
session_start(); // Start the session

// Check if the user is logged in
$is_logged_in = isset($_SESSION['auth']) && $_SESSION['auth'] === true;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="asset/styles.css">
    <style>
        .card-img-top {
            height: 300px;
            object-fit: cover;
            max-width: 100%;
        }

        .tag,
        .category {
            cursor: pointer;
        }

        .loading {
            text-align: center;
            font-size: 1.5rem;
            color: #888;
        }

        .pagination {
            justify-content: center;
        }
    </style>
    <title>MY Blog</title>
</head>
<body>
    <header>
        <div class="container">
            <div class="row">
                <div class="col-12 text-center pt-4">
                    <h2>MY BLOG</h2>
                    <p>Welcome to the blog of <span class="bg-dark text-light">unknown</span></p>
                </div>
            </div>

            <nav class="navbar navbar-dark navbar-expand justify-content-center bg-dark">
                <ul class="navbar-nav">
                    <li class="nav-item"><a href="index.php" class="nav-link text-light">Home</a></li>
                    <li class="nav-item"><a href="index.php" class="nav-link text-light">Blog</a></li>
                    <li class="nav-item"><a href="index.php" class="nav-link text-light">Contact</a></li>
                    <li class="nav-item"><a href="index.php" class="nav-link text-light">About</a></li>

                    <?php if ($is_logged_in): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-light" href="#" id="navbarDropdown" role="button"
                               data-bs-toggle="dropdown" aria-expanded="false">
                                My Account
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                                <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item"><a href="login.php" class="nav-link text-light">Login</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <section>
            <div class="container">
                <div class="row p-4">
                    <div class="col-lg-8 mb-5" id="blog-container">
                        <div class="loading">Loading blogs...</div>
                    </div>

                    <aside class="col-lg-4 mb-5">
                        <div class="card mb-4">
                            <img src="user/img/img2.jpg" class="card-img-top" alt="Portrait of My Name">
                            <div class="card-body">
                                <h3 class="card-title">My Name</h3>
                                <p class="card-text">Just me, myself and I, exploring the universe of unknown. I have a
                                    heart of love and an interest in lorem ipsum and mauris neque quam blog. I want to
                                    share my world with you.</p>
                            </div>
                        </div>

                        <div class="card mb-4">
                            <h3 class="card-header">Popular post</h3>
                            <div class="card-body p-0">
                                <ul class="list-group list-group-flush" id="popular-posts">
                                    <li class="list-group-item loading">Loading popular posts...</li>
                                </ul>
                            </div>
                        </div>

                        <div class="card mb-4">
                            <h3 class="card-header">Tags</h3>
                            <div class="d-flex flex-wrap pt-2" id="tags-container">
                                <div class="loading">Loading tags...</div>
                            </div>
                        </div>

                        <div class="card mb-4">
                            <h3 class="card-header">Category</h3>
                            <div class="d-flex flex-wrap pt-2" id="category-container">
                                <div class="loading">Loading categories...</div>
                            </div>
                        </div>
                    </aside>
                </div>
                <!-- Pagination -->
                <nav aria-label="Page navigation">
                    <ul class="pagination" id="pagination-container">
                        <!-- Pagination items will be dynamically generated here -->
                    </ul>
                </nav>
            </div>
        </section>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
    <script src="asset/js/bootstrap.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let currentPage = 1;
            const blogsPerPage = 2; // Display only 2 blogs per page

            function fetchData(url, callback, errorCallback) {
                fetch(url)
                    .then(response => {
                        if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
                        return response.json();
                    })
                    .then(data => {
                        if (Array.isArray(data)) {
                            callback(data);
                        } else {
                            console.error(`Invalid data format from ${url}`);
                            errorCallback();
                        }
                    })
                    .catch(error => {
                        console.error(`Error fetching data from ${url}:`, error);
                        errorCallback();
                    });
            }

            function displayBlogs(blogs, page) {
                const blogContainer = document.getElementById('blog-container');
                const start = (page - 1) * blogsPerPage;
                const end = start + blogsPerPage;
                const paginatedBlogs = blogs.slice(start, end);

                if (paginatedBlogs.length === 0) {
                    blogContainer.innerHTML = '<p>No blogs found.</p>';
                    return;
                }

                blogContainer.innerHTML = paginatedBlogs.map(blog => `
                    <div class="card mb-4 mycard shadow">
                        <img src="admin/uploads/${blog.blog_image}" class="card-img-top" alt="${blog.title}">
                        <div class="card-body">
                            <h3 class="card-title">${blog.title}</h3>
                            <p class="card-text">${blog.description}</p>
                            <div class="d-flex justify-content-between">
                                <button class="btn btn-sm" aria-label="Read more about this post" 
                                    onclick="window.location.href='second.php?id=${blog.id}'">
                                    READ MORE >>
                                </button>
                                <p class="mb-1"> comments <span class="bg-dark px-2 text-light">${blog.comments || 0}</span></p>
                            </div>
                        </div>
                    </div>
                `).join('');
            }

            function setupPagination(blogs) {
                const paginationContainer = document.getElementById('pagination-container');
                const totalPages = Math.ceil(blogs.length / blogsPerPage);

                paginationContainer.innerHTML = '';
                for (let i = 1; i <= totalPages; i++) {
                    paginationContainer.innerHTML += `
                        <li class="page-item ${i === currentPage ? 'active' : ''}">
                            <a class="page-link" href="#">${i}</a>
                        </li>
                    `;
                }

                const pageLinks = paginationContainer.querySelectorAll('.page-link');
                pageLinks.forEach(link => {
                    link.addEventListener('click', function (e) {
                        e.preventDefault();
                        currentPage = parseInt(this.innerText);
                        displayBlogs(blogs, currentPage);
                        setupPagination(blogs);
                    });
                });
            }

            function displayPopularPosts(posts) {
                const popularPostsContainer = document.getElementById('popular-posts');
                if (posts.length === 0) {
                    popularPostsContainer.innerHTML = '<li class="list-group-item">No popular posts found.</li>';
                    return;
                }
                popularPostsContainer.innerHTML = posts.map(post => `
                    <li class="list-group-item d-flex">
                        <img src="${post.image}" class="mr-3 mt-2" alt="Popular Post Image" style="width: 50px; height: 50px;">
                        <div>
                            <h6 class=" m-2">${post.title}</h6>
                            <p class=" m-2" style="font-size: 1rem;">${post.description}</p>
                        </div>
                    </li>
                `).join('');
            }

            function addClickListeners(containerId, dataAttr, page) {
                const elements = document.querySelectorAll(`#${containerId} .${dataAttr}`);
                elements.forEach(element => {
                    element.addEventListener('click', function () {
                        const selectedValue = this.getAttribute(`data-${dataAttr}`);
                        window.location.href =
                            `${page}?${dataAttr}=${encodeURIComponent(selectedValue)}`;
                    });
                });
            }

            function handleEmptyState(containerId, message) {
                const container = document.getElementById(containerId);
                container.innerHTML = `<p>${message}</p>`;
            }

            function showLoading(containerId) {
                const container = document.getElementById(containerId);
                container.innerHTML = '<p class="loading">Loading...</p>';
            }

            showLoading('blog-container');
            showLoading('tags-container');
            showLoading('category-container');
            showLoading('popular-posts');

            fetchData('fetch_blogs.php', data => {
                displayBlogs(data, currentPage);
                setupPagination(data);
            }, () => {
                handleEmptyState('blog-container', 'Failed to load blogs.');
            });

            fetchData('fetch_popular_posts.php', data => {
                displayPopularPosts(data);
            }, () => {
                handleEmptyState('popular-posts', 'Failed to load popular posts.');
            });

            fetchData('fetch_tags.php', data => {
                const tagsContainer = document.getElementById('tags-container');
                tagsContainer.innerHTML = data.map(tag => `
                    <p class="mb-2 ml-1 m-1 p-1  tag" data-tag="${tag.name}">${tag.name}</p>
                `).join('');
                addClickListeners('tags-container', 'tag', 'tag.php');
            }, () => {
                handleEmptyState('tags-container', 'Failed to load tags.');
            });

            fetchData('fetch_category.php', data => {
                const categoryContainer = document.getElementById('category-container');
                categoryContainer.innerHTML = data.map(category => `
                    <p class="mb-2 ml-2 m-1 p-1 category" data-category="${category.name}">${category.name}</p>
                `).join('');
                addClickListeners('category-container', 'category', 'category.php');
            }, () => {
                handleEmptyState('category-container', 'Failed to load categories.');
            });
        });
    </script>
</body>
</html>
