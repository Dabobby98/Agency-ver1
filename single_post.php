<?php
// Dynamic single post page using Notion CMS
require_once 'php/notion_cms.php';

$postId = $_GET['id'] ?? 'mock-1';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Post - Nexon Digital Marketing Agency</title>
    <meta name="description" content="Read our latest digital marketing insights and expert advice to grow your business online.">
    
    <!-- Favicon -->
    <link rel="icon" href="./image/favicon.png" type="image/png">
    
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
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
                        <h1 class="title-heading animate-box animated animate__animated" data-animate="animate__fadeInRight">Loading...</h1>
                        <nav class="breadcrumb">
                            <a href="./index.html" class="gspace-2">Home</a>
                            <span class="separator-link">/</span>
                            <a href="./blog.php" class="gspace-2">Blog</a>
                            <span class="separator-link">/</span>
                            <p class="current-page">Post</p>
                        </nav>    
                    </div>
                    <div class="spacer"></div>
                </div>
            </div>
        </div>
        
        <!-- Section Post -->
        <div class="section">
            <div class="hero-container">
                <div class="row row-cols-xl-2 row-cols-1 grid-spacer-5">
                    <div class="col col-xl-4 order-2 order-xl-1">
                        <div class="d-flex flex-column flex-md-row flex-xl-column gspace-5">
                            <div class="card recent-post">
                                <h4>Recent Blog</h4>
                                <!-- Recent posts will be loaded here dynamically -->
                            </div>
                            <div class="cta-service-banner">
                                <div class="spacer"></div>
                                <h3 class="title-heading">Transform Your Business with Nexon!</h3>
                                <p>
                                    Take your digital marketing to the next level with data-driven strategies and innovative solutions. Let's create something amazing together!
                                </p>
                                <div class="link-wrapper">
                                    <a href="contact.html">Get in Touch</a>
                                    <i class="fa-solid fa-circle-arrow-right"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col col-xl-8 order-1 order-xl-2">
                        <div class="d-flex flex-column gspace-2">
                            <div class="post-image">
                                <img src="./image/dummy-img-600x400.jpg" alt="Blog Post" class="img-fluid">
                            </div>
                            <h2>Loading post...</h2>
                            <div class="underline-muted-full"></div>
                            <!-- Post content will be loaded here dynamically -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Section Footer -->
    <footer id="footer"></footer>

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="./js/script.js"></script>
    <script src="./js/blog-cms.js"></script>
</body>
</html>