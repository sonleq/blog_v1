<?php

// Include the database connection
require "includes/dbh.php";
// Start the session for user and blog data management
session_start();

// Check if a blog ID has been passed in the request (via GET/POST)
if(isset($_REQUEST['blogid'])){
	
	// Store the blog ID in a variable
	$blogId = $_REQUEST['blogid'];
	
	// If the blog ID is empty, redirect back to the blogs page
	if(empty($blogId)){
		header("Location: blogs.php");
		exit();
	}

	// Store the blog ID in session for later use
	$_SESSION['editBlogId'] = $_REQUEST['blogid'];
	
	// Fetch blog details from the database based on the provided blog ID
	$sqlGetBlogDetails = "SELECT * FROM blog_post WHERE n_blog_post_id = '$blogId'";
	$queryGetBlogDetails = mysqli_query($conn, $sqlGetBlogDetails);
	
	// If the blog details are found, store them in session variables
	if($rowGetBlogDetails = mysqli_fetch_assoc($queryGetBlogDetails)){
		
		$_SESSION['editTitle'] = $rowGetBlogDetails['v_post_title'];
		$_SESSION['editMetaTitle'] = $rowGetBlogDetails['v_post_meta_title'];
		$_SESSION['editCategoryId'] = $rowGetBlogDetails['n_category_id'];
		$_SESSION['editSummary'] = $rowGetBlogDetails['v_post_summary'];
		$_SESSION['editContent'] = $rowGetBlogDetails['v_post_content'];
		$_SESSION['editPath'] = $rowGetBlogDetails['v_post_path'];
		$_SESSION['editHomePagePlacement'] = $rowGetBlogDetails['n_home_page_placement'];
		
	}else{
		// If no blog details are found, redirect to blogs page
		header("Location: blogs.php");
		exit();
	}
	
	// Fetch tags associated with the blog post
	$sqlGetBlogTags = "SELECT * FROM blog_tag WHERE n_blog_post_id = '$blogId'";
	$queryGetBlogTags = mysqli_query($conn, $sqlGetBlogTags);
	
	// Store the tags in session
	if($rowGetBlogTags = mysqli_fetch_assoc($queryGetBlogTags)){
		$_SESSION['editTags'] = $rowGetBlogTags['v_tag'];
	}
}	

// If blog ID is not provided and no session variable exists, redirect to blogs page
else if(isset($_SESSION['editBlogId'])){}
// If no blog ID is found, redirect to blogs page
else{
	header("Location: blogs.php");
	exit();
}
	
		// Fetch the images related to the blog post
		$sqlGetImages = "SELECT * FROM blog_post WHERE n_blog_post_id = '".$_SESSION['editBlogId']."'";
		$queryGetImages = mysqli_query($conn, $sqlGetImages);
		if($rowGetImages = mysqli_fetch_assoc($queryGetImages)){
			$mainImgUrl = $rowGetImages['v_main_image_url'];
			$altImgUrl = $rowGetImages['v_alt_image_url'];
		}

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
      <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Free Bootstrap Admin Template : Dream</title>
	<!-- Bootstrap Styles-->
	     <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

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
		
		<?php include "header.php"; ?>
		<?php include "sidebar.php"; ?>
	
        <div id="page-wrapper" >
            <div id="page-inner">
			 <div class="row">
                    <div class="col-md-12">
                        <h1 class="page-header">
                            Edit Blog Post
                        </h1>
                    </div>
                </div> 
             				
