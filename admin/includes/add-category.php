<?php

session_start();

if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die('CSRF validation failed. Please try again.');
}

require "dbh.php";

if (isset($_POST['add-category-btn'])) {

    $name = trim($_POST['category-name']);
    $metaTitle = trim($_POST['category-meta-title']);
    $categoryPath = trim($_POST['category-path']);

    // Check for empty inputs
    if (empty($name) || empty($metaTitle) || empty($categoryPath)) {
        mysqli_close($conn);
        header("Location: ../blog-category.php?addcategory=empty");
        exit();
    }

    // Check for duplicates
    $checkSql = "SELECT * FROM blog_category WHERE v_category_title = '$name'";
    $result = mysqli_query($conn, $checkSql);

    if (mysqli_num_rows($result) > 0) {
        mysqli_close($conn);
        header("Location: ../blog-category.php?addcategory=exists");
        exit();
    }

    date_default_timezone_set('America/New_York');
    $date = date("Y-m-d");
    $time = date("h:i:sa");

    $sqlAddCategory = "INSERT INTO blog_category (
                            v_category_title,
                            v_category_meta_title,
                            v_category_path,
                            d_date_created,
                            d_time_created
                        ) VALUES (
                            '$name',
                            '$metaTitle',
                            '$categoryPath',
                            '$date',
                            '$time'
                        )";

    if (mysqli_query($conn, $sqlAddCategory)) {
        mysqli_close($conn);
        header("Location: ../blog-category.php?addcategory=success");
        exit();
    } else {
        mysqli_close($conn);
        header("Location: ../blog-category.php?addcategory=error");
        exit();
    }

} else {
    header("Location: ../index.php");
    exit();
}
