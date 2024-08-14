<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="asset/css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="asset/styles.css">
    <style>
        .card-img-top {
            height: 300px;
            object-fit: fill;
            max-width: 100%;
        }
    </style>
    <title>Tag - MY Blog</title>
</head>

<body>
    <header>
        <div class="container">
            <div class="row">
                <div class="col-12 text-center pt-4">
                    <h2>MY BLOG - Tag</h2>
                </div>
            </div>
            <nav class="navbar navbar-dark navbar-expand justify-content-center bg-dark">
                <ul class="navbar-nav">
                <li class="nav-item"><a href="index.php" class="nav-link text-light">Home</a></li>
                    <li class="nav-item"><a href="index.php" class="nav-link text-light">Blog</a></li>
                    <li class="nav-item"><a href="iindex.php" class="nav-link text-light">Contact</a></li>
                    <li class="nav-item"><a href="index.php" class="nav-link text-light">About</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-light" href="#" id="navbarDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            User
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="login.php">Login</a></li>
                            <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                            <li><a class="dropdown-item" href="admin/logout.php">Logout</a></li>
                        </ul>
                    </li>
                  </ul>
            </nav>
        </div>
    </header>

    <main>
        <section>
            <div class="container">
                <div class="row p-4">
                    <div class="col-lg-8 mb-5" id="tag-blog-container"></div>
                    <aside class="col-lg-4 mb-5">
                        <div class="card mb-4">
                            <img src="user/img/img2.jpg" class="card-img-top" alt="Portrait of My Name">
                            <div class="card-body">
                                <h3 class="card-title">My Name</h3>
                                <p class="card-text">Just me, myself and I, exploring the universe of unknown. I have a heart of love and an interest in lorem ipsum and mauris neque quam blog. I want to share my world with you.</p>
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
                            <div class="d-flex flex-wrap pt-2" id="tags-container"></div>
                        </div>

                        <div class="card mb-4">
                            <h3 class="card-header">Category</h3>
                            <div class="d-flex flex-wrap pt-2" id="category-container"></div>
                        </div>
                    </aside>
                </div>
            </div>
        </section>
    </main>

    <script src="asset/js/bootstrap.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const urlParams = new URLSearchParams(window.location.search);
            const tag = urlParams.get('tag');
            if (!tag) {
                document.getElementById('tag-blog-container').innerHTML = '<p>No tag specified.</p>';
                return;
            }

            function fetchData(url, callback, errorCallback, timeout = 5000) {
                const controller = new AbortController();
                const timeoutId = setTimeout(() => controller.abort(), timeout);
                
                fetch(url, { signal: controller.signal })
                    .then(response => {
                        clearTimeout(timeoutId);
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
                        clearTimeout(timeoutId);
                        if (error.name === 'AbortError') {
                            console.error('Fetch request timed out');
                        } else {
                            console.error(`Error fetching data from ${url}:`, error);
                        }
                        errorCallback();
                    });
            }

            function displayBlogs(blogs) {
                const tagBlogContainer = document.getElementById('tag-blog-container');
                if (blogs.length === 0) {
                    tagBlogContainer.innerHTML = `<p>No blogs found for tag: ${tag}</p>`;
                    return;
                }
                tagBlogContainer.innerHTML = blogs.map(blog => `
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

            function displayPopularPosts(posts) {
                const popularPostsContainer = document.getElementById('popular-posts');
                if (posts.length === 0) {
                    popularPostsContainer.innerHTML = '<li class="list-group-item">No popular posts found.</li>';
                    return;
                }
                popularPostsContainer.innerHTML = posts.map(post => `
                    <li class="list-group-item d-flex">
                        <img src="${post.image}" class="mr-3" alt="Popular Post Image" style="width: 50px; height: 50px;">
                        <div>
                            <h6 class="mb-1">${post.title}</h6>
                            <p class="mb-1" style="font-size: 1rem;">${post.description}</p>
                        </div>
                    </li>
                `).join('');
            }

            function handleEmptyState(containerId, message) {
                const container = document.getElementById(containerId);
                container.innerHTML = `<div class="alert alert-warning">${message}</div>`;
            }

            function addClickListeners(containerId, queryParam, baseUrl) {
                const container = document.getElementById(containerId);
                container.addEventListener('click', function(event) {
                    if (event.target && event.target.matches(`.${queryParam}`)) {
                        const value = event.target.getAttribute(`data-${queryParam}`);
                        window.location.href = `${baseUrl}?${queryParam}=${encodeURIComponent(value)}`;
                    }
                });
            }

            fetchData(`fetch_blog1.php?tag=${encodeURIComponent(tag)}`, displayBlogs, () => {
                handleEmptyState('tag-blog-container', 'Failed to load blogs for this tag.');
            });
      
            fetchData('fetch_tags.php', data => {
                const tagsContainer = document.getElementById('tags-container');
                tagsContainer.innerHTML = data.map(tag => `
                    <p class="mb-2 ml-2 tag" data-tag="${tag.name}">${tag.name}</p>
                `).join('');
                addClickListeners('tags-container', 'tag', 'tag.php');
            }, () => {
                handleEmptyState('tags-container', 'Failed to load tags.');
            });

            fetchData('fetch_category.php', data => {
                const categoryContainer = document.getElementById('category-container');
                categoryContainer.innerHTML = data.map(category => `
                    <p class="mb-2 ml-2 category" data-category="${category.name}">${category.name}</p>
                `).join('');
                addClickListeners('category-container', 'category', 'category.php');
            }, () => {
                handleEmptyState('category-container', 'Failed to load categories.');
            });

            // Load popular posts directly into the section
            fetchData('fetch_popular_posts.php', displayPopularPosts, () => {
                handleEmptyState('popular-posts', 'Failed to load popular posts.');
            });
        });
    </script>
</body>

</html>
