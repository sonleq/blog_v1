<?php

require "includes/dbh.php";

require "includes/protect.php";

// Include any session cleanup/unsetting if necessary
include "includes/unset-session.php";


// ----- Securely get number of blogs with status = 1 -----
$status = 1;  // Status meaning "active" or "published"
$sqlNumBlogs = "SELECT COUNT(*) AS total FROM blog_post WHERE f_post_status = ?";
$stmtNumBlogs = mysqli_prepare($conn, $sqlNumBlogs);
mysqli_stmt_bind_param($stmtNumBlogs, "i", $status);
mysqli_stmt_execute($stmtNumBlogs);
$resultNumBlogs = mysqli_stmt_get_result($stmtNumBlogs);

if ($resultNumBlogs) {
    $rowNumBlogs = mysqli_fetch_assoc($resultNumBlogs);
    $numBlogs = $rowNumBlogs['total'];
} else {
    $numBlogs = 0; // Default to 0 if query fails
}
mysqli_stmt_close($stmtNumBlogs);


// ----- Securely get number of comments for posts with status = 1 -----
// Fixed JOIN condition: blog_comment.n_blog_post_id (foreign key) should join blog_post.n_blog_post_id (primary key)
$sqlNumComments = "SELECT COUNT(*) AS total FROM blog_comment 
                   INNER JOIN blog_post ON blog_comment.n_blog_post_id = blog_post.n_blog_post_id
                   WHERE blog_post.f_post_status = ?";
				   
$stmtNumComments = mysqli_prepare($conn, $sqlNumComments);
mysqli_stmt_bind_param($stmtNumComments, "i", $status);
mysqli_stmt_execute($stmtNumComments);
$resultNumComments = mysqli_stmt_get_result($stmtNumComments);

if ($resultNumComments) {
    $rowNumComments = mysqli_fetch_assoc($resultNumComments);
    $numComments = $rowNumComments['total'];
} else {
    $numComments = 0;
}
mysqli_stmt_close($stmtNumComments);


// ----- Securely get number of subscribers -----
$sqlNumSubscribers = "SELECT COUNT(*) AS total FROM subscribers";
$stmtNumSubscribers = mysqli_prepare($conn, $sqlNumSubscribers);
mysqli_stmt_execute($stmtNumSubscribers);
$resultNumSubscribers = mysqli_stmt_get_result($stmtNumSubscribers);

if ($resultNumSubscribers) {
    $rowNumSubscribers = mysqli_fetch_assoc($resultNumSubscribers);
    $numSubscribers = $rowNumSubscribers['total'];
} else {
    $numSubscribers = 0;
}
mysqli_stmt_close($stmtNumSubscribers);

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <!-- Meta tags to further prevent caching -->
    <meta http-equiv="Cache-Control" content="no-store" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>The Living Free - Admin Dashboard </title>

    <!-- Stylesheets -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/js/morris/morris-0.4.3.min.css" rel="stylesheet" />
    <link href="assets/css/custom-styles.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
</head>

<body>
    <div id="wrapper">

        <!-- Include header and sidebar -->
        <?php include "header.php"; ?>
        <?php include "sidebar.php"; ?>

        <!-- Main content wrapper -->
        <div id="page-wrapper">
            <div id="page-inner">

                <!-- Dashboard Title -->
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="page-header">Dashboard</h1>
                    </div>
                </div>

                <!-- Dashboard Stats Panels -->
                <div class="row">
                    <!-- Total Visits (hardcoded) -->
                    <div class="col-md-4 col-sm-12 col-xs-12">
                        <div class="panel panel-primary text-center no-boder bg-color-green">
                            <div class="panel-body">
                                <i class="fa fa-bar-chart-o fa-5x"></i>
                                <h3>8,457</h3>
                            </div>
                            <div class="panel-footer back-footer-green">
                                Total Visits
                            </div>
                        </div>
                    </div>

                    <!-- Blogs -->
                    <div class="col-md-4 col-sm-12 col-xs-12">
                        <div class="panel panel-primary text-center no-boder bg-color-blue">
                            <div class="panel-body">
                                <i class="fas fa-file-alt fa-5x"></i>
                                <h3 id="num-blogs"><?php echo number_format($numBlogs); ?></h3>
                                <small id="blogs-status" style="display:none; font-size:12px; color:#888;">Updating...</small>
                            </div>
                            <div class="panel-footer back-footer-blue">
                                Blogs
                            </div>
                        </div>
                    </div>

                    <!-- Comments -->
                    <div class="col-md-4 col-sm-12 col-xs-12">
                        <div class="panel panel-primary text-center no-boder bg-color-red">
                            <div class="panel-body">
                                <i class="fa fa-comments fa-5x"></i>
                                <h3 id="num-comments"><?php echo number_format($numComments); ?></h3>
                                <small id="comments-status" style="display:none; font-size:12px; color:#888;">Updating...</small>
                            </div>
                            <div class="panel-footer back-footer-red">
                                Comments
                            </div>
                        </div>
                        <i class="fa-solid fa-users-rays"></i>
                    </div>

                    <!-- Subscribers -->
                    <div class="col-md-4 col-sm-12 col-xs-12">
                        <div class="panel panel-primary text-center no-boder" style="background-color:white;  color: #6f42c1;">
                            <div class="panel-body">
                                <i class="fa fa-users fa-5x"></i>
                                <h3 id="num-subscribers"><?php echo number_format($numSubscribers); ?></h3>
                                <small id="subscribers-status" style="display:none; font-size:12px; color: #6f42c1;">Updating...</small>
                            </div>
                            <div class="panel-footer" style="background-color: #5a379d; color: white;">
                                Subscribers
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Include footer -->
                <?php include "footer.php"; ?>

            </div>
            <!-- End page-inner -->
        </div>
        <!-- End page-wrapper -->

    </div>
    <!-- End wrapper -->

    <!-- JavaScript Scripts -->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/jquery.metisMenu.js"></script>
    <script src="assets/js/morris/raphael-2.1.0.min.js"></script>
    <script src="assets/js/morris/morris.js"></script>
    <script src="assets/js/custom-scripts.js"></script>

    <script>
        // Fetch latest stats every 30 seconds
        function updateDashboardStats() {
            fetch('includes/dashboard-update.php')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('num-blogs').textContent = Number(data.blogs).toLocaleString();
                    document.getElementById('num-comments').textContent = Number(data.comments).toLocaleString();
                    document.getElementById('num-subscribers').textContent = Number(data.subscribers).toLocaleString();
                })
                .catch(error => console.error("Error loading stats:", error));
        }

        // Run on page load + every 30 seconds
        updateDashboardStats();
        setInterval(updateDashboardStats, 30000);
    </script>
	
</body>

<script src="../js/timeout-warning.js"></script>

</html>