<?php    
    // Check if any update error code was passed to display the appropriate message
    if(isset($_REQUEST['updateblog'])){
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
            "mainimageerror" => "Please upload another main image.",
            "altimageerror" => "Please upload another alt image.",
            "invalidtypealtimage" => "Alt Image -> Upload only jpg, jpeg, png, gif, bmp images.",
            "invalidtypemainimage" => "Main Image -> Upload only jpg, jpeg, png, gif, bmp images.",
            "erroruploadingmainimage" => "Main Image -> There was an error while uploading. Please try again later.",
            "erroruploadingaltimage" => "Alt Image -> There was an error while uploading. Please try again later.",
            "titlebeingused" => "The title is being used in another blog. Try picking another title.",
            "pathbeingused" => "The path is being used in another blog. Try picking another blog path.",
            "homepageplacementerror" => "An unexpected error occurred while trying to set the home page placement. Please try again."
        ];

        // Get the error code from the request
        $errorCode = $_REQUEST['updateblog'];

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
                            Edit: <?php echo $_SESSION['editTitle']; ?>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <!-- Form to edit the blog -->
                                    <form role="form" method="POST" action="includes/update-blog.php" enctype="multipart/form-data">
									<input type="hidden" name="blog-id" value="<?php echo $blogId; ?>" />
                                        <div class="form-group">
                                            <label>Title</label>
                                            <input class="form-control" name="blog-title" value="<?php echo $_SESSION['editTitle']; ?>">
                                        </div>
										 <div class="form-group">
                                            <label>Meta Title</label>
                                            <input class="form-control" name="blog-meta-title" value="<?php echo $_SESSION['editMetaTitle']; ?>">
                                        </div>						
										<div class="form-group">
                                            <label>Blog Category</label>
                                            <select class="form-control" name="blog-category">
                                               <option value="">Select a Category</option>
											   
											  <?php 
														// Fetch categories from the database
														$sqlCategories   = "SELECT * FROM blog_category";
														$queryCategories =  mysqli_query($conn, $sqlCategories);

														// Loop through categories to display them in the dropdown
														while($rowCategories = mysqli_fetch_assoc($queryCategories)){
															$cId = $rowCategories['n_category_id'];
															$cName = $rowCategories['v_category_title'];
															
															// Check if the session has a selected category, and mark it as selected
				
																if($_SESSION['editCategoryId'] == $cId){
																	echo "<option value='".$cId."' selected=''>".$cName."</option>";
																} else {
																	echo "<option value='".$cId."'>".$cName."</option>";
																}
														
														}
													?>
                                            </select>
                                        </div>					                       
                                        <div class="form-group">
                                            <label>Update Main Image</label>
                                            <input type="file" name="main-blog-image" id="main-blog-image">
											<?php
												if(!empty($mainImgUrl)){
													echo "<p style='font-size:inherit;'><a href='' data-toggle='modal' data-target='#main-image' class='popup-button' style='margin-top:10px'>View Existing Image</a></p>";
												}
											?>
                                        </div>
										<div class="form-group">
                                            <label>Update Alternate Image</label>
                                            <input type="file" name="alt-blog-image" id="alt-blog-image">
											<?php
												if(!empty($mainImgUrl)){
													echo "<p style='font-size:inherit;'><a href='' data-toggle='modal' data-target='#alt-image' class='popup-button' style='margin-top:10px'>View Existing Image</a></p>";
												}
											?>
                                        </div>
                                        <div class="form-group">
                                            <label>Blog Summary</label>
                                            <textarea class="form-control" rows="3" name="blog-summary"> <?php echo $_SESSION['editSummary']; ?> </textarea>
                                        </div>
										<div class="form-group">
                                            <label>Blog Content</label>
                                            <textarea class="form-control" rows="3" name="blog-content" id="summernote"><?php echo $_SESSION['editContent']; ?></textarea>
                                        </div>
                                       <div class="form-group">
                                            <label>Blog Tags (separated by comma)</label>
                                            <input class="form-control" name="blog-tags" value="<?php echo isset($_SESSION['editTags']) ? $_SESSION['editTags'] : ''; ?>">
                                        </div>
								   <div class="form-group">
										<label>Blog Path</label>										 
										<div class= "input-group">
											 <span class="input-group-addon">https://sonqle.netlify.app/</span>
											 <input type="text" class="form-control" name= "blog-path" value="<?php echo $_SESSION['editPath']; ?>">
										</div>  
                                   </div>
								    <div class="form-group">
                                            <label>Home Page Placement</label>
                                            <label class="radio-inline">
                                                <input type="radio" name="blog-home-page-placement" id="optionsRadiosInline1" value="1" <?php if(isset($_SESSION['editHomePagePlacement'])){ if($_SESSION['editHomePagePlacement'] == 1){echo "checked=''";}} ?> >1
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="blog-home-page-placement" id="optionsRadiosInline2" value="2" <?php if(isset($_SESSION['editHomePagePlacement'])){ if($_SESSION['editHomePagePlacement'] == 2){echo "checked=''";}} ?>>2
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="blog-home-page-placement" id="optionsRadiosInline3" value="3" <?php if(isset($_SESSION['editHomePagePlacement'])){ if($_SESSION['editHomePagePlacement'] == 3){echo "checked=''";}} ?>>3
                                            </label>
                                        </div>
                                        <!-- Submit Button -->
                                        <button type="submit" class="btn btn-default" name="submit-edit-blog">Save Changes</button>
                                    </form>
                                </div>
                            </div>
                            <!-- /.row (nested) -->
							
							<?php 
							if(!empty($mainImgUrl)){
							
							?>
							 <div class="modal fade" id="main-image" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                                <h4 class="modal-title" id="myModalLabel">Main Image</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <img src="<?php echo $mainImgUrl; ?>" style="max-width:100%; height:auto;" />
                                                          
                                                            </div>
                                                            <div class="modal-footer">
                                                                <!-- Button to close the modal without deleting -->
                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                
                                                            </div>
                                                     
                                                    </div>
                                                </div>
                                            </div>	
							<?php } ?>
							
							<?php 
							if(!empty($altImgUrl)){
							
							?>
							 <div class="modal fade" id="alt-image" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                                <h4 class="modal-title" id="myModalLabel">Alt Image</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <img src="<?php echo $altImgUrl; ?>" style="max-width:100%; height:auto;" />
                                                          
                                                            </div>
                                                            <div class="modal-footer">
                                                                <!-- Button to close the modal without deleting -->
                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                
                                                            </div>
                                                   
                                                    </div>
                                                </div>
                                            </div>	
							<?php } ?>
							
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
			
			<?php include "footer.php"; ?>
			
			</div>
             <!-- /. PAGE INNER  -->
            </div>
         <!-- /. PAGE WRAPPER  -->
        </div>
     <!-- /. WRAPPER  -->
    <!-- JS Scripts-->
    <!-- jQuery Js -->
   <!-- jQuery -->
    <!-- Bootstrap -->
    <script src="assets/js/bootstrap.min.js"></script>
    <!-- Custom Scripts -->
    <script src="assets/js/custom-scripts.js"></script>
	 <!-- Summernote -->
    <script type="text/javascript" src="summernote/summernote.min.js"></script>
 
	
	 <!-- Edit Summernote functionality/height-->
   
	<script src="summernote/function.js"> </script>

	<script> 
		// JavaScript function to validate the uploaded images before form submission
		/*function validateImage(){
			
				var main_img = $("#main-blog-image").val();
				var alt_img =  $("#alt-blog-image").val();
				
				// Allowed file types for images
				var exts = ['jpg','jpeg','png','gif', 'bmp'];
				
				// Get file extensions of the uploaded images
				var get_ext_main_img = main_img.split('.');
				var get_ext_alt_img = alt_img.split('.');
				
				get_ext_main_img = get_ext_main_img.reverse();
				get_ext_alt_img  = get_ext_alt_img.reverse();
				
				// Flags to check if the images are valid
				main_image_check = false;
				alt_image_check = false;
			
				// Validate main image
			if(main_img.length > 0){
					if($.inArray(get_ext_main_img[0].toLowerCase(), exts) >= -1){
						main_image_check = true;
					
					}else{
						alert("Error -> Main Image. Upload only jpg, jpeg, png, gif, bmp images.");
						main_img_check = false;
					}
				
				}else{
					 
					main_img_check = true;
				}
				
				// Validate alt image
			if(alt_img.length > 0){
					if($.inArray(get_ext_alt_img[0].toLowerCase(), exts) >= -1){
						alt_image_check = true;
					
					}else{
						alert("Error -> Alternative Image. Upload only jpg, jpeg, png, gif, bmp images.");
						alt_img_check = false;
					}
				
				}else{
					alt_img_check = true;
				}
				
				// If both images are valid, submit the form
				if(main_image_check == true && alt_image_check == true){
					return true;
				
				}else{
					return false;
				}
		}*/
	</script>
   
</body>
</html>
