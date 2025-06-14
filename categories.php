<?php

// Include the database connection file
require "admin/includes/dbh.php";

// Check if the 'group' parameter is set in the URL query string
if(isset($_GET['group'])){

    // Get the category path from the URL parameter
    $categoryPath = $_GET['group'];

    // Prepare SQL query to fetch category details based on the category path
    $sqlGetCategory = "SELECT * FROM blog_category WHERE v_category_path = '$categoryPath'";
    // Execute the query
    $queryGetCategory = mysqli_query($conn, $sqlGetCategory);

    // Fetch the category row from the result set if exists
    if($rowGetCategory = mysqli_fetch_assoc($queryGetCategory)){
        // Store the category ID, title, and meta title into variables
        $categoryId = $rowGetCategory['n_category_id'];
        $categoryTitle = $rowGetCategory['v_category_title'];
        $categoryMetaTitle = $rowGetCategory['v_category_meta_title'];

        // Set the number of posts to display per page for pagination
        $postsPerPage = 4;
        // Get the current page number from URL if set and valid; default to 1
        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
        // Calculate the offset for the SQL LIMIT clause
        $offset = ($page - 1) * $postsPerPage;

        // Count total number of posts in the category with status = 1 (published)
        $sqlCount = "SELECT COUNT(*) as total FROM blog_post WHERE n_category_id = '$categoryId' AND f_post_status = '1'";
        $resultCount = mysqli_query($conn, $sqlCount);
        $rowCount = mysqli_fetch_assoc($resultCount);
        $totalPosts = $rowCount['total'];
        // Calculate total pages needed for pagination
        $totalPages = ceil($totalPosts / $postsPerPage);

    }
    else{
        // If category not found, redirect to homepage
        header("Location: index.php");
        exit();
    }

?>

<!DOCTYPE html>
<html class="no-js" lang="en">
<head>

    <!-- Basic page metadata -->
    <meta charset="utf-8">
    <title>Son's Blog | <?php echo $categoryMetaTitle; ?> </title>
    <meta name="description" content="Posts in category: <?php echo htmlspecialchars($categoryMetaTitle, ENT_QUOTES, 'UTF-8'); ?>">
    <meta name="author" content="Son Le">

    <!-- Responsive viewport -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSS stylesheets -->
    <link rel="stylesheet" href="css/vendor.css">
    <link rel="stylesheet" href="css/styles.css">

    <!-- JavaScript -->
    <script src="js/modernizr.js"></script>
    <script defer src="js/fontawesome/all.min.js"></script>

    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="180x180" href="apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon-16x16.png">
    <link rel="manifest" href="site.webmanifest">

</head>

<body id="top">

    <!-- Preloader animation while page loads -->
    <div id="preloader"> 
        <div id="loader"></div>
    </div>

    <!-- Include site header -->
    <?php include "header-opaque.php"; ?>

    <!-- Main content section -->
    <section class="s-content">

        <!-- Page header showing current category -->
        <div class="s-pageheader">
            <div class="row">
                <div class="column large-12">
                    <h1 class="page-title">
                        <span class="page-title__small-type">Category</span>
                        <?php echo $categoryTitle; ?>
                    </h1>
                </div>
            </div>
        </div> <!-- end s-pageheader-->

        <!-- Blog posts grid -->
        <div class="s-bricks s-bricks--half-top-padding">
            <div class="masonry">
                <div class="bricks-wrapper h-group">

                    <div class="grid-sizer"></div>

                    <div class="lines">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>

        <?php
        // Query to get all blog posts for this category that are published
        // Order by latest post ID descending, limit by postsPerPage and offset for pagination
        $sqlGetAllBlogs = "SELECT * FROM blog_post 
                           WHERE n_category_id = '$categoryId' AND f_post_status = '1' 
                           ORDER BY n_blog_post_id DESC 
                           LIMIT $postsPerPage OFFSET $offset";

        // Execute the query
        $queryGetAllBlogs = mysqli_query($conn, $sqlGetAllBlogs);

        // Loop through each blog post and display it
        while($rowGetAllBlogs = mysqli_fetch_assoc($queryGetAllBlogs)){

            // Extract relevant blog post data
            $blogTitle       = $rowGetAllBlogs['v_post_title'];
            $blogPath        = $rowGetAllBlogs['v_post_path'];
            $blogSummary     = $rowGetAllBlogs['v_post_summary'];
            $blogAltImageUrl = $rowGetAllBlogs['v_alt_image_url'];

        ?>

                    <!-- Single blog post article -->
                    <article class="brick entry" data-aos="fade-up">

                        <div class="entry__thumb">
                            <a href="single-blog.php?blog=<?php echo $blogPath; ?>" class="thumb-link">
                                <img src="<?php echo $blogAltImageUrl; ?>" 
                                     srcset="<?php echo $blogAltImageUrl; ?> 1x, <?php echo $blogAltImageUrl; ?> 2x" alt="">
                            </a>
                        </div> <!-- end entry__thumb -->

                        <div class="entry__text">
                            <div class="entry__header">
                                <h1 class="entry__title">
                                    <a href="single-blog.php?blog=<?php echo $blogPath; ?>">
                                        <?php echo $blogTitle; ?>
                                    </a>
                                </h1>

                                <div class="entry__meta">
                                    <span class="byline">By:
                                        <span class='author'>
                                            <a href="#">Son Le</a>
                                        </span>
                                    </span>
                                </div>
                            </div>
                            <div class="entry__excerpt">
                                <p>
                                    <?php echo $blogSummary; ?>
                                </p>
                            </div>
                            <a class="entry__more-link" href="single-blog.php?blog=<?php echo $blogPath; ?>">Read Blog</a>
                        </div> <!-- end entry__text -->

                    </article> <!-- end article -->

        <?php
        } // end while loop
        ?>

                </div> <!-- end bricks-wrapper -->

            </div> <!-- end masonry -->

            <!-- Pagination navigation -->
            <div class="row">
                <div class="column large-12">
                    <nav class="pgn">
                        <ul>
                            <?php if ($page > 1): ?>
                                <!-- Previous page link -->
                                <li><a class="pgn__prev" href="categories.php?group=<?php echo urlencode($_GET['group']); ?>&page=<?php echo $page - 1; ?>">Prev</a></li>
                            <?php endif; ?>

                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <!-- Page number links -->
                                <li>
                                    <a class="pgn__num <?php if ($i == $page) echo 'current'; ?>" href="categories.php?group=<?php echo urlencode($_GET['group']); ?>&page=<?php echo $i; ?>">
                                        <?php echo $i; ?>
                                    </a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($page < $totalPages): ?>
                                <!-- Next page link -->
                                <li><a class="pgn__next" href="categories.php?group=<?php echo urlencode($_GET['group']); ?>&page=<?php echo $page + 1; ?>">Next</a></li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                </div> <!-- end column -->
            </div> <!-- end row -->

        </div> <!-- end s-bricks -->

    </section> <!-- end s-content -->

    <!-- Include site footer -->
    <?php include "footer.php"; ?>

    <!-- JavaScript files -->
    <script src="js/jquery-3.5.0.min.js"></script>
    <script src="js/plugins.js"></script>
    <script src="js/main.js"></script>

</body>

</html>

<?php
} // end if isset($_GET['group'])
else {
    // Redirect to homepage if 'group' parameter is missing
    header("Location: index.php");
    exit();
}
?>
