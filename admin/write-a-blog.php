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
require_once "includes/dbh.php";

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
    <!-- Bootstrap Styles-->

    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FontAwesome Styles-->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- Custom Styles-->
    <link href="assets/css/custom-styles.css" rel="stylesheet" />
    <!-- Google Fonts-->
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Baskerville&family=Brush+Script+MT&family=Dancing+Script&family=Futura&family=Garamond&family=Georgia&family=Helvetica&family=Helvetica+Neue&family=Impact&family=Lobster&family=Lucida+Sans&family=Monaco&family=Montserrat&family=Pacifico&family=Playfair+Display&family=Roboto&family=Rockwell&family=Tahoma&family=Times+New+Roman&family=Verdana&display=swap" rel="stylesheet">
	
	<!-- Summernote -->
	<link href="summernote/summernote.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
	
</head>
<body>
    <div id="wrapper">
        <!-- Include the header -->
        <?php include "header.php"; ?>
        <!-- Include the sidebar -->
        <?php include "sidebar.php"; ?>
    
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="page-header">
                            Write A Blog
                        </h1>
                    </div>
                </div> 
                 
                <?php
                // Check if the form submission has failed and display corresponding error messages
                if(isset($_REQUEST['addblog'])){
                    // Define error messages in an associative array
                    $errorMessages = [
                        "emptytitle" => "Please add a blog title.",
                        "emptycategory" => "Please add a blog category.",
                        "emptysummary" => "Please add a blog summary.",
                        "emptycontent" => "Please add a blog content.",
                        "emptytags" => "Please add a blog tag.",
                        "sqlerror" => "Please try again.",
                        "pathcontainsspaces" => "Please make sure there are no spaces in the blog path.",
                        "emptymainimage" => "Please upload a main image.",
                        "emptyaltimage" => "Please upload an alt image.",
                        "mainimageerror" => "There was an error while uploading the main image. Please try again later.",
                        "altimageerror" => "There was an error while uploading the alt image. Please try again later.",
                        "invalidtypealtimage" => "Alt Image -> Upload only jpg, jpeg, png, gif, bmp images.",
                        "invalidtypemainimage" => "Main Image -> Upload only jpg, jpeg, png, gif, bmp images.",
                        "titlebeingused" => "The title is being used in another blog. Try picking another title.",
                        "pathbeingused" => "The path is being used in another blog. Try picking another blog path.",
                        "homepageplacementerror" => "An unexpected error occurred while trying to set the home page placement. Please try again."
                    ];

                    // Get the error code sent from the form submission
                    $errorCode = $_REQUEST['addblog'];
                    // Check if the error code exists in the errorMessages array
                    if (array_key_exists($errorCode, $errorMessages)) {
                        // Display the corresponding error message
                        echo "<div class='alert alert-danger'> 
                                <strong>Error!</strong> " . $errorMessages[$errorCode] . "
                             </div>";
                    }
                }
                ?>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Write A Blog
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <!-- Form for writing a blog -->
                                        <form role="form" method="POST" action="includes/add-blog.php" enctype="multipart/form-data" onsubmit="return validateImage();">
                                            <!-- Title Input -->
                                            <div class="form-group">
                                                <label>Title</label>
                                                <input class="form-control" name="blog-title" value="<?php if(isset($_SESSION['blogTitle'])){ echo $_SESSION['blogTitle'];} ?>">
                                            </div>
                                            
                                            <!-- Meta Title Input -->
                                            <div class="form-group">
                                                <label>Meta Title</label>
                                                <input class="form-control" name="blog-meta-title" value="<?php if(isset($_SESSION['blogMetaTitle'])){ echo $_SESSION['blogMetaTitle'];} ?>">
                                            </div>
                                            
                                            <!-- Blog Category Dropdown -->
                                            <div class="form-group">
                                                <label>Blog Category</label>
                                                <select class="form-control" name="blog-category">
                                                    <option value="">Select a Category</option>
                                                    <?php 
                                                        // Fetch categories from the database
                                                        $sqlCategories = "SELECT * FROM blog_category";
                                                        $queryCategories = mysqli_query($conn, $sqlCategories);

                                                        // Loop through the categories and display them in the dropdown
                                                        while($rowCategories = mysqli_fetch_assoc($queryCategories)){
                                                            $cId = $rowCategories['n_category_id'];
                                                            $cName = $rowCategories['v_category_title'];

                                                            // Check if a category was previously selected
                                                            if(isset($_SESSION['blogCategoryId'])){
                                                                if($_SESSION['blogCategoryId'] == $cId){
                                                                    echo "<option value='".$cId."' selected=''>".$cName."</option>";
                                                                } else {
                                                                    echo "<option value='".$cId."'>".$cName."</option>";
                                                                }
                                                            } else {
                                                                echo "<option value='".$cId."'>".$cName."</option>";
                                                            }
                                                        }
                                                    ?>
                                                </select>
                                            </div>

                                            <!-- Main Image Input -->
                                            <div class="form-group">
                                                <label>Main Image</label>
                                                <input type="file" name="main-blog-image" id="main-blog-image">
                                            </div>

                                            <!-- Alternate Image Input -->
                                            <div class="form-group">
                                                <label>Alternate Image</label>
                                                <input type="file" name="alt-blog-image" id="alt-blog-image">
                                            </div>

                                            <!-- Blog Summary Textarea -->
                                            <div class="form-group">
                                                <label>Blog Summary</label>
                                                <textarea class="form-control" rows="3" name="blog-summary"><?php if(isset($_SESSION['blogSummary'])){ echo $_SESSION['blogSummary'];} ?></textarea>
                                            </div>

                                            <!-- Blog Content Textarea -->
                                            <div class="form-group">
                                                <label>Blog Content</label>
                                                <textarea class="form-control" rows="3" name="blog-content" id="summernote"><?php if(isset($_SESSION['blogContent'])){ echo $_SESSION['blogContent'];} ?></textarea>
                                            </div>

                                            <!-- Blog Tags Input -->
                                            <div class="form-group">
                                                <label>Blog Tags (separated by comma)</label>
                                                <input class="form-control" name="blog-tags" value="<?php if(isset($_SESSION['blogTags'])){ echo $_SESSION['blogTags'];} ?>">
                                            </div>

                                            <!-- Blog Path Input -->
                                            <div class="form-group">
                                                <label>Blog Path</label>                                         
                                                <div class="input-group">
                                                     <span class="input-group-addon">https://sonqle.netlify.app/</span>
                                                     <input type="text" class="form-control" name="blog-path" value="<?php if(isset($_SESSION['blogPath'])){ echo $_SESSION['blogPath'];} ?>">
                                                </div>  
                                           </div>

                                            <!-- Home Page Placement Radio Buttons -->
                                            <div class="form-group">
                                                <label>Home Page Placement</label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="blog-home-page-placement" id="optionsRadiosInline1" value="1" <?php if(isset($_SESSION['blogHomePagePlacement'])){ if($_SESSION['blogHomePagePlacement'] == 1){echo "checked=''";}} ?> >1
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="blog-home-page-placement" id="optionsRadiosInline2" value="2" <?php if(isset($_SESSION['blogHomePagePlacement'])){ if($_SESSION['blogHomePagePlacement'] == 2){echo "checked=''";}} ?>>2
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="blog-home-page-placement" id="optionsRadiosInline3" value="3" <?php if(isset($_SESSION['blogHomePagePlacement'])){ if($_SESSION['blogHomePagePlacement'] == 3){echo "checked=''";}} ?>>3
                                                </label>
                                            </div>

                                            <!-- Submit Button -->
                                            <button type="submit" class="btn btn-default" name="submit-blog">Add Blog</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Include footer -->
                <?php include "footer.php"; ?>
            </div>
        </div>
    </div>
    
    <!-- jQuery -->
     <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Bootstrap -->
    <script src="assets/js/bootstrap.min.js"></script>
    <!-- Custom Scripts -->
    <script src="assets/js/custom-scripts.js"></script>
	 <!-- Summernote -->
    <script src="summernote/summernote.min.js"></script>
 
	
	  <!-- Edit Summernote functionality/height-->
   
	
	<script src="summernote/function.js"> </script>
    <!-- JavaScript function to validate images -->
    <script> 
        /*function validateImage(){
            var main_img = $("#main-blog-image").val();
            var alt_img = $("#alt-blog-image").val();
            
            var exts = ['jpg','jpeg','png','gif', 'bmp'];
            
            var get_ext_main_img = main_img.split('.');
            var get_ext_alt_img = alt_img.split('.');
            
            get_ext_main_img = get_ext_main_img.reverse();
            get_ext_alt_img  = get_ext_alt_img.reverse();
            
            main_image_check = false;
            alt_image_check = false;
        
        if(main_img.length > 0){
                if($.inArray(get_ext_main_img[0].toLowerCase(), exts) >= -1){
                    main_image_check = true;
                
                } else {
                    alert("Error -> Main Image. Upload only jpg, jpeg, png, gif, bmp images.");
                    main_img_check = false;
                }
            
            } else {
                alert("Please upload a main image.");
                main_img_check = false;
            }
        
        if(alt_img.length > 0){
                if($.inArray(get_ext_alt_img[0].toLowerCase(), exts) >= -1){
                    alt_image_check = true;
                
                } else {
                    alert("Error -> Alternative Image. Upload only jpg, jpeg, png, gif, bmp images.");
                    alt_img_check = false;
                }
            
            } else {
                alert("Please upload an alternative image.");
                alt_img_check = false;
            }
            
            if(main_image_check == true && alt_image_check == true){
                return true;
            
            } else {
                return false;
            }
        }*/
    </script>
</body>
</html>
