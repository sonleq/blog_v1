<?php

session_start(); // ensure session is started here too

if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die('CSRF validation failed. Please try again.');
}


require "dbh.php";

if(isset($_POST['edit-category-btn'])){
	
	$id = $_POST['category-id'];
	$name = $_POST['edit-category-name'];
	$metaTitle = $_POST['edit-category-meta-title'];
	$categoryPath = $_POST['edit-category-path'];
	
	$sqlEditCategory  = "UPDATE blog_category
						SET 
							v_category_title = '$name',
							v_category_meta_title= '$metaTitle', 
							v_category_path = '$categoryPath' 
						WHERE n_category_id = '$id'";
	
	if(mysqli_query($conn, $sqlEditCategory)){
		mysqli_close($conn);
		header("Location: ../blog-category.php?editcategory=success");
		exit();
	}else{
		
		mysqli_close($conn);
		header("Location: ../blog-category.php?editcategory=error");
		exit();
	}
	
}else{
	header("Location: ../index.php");
	exit();
}
