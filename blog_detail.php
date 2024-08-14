<?php
include('admin/config/dbcon.php'); 

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "No blog ID provided.";
    exit;
}

$blog_id = intval($_GET['id']);

// Fetch blog details
$sql = "SELECT * FROM blog_page WHERE id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    error_log("Prepare statement failed: " . $conn->error);
    echo "An error occurred.";
    exit;
}
$stmt->bind_param("i", $blog_id);
$stmt->execute();
$result = $stmt->get_result();
$blog = $result->fetch_assoc();

if (!$blog) {
    echo "Blog not found.";
    exit;
}
?>
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
        object-fit: Fill;
        max-width: 100%;
    }
    </style>
    <title>Blog Detail</title>
</head>

<body>
    <header>
        <div class="container">
            <div class="row">
                <div class="col-12 text-center pt-4">
                    <h2>MY BLOG</h2>
                </div>
            </div>
            <nav class="navbar navbar-dark navbar-expand justify-content-center bg-dark">
                <ul class="navbar-nav">
                    <li class="nav-item"><a href="index.php" class="nav-link text-light">Home</a></li>
                    <li class="nav-item"><a href="Second.php" class="nav-link text-light">Blog</a></li>
                    <li class="nav-item"><a href="#" class="nav-link text-light">Contact</a></li>
                    <li class="nav-item"><a href="#" class="nav-link text-light">About</a></li>
                    <li class="nav-item"><a href="admin/logout.php" class="nav-link text-light">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <main>
        <section>
            <div class="container">
                <div class="row p-4">
                    <div class="col-lg-8 mb-5">
                        <div class="card mb-4 mycard shadow">
                            <img src="admin/uploads/<?php echo htmlspecialchars($blog['img']); ?>" class="card-img-top"
                                alt="<?php echo htmlspecialchars($blog['title']); ?>">
                            <div class="card-body">
                                <h3 class="card-title"><?php echo htmlspecialchars($blog['title']); ?></h3>
                                <p class="card-text"><?php echo htmlspecialchars($blog['description']); ?></p>
                                <div class="d-flex justify-content-between">
                                    <p class="mb-1">Comments <span
                                            class="bg-dark px-2 text-light"><?php echo htmlspecialchars($blog['comments'] ?? 0); ?></span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="card mycard">
                            <h3 class="text-center mt-3 pb-3">Contact From</h3>
                            <div class="form-group ml-5 mr-5">
                                <input type="text" placeholder="First Name" class="form-control" name="" id="">
                            </div>
                            <div class="form-group ml-5 mr-5">
                                <input type="text" placeholder="Least Name" class="form-control" name="" id="">
                            </div>
                            <div class="form-group ml-5 mr-5">
                                <input type="email" placeholder="Email" class="form-control" name="" id="">
                            </div>
                            <div class="form-group ml-5 mr-5">
                                <textarea name="message" rows="5" cols="50" placeholder="Message"
                                    class="form-control myform" id=""></textarea>
                            </div>
                            <div class="form-group d-flex justify-content-center">
                                <button class="btn btn-outline-secondary bg-light">Submit</button>
                            </div>

                        </div>
                    </div>
                

                <aside class="col-lg-4 mb-5">
                    <div class="card mb-4">
                        <img src="user/img/img2.jpg" class="card-img-top" alt="Portrait of My Name">
                        <div class="card-body">
                            <h3 class="card-title">My Name</h3>
                            <p class="card-text">Just me, myself and I, exploring the universe of unknown. I have a
                                heart of love and an interest in lorem ipsum and mauris neque quam blog. I want to share
                                my world with you.</p>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <h3 class="card-header">Popular Post</h3>
                        <div class="card-body p-0">
                            <ul class="list-group list-group-flush" id="popular-posts-container">
                                <!-- Popular posts will be injected here by JavaScript -->
                            </ul>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <h3 class="card-header">Tags</h3>
                        <div class="d-flex flex-wrap" id="tags-container"></div>
                    </div>

                    <div class="card mb-4">
                        <h3 class="card-header">Category</h3>
                        <div class="d-flex flex-wrap" id="category-container"></div>
                    </div>
                </aside>
            </div>
            </div>
        </section>
    </main>
    <script src="asset/js/bootstrap.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        function filterBlogsByTag(tag) {
            const filteredBlogs = allBlogs.filter(blog => blog.tags && blog.tags.split(', ').includes(tag));
            displayBlogs(filteredBlogs);
            setUrlParameter('tag', tag);
            setUrlParameter('category', ''); // Clear category if filtering by tag
        }

        function filterBlogsByCategory(category) {
            const filteredBlogs = allBlogs.filter(blog => blog.categories && blog.categories.split(', ')
                .includes(category));
            displayBlogs(filteredBlogs);
            setUrlParameter('category', category);
            setUrlParameter('tag', ''); // Clear tag if filtering by category
        }

        function addClickListeners(containerId, filterFunction, dataAttr) {
            const elements = document.querySelectorAll(`#${containerId} .${dataAttr}`);
            elements.forEach(element => {
                element.addEventListener('click', function() {
                    const selectedValue = this.getAttribute(`data-${dataAttr}`);
                    filterFunction(selectedValue);
                });
            });
        }

        function handleEmptyState(containerId, message) {
            const container = document.getElementById(containerId);
            container.innerHTML = `<p>${message}</p>`;
        }

        function fetchPopularPosts() {
            fetch('popular_posts.php') // Update the path to your PHP file
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    if (data.error) {
                        console.error(data.error);
                        return;
                    }
                    displayPopularPosts(data);
                })
                .catch(error => console.error('Fetch error:', error));
        }

        function displayPopularPosts(posts) {
            const popularPostsContainer = document.getElementById('popular-posts-container');
            if (posts.length === 0) {
                popularPostsContainer.innerHTML = '<li>No popular posts found.</li>';
                return;
            }
            popularPostsContainer.innerHTML = posts.map(post => `
                    <li class="list-group-item d-flex">
                        <img src="admin/uploads/${post.blog_image}" class="mr-3" alt="${post.title}" style="width: 50px; height: 50px;">
                        <div>
                            <h5 class="mb-1">${post.title}</h5>
                            <p class="mb-1" style="font-size: 1rem;">${post.description}</p>
                        </div>
                    </li>
                `).join('');
        }

        function fetchData(url, onSuccess, onError) {
            fetch(url)
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => onSuccess(data))
                .catch(error => onError(error));
        }

        fetchData('fetch_blogs.php', data => {
            allBlogs = data;
            displayBlogs(allBlogs);
        }, () => {
            handleEmptyState('blog-container', 'Failed to load blogs.');
        });

        fetchData('fetch_tags.php', data => {
            const tagsContainer = document.getElementById('tags-container');
            tagsContainer.innerHTML = data.map(tag => `
                    <p class="mb-2 ml-2 tag" data-tag="${tag.name}">${tag.name}</p>
                `).join('');
            addClickListeners('tags-container', tag => filterBlogsByTag(tag), 'tag');
        }, () => {
            handleEmptyState('tags-container', 'Failed to load tags.');
        });

        fetchData('fetch_category.php', data => {
            const categoryContainer = document.getElementById('category-container');
            categoryContainer.innerHTML = data.map(category => `
                    <p class="mb-2 ml-2 category tag" data-category="${category.name}">${category.name}</p>
                `).join('');
            addClickListeners('category-container', category => filterBlogsByCategory(category),
                'category');
        }, () => {
            handleEmptyState('category-container', 'Failed to load categories.');
        });

        fetchPopularPosts();
    });
    </script>
</body>

</html>