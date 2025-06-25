<?php
require "dbh.php";
header('Content-Type: application/json');

// Count active blog posts
$sqlNumBlogs = "SELECT COUNT(*) AS total FROM blog_post WHERE f_post_status = '1'";
$resultNumBlogs = mysqli_query($conn, $sqlNumBlogs);
$numBlogs = ($resultNumBlogs && $row = mysqli_fetch_assoc($resultNumBlogs)) ? $row['total'] : 0;

// Count comments only for active blog posts
$sqlNumComments = "
    SELECT COUNT(*) AS total
    FROM blog_comment
    JOIN blog_post ON blog_comment.n_blog_post_id = blog_post.n_blog_post_id
    WHERE blog_post.f_post_status = '1'";
$resultNumComments = mysqli_query($conn, $sqlNumComments);
$numComments = ($resultNumComments && $row = mysqli_fetch_assoc($resultNumComments)) ? $row['total'] : 0;

// **Count subscribers**
$sqlNumSubscribers = "SELECT COUNT(*) AS total FROM subscribers";
$resultNumSubscribers = mysqli_query($conn, $sqlNumSubscribers);
$numSubscribers = ($resultNumSubscribers && $row = mysqli_fetch_assoc($resultNumSubscribers)) ? $row['total'] : 0;

echo json_encode([
    'blogs' => $numBlogs,
    'comments' => $numComments,
    'subscribers' => $numSubscribers
]);
