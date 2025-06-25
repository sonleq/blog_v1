<?php

require "includes/dbh.php";

// Start session if not started yet
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Prevent browser caching to avoid back-button access after logout
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Redirect to login if user not authenticated
if (!isset($_SESSION['username'])) {
    header("Location: http://localhost/blog/login.html");  // Adjust path if needed
    exit;
}

// Include any session cleanup/unsetting if necessary
include "includes/unset-session.php";
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
                    <!-- Total Visits -->
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
					
					
					<?php 

$sqlNumBlogs = "SELECT COUNT(*) AS total FROM blog_post WHERE f_post_status= '1'";
$resultNumBlogs = mysqli_query($conn, $sqlNumBlogs);

if ($resultNumBlogs) {
    $row = mysqli_fetch_assoc($resultNumBlogs);
    $numBlogs = $row['total'];
} else {
    $numBlogs = 0;
}

?>
					
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

<?php 

$sqlNumComments = "
    SELECT COUNT(*) AS total
    FROM blog_comment
    JOIN blog_post ON blog_comment.n_blog_comment_id = blog_post.n_blog_post_id
    WHERE blog_post.f_post_status = '1'
";

$resultNumComments = mysqli_query($conn, $sqlNumComments);

if ($resultNumComments) {
    $row = mysqli_fetch_assoc($resultNumComments);
    $numComments = $row['total'];
} else {
    $numComments = 0;
}
?>					
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
                        </div><i class="fa-solid fa-users-rays"></i>
                    </div>

<?php			

//COUNT(*) counts all rows.
//AS total gives the result column the name total.
//Count all the rows in the subscribers table (no matter what).
//Then give that number the label (alias) total in the result.

$sqlNumSubscribers = "SELECT COUNT(*) AS total FROM subscribers";		
$resultSubscriber = mysqli_query($conn, $sqlNumSubscribers);

if ($resultSubscriber) {
    $row = mysqli_fetch_assoc($resultSubscriber);
    $numSubscriber = $row['total'];
} else {
    $numSubscriber = 0;
}
?>						
					
					<!-- Comments -->
                    <div class="col-md-4 col-sm-12 col-xs-12">
                         <div class="panel panel-primary text-center no-boder" style="background-color:white;  color: #6f42c1;">
                            <div class="panel-body">
                               <i class="fa fa-users fa-5x"></i>
                                <h3 id="num-subscribers"><?php echo number_format($numSubscriber); ?></h3>
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
function updateDashboardStats() {
    fetch('includes/dashboard-update.php')
        .then(response => response.json())
        .then(data => {
            document.getElementById('num-blogs').textContent = Number(data.blogs).toLocaleString();
            document.getElementById('num-comments').textContent = Number(data.comments).toLocaleString();
        })
        .catch(error => console.error("Error loading stats:", error));
}

// Run on page load + every 60 seconds
updateDashboardStats();
setInterval(updateDashboardStats, 30000);
</script>

</body>

</html>
