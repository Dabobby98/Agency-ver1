<?php
// Dynamic blog page using Notion CMS
require_once 'php/notion_cms.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog - Nexon Digital Marketing Agency</title>
    <meta name="description" content="Latest digital marketing insights, tips and strategies from Nexon experts. Stay updated with industry trends and best practices.">
    
    <!-- Favicon -->
    <link rel="icon" href="./image/favicon.png" type="image/png">
    
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
    <!-- Section Header -->
    <header>
        <div id="header"></div>
    </header>

    <!-- Section Content Edit -->
    <aside>
        <div id="edit-sidebar"></div>
    </aside>

    <!-- Section Search -->
    <aside>
        <div id="search-form-container"></div>
    </aside>

    <!-- Section Sidebar -->
    <aside>
        <div id="sidebar"></div>
    </aside>

    <!-- Section Main Content -->
    <main>
        <!-- Section Banner -->
        <div class="section-banner">
            <div class="banner-layout-wrapper">
                <div class="banner-layout">
                    <div class="d-flex flex-column text-center align-items-center gspace-2">
                        <h1 class="title-heading animate-box animated animate__animated" data-animate="animate__fadeInRight">Our Blog</h1>
                        <nav class="breadcrumb">
                            <a href="./index.html" class="gspace-2">Home</a>
                            <span class="separator-link">/</span>
                            <p class="current-page">Blog</p>
                        </nav>    
                    </div>
                    <div class="spacer"></div>
                </div>
            </div>
        </div>
        
        <!-- Section Blog -->
        <div class="section">
            <div class="hero-container">
                <div class="d-flex flex-column gspace-5">
                    <div class="row row-cols-xl-2 row-cols-1 grid-spacer-5 m-0">
                        <div class="col col-xl-8 ps-0 pe-0">
                            <div class="d-flex flex-column gspace-2 animate-box animated fast animate__animated" data-animate="animate__fadeInLeft">
                                <div class="sub-heading">
                                    <i class="fa-regular fa-circle-dot"></i>
                                    <span>Insights & Trends</span>
                                </div>
                                <h2 class="title-heading">Latest Digital Marketing Strategies & Tips</h2>
                            </div>
                        </div>
                        <div class="col col-xl-4 ps-0 pe-0">
                            <div class="d-flex flex-column gspace-2 justify-content-end h-100 animate-box animated animate__animated" data-animate="animate__fadeInRight">
                                <p>Explore our latest blog articles covering industry trends, expert insights, and actionable strategies to elevate your digital marketing game.</p>
                                <div class="link-wrapper">
                                    <a href="./blog.php">View All Articles</a>
                                    <i class="fa-solid fa-circle-arrow-right"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Dynamic Blog Posts Container -->
                    <div class="row row-cols-md-2 row-cols-1 grid-spacer-3">
                        <!-- Blog posts will be loaded here dynamically -->
                        <div class="col">
                            <div class="text-center p-5">
                                <i class="fa-solid fa-spinner fa-spin fa-2x accent-color"></i>
                                <p class="mt-3">Loading blog posts...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Section Footer -->
    <footer id="footer"></footer>

    <!-- JavaScript -->
    <script src="./js/vendor/jquery.min.js"></script>
    <script src="./js/vendor/bootstrap.bundle.min.js"></script>
    <script src="./js/language.js"></script>
    <script src="./js/script.js"></script>
    <script src="./js/blog-cms.js"></script>
</body>
</html>