<?php 

require "dbh.php";

if(isset($_POST['delete-blog-post-btn'])){
	
	$id = $_POST['blog-post-id'];
	
	// it doesn't DELETE but UPDATE, base on the 0,1,2 f_post_status
	$sqlDeleteBlogPost = "UPDATE blog_post SET f_post_status = '2' WHERE n_blog_post_id = '$id'";
	
	if(mysqli_query($conn, $sqlDeleteBlogPost)){
		mysqli_close($conn);
		header("Location: ../blogs.php?deleteblogpost=success");
		exit();
	
	}else{
		mysqli_close($conn);
		header("Location: ../blogs.php?deleteblogpost=error");
	}
	
}else{
	header("Location: ../index.php");
	exit();
}


?>
