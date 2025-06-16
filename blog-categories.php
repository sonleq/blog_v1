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

// Include database connection
require "includes/dbh.php";
include "includes/unset-session.php";


// Query to fetch all categories from the database
$sqlCategories = "SELECT * FROM blog_category";
$queryCategories = mysqli_query($conn, $sqlCategories);
$numCategories = mysqli_num_rows($queryCategories);

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
    <title>Free Bootstrap Admin Template : Dream</title>
	<!-- Bootstrap Styles -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FontAwesome Styles -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- Custom Styles -->
    <link href="assets/css/custom-styles.css" rel="stylesheet" />
    <!-- Google Fonts -->
   <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
   	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
	<div id="wrapper">
		<?php include "header.php"; ?> <!-- Include header -->
		<?php include "sidebar.php"; ?> <!-- Include sidebar -->
		
		<!-- Page Wrapper -->
		<div id="page-wrapper">
			<div id="page-inner">
				<!-- Page Header -->
				<div class="row">
					<div class="col-md-12">
						<h1 class="page-header">
							Blog Categories
						</h1>
					</div>
				</div>

				<!-- Display success or error messages based on the category actions -->
				<?php
				// Check for category-related status messages (add, edit, delete)
				if(isset($_REQUEST['addcategory'])){
					if($_REQUEST['addcategory'] == "success"){
						echo "<div class='alert alert-success'><strong>Successful!!</strong> Changes to the Category were saved. </div>";
					}else if($_REQUEST['addcategory'] == "error"){
						echo "<div class='alert alert-danger'><strong>Error!</strong> Changes to the Category were not saved, there was an unexpected error. </div>";
					}
				}else if(isset($_REQUEST['editcategory'])){
					if($_REQUEST['editcategory'] == "success"){
						echo "<div class='alert alert-success'><strong>Successful!!</strong> Category added. </div>";
					}else if($_REQUEST['editcategory'] == "error"){
						echo "<div class='alert alert-danger'><strong>Error!</strong> Category was not added, there was an unexpected error. </div>";
					}
				}else if(isset($_REQUEST['deletecategory'])){
					if($_REQUEST['deletecategory'] == "success"){
						echo "<div class='alert alert-success'><strong>Successful!!</strong> Category deleted. </div>";
					}else if($_REQUEST['deletecategory'] == "error"){
						echo "<div class='alert alert-danger'><strong>Error!</strong> Category was not deleted. </div>";
					}
				}
				?>

				<!-- Form to Add New Category -->
				<div class="row">
					<div class="col-lg-12">
						<div class="panel panel-default">
							<div class="panel-heading">
								Add Category
							</div>
							<div class="panel-body">
								<form role="form" method="POST" action="includes/add-category.php">
									<div class="form-group">
										<label>Name</label>
										<input class="form-control" name="category-name" required pattern="[^ ]+" title="Category name cannot be just spaces">
									</div>
									<div class="form-group">
										<label>Meta Title</label>
										<input class="form-control" name="category-meta-title" required pattern="[^ ]+" title="Category name cannot be just spaces">
									</div>
									<div class="form-group">
										<label>Category Path (lower case, no spaces)</label>
										<input class="form-control" name="category-path" required pattern="[^ ]+" title="Category path cannot be just spaces">
									</div>
									<button type="submit" class="btn btn-default" name="add-category-btn">Add Category</button>
								</form>
							</div>
						</div>
					</div>
				</div>

				<!-- Display All Categories in a Table -->
				<div class="panel panel-default">
					<div class="panel-heading">
						All Categories
					</div>
					<div class="panel-body">
						<div class="table-responsive">
							<table class="table table-striped table-bordered table-hover">
								<thead>
									<tr>
										<th>Id</th>
										<th>Name</th>
										<th>Meta Title</th>
										<th>Category Path</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
									// Counter for category IDs
									$counter = 0;

									// Loop through categories and display them
									while($rowCategories = mysqli_fetch_assoc($queryCategories)){
										$counter++;

										// Assign category details to variables
										$id = $rowCategories['n_category_id'];
										$name = $rowCategories['v_category_title'];
										$metaTitle = $rowCategories['v_category_meta_title'];
										$categoryPath = $rowCategories['v_category_path'];
									?>

									<tr>
										<td><?php echo $counter; ?></td>
										<td><?php echo $name; ?></td>
										<td><?php echo $metaTitle; ?></td>
										<td><?php echo $categoryPath; ?></td>
										<td>
											<!-- Action Buttons for View, Edit, and Delete -->
											<button class="popup-button" onclick="window.open('../categories.php?group=<?php echo $categoryPath; ?>', '_blank');">View</button>
											<button data-toggle="modal" name="edit-category-btn" data-target="#edit<?php echo $id; ?>" class="popup-button">Edit</button>
											<button data-toggle="modal" name="delete-category-btn" data-target="#delete<?php echo $id; ?>" class="popup-button">Delete</button>
										</td>
									</tr>

									<!-- Modal for Editing Category -->
									<div class="modal fade" id="edit<?php echo $id; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
										<div class="modal-dialog">
											<div class="modal-content">
												<form method="POST" action="includes/edit-category.php">
													<div class="modal-header">
														<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
														<h4 class="modal-title" id="myModalLabel">Edit Category</h4>
													</div>
													<div class="modal-body">
														<input type="hidden" name="category-id" value="<?php echo $id; ?>" />
														<label>Name</label>
														<input class="form-control" name="edit-category-name" value="<?php echo $name; ?>" />
													</div>
													<div class="modal-body">
														<label>Meta Title</label>
														<input class="form-control" name="edit-category-meta-title" value="<?php echo $metaTitle; ?>" />
													</div>
													<div class="modal-body">
														<label>Category Path</label>
														<input class="form-control" name="edit-category-path" value="<?php echo $categoryPath; ?>" />
													</div>
													<div class="modal-footer">
														<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
														<button type="submit" class="btn btn-primary" name="edit-category-btn">Save changes</button>
													</div>
												</form>
											</div>
										</div>
									</div>

									<!-- Modal for Deleting Category -->
									<div class="modal fade" id="delete<?php echo $id; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
										<div class="modal-dialog">
											<div class="modal-content">
												<form method="POST" action="includes/delete-category.php">
													<div class="modal-header">
														<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
														<h4 class="modal-title" id="myModalLabel">Delete Category</h4>
													</div>
													<div class="modal-body">
														<input type="hidden" name="category-id" value="<?php echo $id; ?>" />
														<p>Are you sure you want to delete this category?</p>
													</div>
													<div class="modal-footer">
														<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
														<button type="submit" class="btn btn-primary" name="delete-category-btn">Delete</button>
													</div>
												</form>
											</div>
										</div>
									</div>
									<?php
									}
									?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>

			<!-- Footer -->
			<?php include "footer.php"; ?>
		</div>
	</div>

	<!-- JS Scripts -->
	<script src="assets/js/jquery-1.10.2.js"></script>
	<script src="assets/js/bootstrap.min.js"></script>
	<script src="assets/js/jquery.metisMenu.js"></script>
	<script src="assets/js/custom-scripts.js"></script>
</body>
</html>
