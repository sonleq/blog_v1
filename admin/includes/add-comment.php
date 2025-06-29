<?php

require_once("dbh.php");



$blogPostId = isset($_POST['blogPostId']) ? $_POST['blogPostId'] : "";
$commentSenderName = isset($_POST['cName']) ? $_POST['cName'] : "";
$commentEmail = isset($_POST['cEmail']) ? $_POST['cEmail'] : "";
$comment = isset($_POST['cMessage']) ? $_POST['cMessage'] : "";

// Default to 0 (top-level comment)
$commentParentId = isset($_POST['commentParentId']) && $_POST['commentParentId'] !== "" ? $_POST['commentParentId'] : 0;

$date = date("Y-m-d");
$time = date("H:i:s");

$sqlAddComment = "INSERT INTO blog_comment (n_blog_post_id, v_comment_author, v_comment_author_email, v_comment, d_date_created, d_time_created, n_blog_comment_parent_id) 
VALUES ('$blogPostId','$commentSenderName', '$commentEmail', '$comment', '$date', '$time', '$commentParentId')";

$queryAddComment = mysqli_query($conn, $sqlAddComment);

echo $queryAddComment ? "success" : "error";


?>
