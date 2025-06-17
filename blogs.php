<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Prevent browser caching so back button doesn’t show protected pages after logout
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Your existing login check here
if (!isset($_SESSION['username'])) {
    header("Location: http://localhost/blog/login.html");  // adjust path accordingly
    exit;
}

// Include the database connection file to establish a connection with the database
require "includes/dbh.php";
//include "includes/unset-sessions.php";


// SQL query to fetch all blog posts where the post status is not '2' (presumably '2' means deleted or unpublished posts)
$sqlBlogs = "SELECT * FROM blog_post WHERE f_post_status != '2'";

// Execute the query and store the result in the $queryBlogs variable
$queryBlogs = mysqli_query($conn, $sqlBlogs);

// Get the number of blog posts retrieved
$numBlogs = mysqli_num_rows($queryBlogs);
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
	<!-- Prevent browser from caching the page -->
    <meta http-equiv="Cache-Control" content="no-store" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Blog Posts</title>
    <!-- Include Bootstrap CSS for styling -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- Include FontAwesome CSS for icon support -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- Include custom styles for the page -->
    <link href="assets/css/custom-styles.css" rel="stylesheet" />
    <!-- Include Google Fonts (Open Sans) -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <!-- Include FontAwesome icons from a CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <!-- Wrapper div for the whole page content -->
    <div id="wrapper">
        <!-- Include header and sidebar components -->
        <?php include "header.php"; ?>
        <?php include "sidebar.php"; ?>
        
        <!-- Main content area -->
        <div id="page-wrapper">
            <div id="page-inner">
                <!-- Row for the page header -->
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="page-header">
                            Blog Posts
                        </h1>
                    </div>
                </div>

                <!-- Display success or error messages for blog operations (add/delete) -->
                <?php
                // If a blog was successfully added, display a success message
                if(isset($_REQUEST['addblog'])){
                    if($_REQUEST['addblog'] == "success"){
                        echo "<div class='alert alert-success'> 
                                <strong>Success!</strong> Blog Added!
                             </div>";
                    }
                }
				
				 if(isset($_REQUEST['updateblog'])){
                    if($_REQUEST['updateblog'] == "success"){
                        echo "<div class='alert alert-success'> 
                                <strong>Success!</strong> Blog Updated!
                             </div>";
                    }
                }

                // If a blog post was deleted, display success or error messages
                if(isset($_REQUEST['deleteblogpost'])){
                    if($_REQUEST['deleteblogpost'] == "success"){
                        echo "<div class='alert alert-success'> 
                                <strong>Success!</strong> Blog Post deleted!
                             </div>";
                    } else if($_REQUEST['deleteblogpost'] == "error"){
                        echo "<div class='alert alert-danger'> 
                                <strong>Error!</strong> Blog Post was not deleted due to an error!
                             </div>";
                    }
                }
                ?>

                <!-- Display the list of blog posts in a table -->
                <div class="row">
                    <div class="col-lg-12">
                        <!-- Panel to display the list of blog posts -->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                All Blog Posts
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>Id</th>
                                                <th>Name</th>
                                                <th>Category</th>
                                                <th>Views</th>
                                                <th>Blog Path</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // Counter to display the row number for each blog
                                            $counter = 0;
                                            
                                            // Loop through the blog posts fetched from the database
                                            while($rowBlogs = mysqli_fetch_assoc($queryBlogs)){
                                                
                                                // Increment the counter for each blog post
                                                $counter++;
                                                $id      = $rowBlogs['n_blog_post_id']; // Get blog post ID
                                                $name    = $rowBlogs['v_post_title'];   // Get blog post title
                                                $cId     = $rowBlogs['n_category_id'];  // Get the category ID of the blog post
                                                $views   = $rowBlogs['n_blog_post_views']; // Get the number of views
                                                $blogPath = $rowBlogs['v_post_path'];    // Get the blog path (URL or slug)
                                                
                                                // SQL query to fetch the category name based on the category ID
                                                $sqlGetCategoryName = "SELECT v_category_title FROM blog_category WHERE n_category_id = '$cId'";
                                                $queryGetCategoryName = mysqli_query($conn, $sqlGetCategoryName);
                                                
                                                // Fetch the category name from the query result
                                                if($rowGetCategoryName = mysqli_fetch_assoc($queryGetCategoryName)){
                                                    $categoryName = $rowGetCategoryName['v_category_title'];
                                                }
                                            ?>
                                            <!-- Display each blog post in a table row -->
                                            <tr>
                                                <td><?php echo $counter; ?></td> <!-- Display the row number -->
                                                <td><?php echo $name; ?></td>    <!-- Display the blog post title -->
                                                <td><?php echo $categoryName; ?></td> <!-- Display the blog's category name -->
                                                <td><?php echo $views; ?></td>  <!-- Display the number of views -->
                                                <td><?php echo $blogPath; ?></td> <!-- Display the blog path (slug or URL) -->
                                                <td>
                                                    <!-- Button to view the blog post in a new window -->
                                                    <button class="popup-button" onclick="window.open('../single-blog.php?blog=<?php echo $blogPath; ?>', '_blank' );">View</button>
                                                    
                                                    <!-- Button to edit the blog post (redirects to the edit page) -->
                                                    <button class="popup-button" onclick="location.href='edit-blog.php?blogid=<?php echo $id; ?>'">Edit</button>
                                                    
                                                    <!-- Button to trigger the delete confirmation modal -->
                                                    <button class="popup-button" data-toggle="modal" data-target="#delete<?php echo $id; ?>">Delete</button>
                                                </td>
                                            </tr>

                                            <!-- Delete confirmation modal -->
                                            <div class="modal fade" id="delete<?php echo $id; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <!-- Form to delete the blog post -->
                                                        <form method="POST" action="includes/delete-blog-post.php">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                                <h4 class="modal-title" id="myModalLabel">Delete Blog Post</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <!-- Hidden field to store the blog post ID to be deleted -->
                                                                <input type="hidden" name="blog-post-id" value="<?php echo $id; ?>" />
                                                                <p>Are you sure you want to delete this blog post?</p> <!-- Confirmation message -->
                                                            </div>
                                                            <div class="modal-footer">
                                                                <!-- Button to close the modal without deleting -->
                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                <!-- Button to confirm the deletion -->
                                                                <button type="submit" class="btn btn-primary" name="delete-blog-post-btn">Delete</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Include footer component -->
        <?php include "footer.php"; ?>
    </div>

    <!-- Include necessary JavaScript files -->
    <script src="assets/js/jquery-1.10.2.js"></script> <!-- jQuery -->
    <script src="assets/js/bootstrap.min.js"></script> <!-- Bootstrap JS -->
    <script src="assets/js/jquery.metisMenu.js"></script> <!-- Metis Menu for sidebar functionality -->
    <script src="assets/js/custom-scripts.js"></script> <!-- Custom scripts for additional functionality -->
</body>
</html>
