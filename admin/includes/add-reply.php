<?php
require_once("dbh.php");

$blogPostId = isset($_POST['replyBlogPostId']) ? $_POST['replyBlogPostId'] : "";
$commentParentId = isset($_POST['commentParentId']) ? $_POST['commentParentId'] : "";
$commentSenderName = isset($_POST['replycName']) ? $_POST['replycName'] : "";
$commentEmail = isset($_POST['replycEmail']) ? $_POST['replycEmail'] : "";
$comment = isset($_POST['replycMessage']) ? $_POST['replycMessage'] : "";

// Default to 0 (top-level comment)
//$commentParentId = isset($_POST['commentParentId']) && $_POST['commentParentId'] !== "" ? $_POST['commentParentId'] : 0;

$date = date("Y-m-d");
$time = date("H:i:s");

$sqlAddReply = "INSERT INTO blog_comment (n_blog_post_id, v_comment_author, v_comment_author_email, v_comment, d_date_created, d_time_created, n_blog_comment_parent_id) 
VALUES ('$blogPostId','$commentSenderName', '$commentEmail', '$comment', '$date', '$time', '$commentParentId')";

$queryAddReply = mysqli_query($conn, $sqlAddReply);

if(!$queryAddReply){
	$result = "error";

	}else{
		$result = "success";
	}
	
echo $result;
?>
