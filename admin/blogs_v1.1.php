<?php

require "includes/dbh.php";
require "includes/protect.php";

// Include any session cleanup/unsetting if necessary
include "includes/unset-session.php";


/*************************************************/
// Get current user info
//null-coalescing operator
$user_id = $_SESSION['user_id'] ?? 0; 
$role = $_SESSION['role'] ?? '';

// Prepare SQL differently for admin vs writer
if ($role === 'admin') {
    // Admin sees all posts that are not deleted (status != 2)
    $sql = "SELECT * FROM blog_post WHERE f_post_status != ?";
    $stmt = mysqli_prepare($conn, $sql);
    $status = 2;
    mysqli_stmt_bind_param($stmt, "i", $status);
} else { 
    // Writer sees only their own posts
    $sql = "SELECT * FROM blog_post WHERE f_post_status != ? AND n_user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    $status = 2;
    mysqli_stmt_bind_param($stmt, "ii", $status, $user_id);
}

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <!-- Prevent browser caching -->
    <meta http-equiv="Cache-Control" content="no-store" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Blog Posts</title>

    <!-- CSS dependencies -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/custom-styles.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
</head>
<body>
<div id="wrapper">
    <!-- Include header and sidebar -->
    <?php include "header.php"; ?>
    <?php include "sidebar.php"; ?>

    <div id="page-wrapper">
        <div id="page-inner">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="page-header">Blog Posts</h1>
                </div>
            </div>

            <?php
            // Show success message if blog was added
            if (isset($_GET['addblog']) && $_GET['addblog'] === "success") {
                echo "<div class='alert alert-success'><strong>Success!</strong> Blog Added!</div>";
            }

            // Show success message if blog was updated
            if (isset($_GET['updateblog']) && $_GET['updateblog'] === "success") {
                echo "<div class='alert alert-success'><strong>Success!</strong> Blog Updated!</div>";
            }

            // Show message for blog deletion success or error
            if (isset($_GET['deleteblogpost'])) {
                if ($_GET['deleteblogpost'] === "success") {
                    echo "<div class='alert alert-success'><strong>Success!</strong> Blog Post deleted!</div>";
                } elseif ($_GET['deleteblogpost'] === "error") {
                    echo "<div class='alert alert-danger'><strong>Error!</strong> Could not delete blog post!</div>";
                }
            }
            ?>

            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">All Blog Posts</div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Title</th>
                                        <th>Category</th>
                                        <th>Views</th>
                                        <th>Path</th>
                                        <th>Action</th>
										<th>Author</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $counter = 1; // Row number counter

                                    // Loop through all blog posts fetched from the database
                                    while ($blog = mysqli_fetch_assoc($result)) {
                                        // Cast and sanitize data for safe output
                                        $id = (int)$blog['n_blog_post_id'];
                                        $title = htmlspecialchars($blog['v_post_title'], ENT_QUOTES, 'UTF-8');
                                        $views = (int)$blog['n_blog_post_views'];
                                        $path = htmlspecialchars($blog['v_post_path'], ENT_QUOTES, 'UTF-8');
                                        $cat_id = (int)$blog['n_category_id'];

                                        // Get the category name from category ID
                                        $cat_title = 'Unknown'; // default if category not found
                                        $cat_sql = "SELECT v_category_title FROM blog_category WHERE n_category_id = ?";
                                        $cat_stmt = mysqli_prepare($conn, $cat_sql);
                                        mysqli_stmt_bind_param($cat_stmt, "i", $cat_id);
                                        mysqli_stmt_execute($cat_stmt);
                                        $cat_result = mysqli_stmt_get_result($cat_stmt);

                                        // If category found, sanitize its title
                                        if ($cat_row = mysqli_fetch_assoc($cat_result)) {
                                            $cat_title = htmlspecialchars($cat_row['v_category_title'], ENT_QUOTES, 'UTF-8');
                                        }
                                    ?>
                                        <tr>
                                            <td><?php echo $counter++; ?></td>
                                            <td><?php echo $title; ?></td>
                                            <td><?php echo $cat_title; ?></td>
                                            <td><?php echo $views; ?></td>
                                            <td><?php echo $path; ?></td>
                                            <td>
                                                <!-- Buttons for View, Edit, Delete -->
                                                <button onclick="window.open('../single-blog.php?blog=<?php echo urlencode($path); ?>', '_blank');" class="popup-button">View</button>
                                                <button onclick="location.href='edit-blog.php?blogid=<?php echo $id; ?>'" class="popup-button">Edit</button>
                                                <button data-toggle="modal" data-target="#delete<?php echo $id; ?>" class="popup-button">Delete</button>
                                            </td>
                                        </tr>

                                        <!-- Modal dialog for delete confirmation -->
                                        <div class="modal fade" id="delete<?php echo $id; ?>" tabindex="-1" role="dialog" aria-labelledby="modalLabel<?php echo $id; ?>" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form method="POST" action="includes/delete-blog-post.php">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            <h4 class="modal-title" id="modalLabel<?php echo $id; ?>">Delete Blog Post</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <!-- Hidden field to identify blog post to delete -->
                                                            <input type="hidden" name="blog-post-id" value="<?php echo $id; ?>" />
                                                            <p>Are you sure you want to delete this blog post?</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-danger" name="delete-blog-post-btn">Delete</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                    } // end while loop
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div> <!-- End page-inner -->
    </div> <!-- End page-wrapper -->

    <?php //include "footer.php"; ?>
</div> <!-- End wrapper -->

<!-- JS dependencies -->
<script src="assets/js/jquery-1.10.2.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/jquery.metisMenu.js"></script>
<script src="assets/js/custom-scripts.js"></script>
<script src="../js/timeout-warning.js"></script>
<script src="../js/logout-modal.js"></script>
</body>
</html>
