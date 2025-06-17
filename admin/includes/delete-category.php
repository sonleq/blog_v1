<?php 

session_start(); // ensure session is started here too

if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die('CSRF validation failed. Please try again.');
}


require "dbh.php";

if(isset($_POST['delete-category-btn'])){
	
	$id = $_POST['category-id'];
	
	$sqlDeleteCategory = "DELETE FROM blog_category WHERE n_category_id = '$id'";
	
	if(mysqli_query($conn, $sqlDeleteCategory)){
		mysqli_close($conn);
		header("Location: ../blog-category.php?deletecategory=success");
		exit();
	}else{
		mysqli_close($conn);
		header("Location: ../blog-category.php?deletecategory=error");
		exit();
	}
}else{
	
	header("Location: ../index.php");
	exit();
}
