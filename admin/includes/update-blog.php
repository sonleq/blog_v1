<?php


// Include database connection
require "dbh.php";

// Start a new session or resume the existing sessionf
session_start();

// Check if the submit-blog button was clicked
if(isset($_POST['submit-edit-blog'])){
    
    // Capture all form data
	$blogId = $_POST['blog-id'];
    $title = $_POST['blog-title'];
    $metaTitle = $_POST['blog-meta-title'];
    $blogCategoryId = $_POST['blog-category'];
    $blogSummary = $_POST['blog-summary'];
    $blogContent = $_POST['blog-content'];
    $blogTags = $_POST['blog-tags'];
    $blogPath = $_POST['blog-path'];
    $homePagePlacement = $_POST['blog-home-page-placement'];
    
    // Get the current date and time
    $date = date("Y-m-d");
    $time = date("H:i:s");

    // Check for empty fields and call formError function with corresponding error code
    if(empty($title)){
        formError("emptytitle");
    }
    else if (empty($blogCategoryId)){
        formError("emptycategory");
    }
    else if(empty($blogSummary)){
        formError("emptysummary");
    }
    else if (empty($blogContent)){
        formError("emptycontent");
    }
    else if(empty($blogTags)){
        formError("emptytags");
    }
    else if(empty($blogPath)){
        formError("emptypath");
    }
    // Check if the blog path contains spaces
    if(strpos($blogPath, " ") !== false){
        formError("pathcontainsspaces");
    }
    // If no home page placement is selected, set it to 0
    if(empty($homePagePlacement)){
        $homePagePlacement = 0;
    }
    
    // Check if the title already exists in the database (excluding deleted posts)
    $sqlCheckBlogTitle = "SELECT v_post_title FROM blog_post WHERE v_post_title = '$title' AND v_post_title != '$title' AND f_post_status !='2'";
    $queryCheckBlogTitle = mysqli_query($conn, $sqlCheckBlogTitle);
    
    // Check if the blog path already exists in the database (excluding deleted posts)
    $sqlCheckBlogPath = "SELECT v_post_path FROM blog_post WHERE v_post_path = '$blogPath' AND v_post_path != '$blogPath' AND f_post_status !='2'";
    $queryCheckBlogPath = mysqli_query($conn, $sqlCheckBlogPath);
    
    // If the title or path already exists, call formError with corresponding error
    if(mysqli_num_rows($queryCheckBlogTitle) > 0){
        formError("titlebeingused");
    }else if(mysqli_num_rows($queryCheckBlogPath) > 0){
        formError("pathbeingused");
    }
    
    // Check if the home page placement is set and update the blog home page placement if necessary
    if($homePagePlacement !=0){
        $sqlCheckBlogHomePagePlacement = "SELECT * FROM blog_post WHERE n_home_page_placement = '$homePagePlacement' AND f_post_status != '2'";
        $queryCheckBlogHomePagePlacement = mysqli_query($conn, $sqlCheckBlogHomePagePlacement);
        
        if(mysqli_num_rows($queryCheckBlogHomePagePlacement ) > 0 ){
			
            // If another blog is placed in the same home page spot, set the previous blog's placement to 0
            $sqlUpdateBlogHomePagePlacement = "UPDATE blog_post SET n_home_page_placement = '0' WHERE n_home_page_placement = '$homePagePlacement' AND f_post_status !='2'";
			
            if(!mysqli_query($conn, $sqlUpdateBlogHomePagePlacement)){
                formError("homepageplacementerror");
            }
        }
    }

    // Handle image uploads for main and alternate images
    $mainImgUrl = uploadImage($_FILES["main-blog-image"]["name"], "main-blog-image", "main", "v_main_image_url");
    $altImgUrl  = uploadImage($_FILES["alt-blog-image"]["name"], "alt-blog-image", "alt", "v_alt_image_url");
    

	
	if($mainImgUrl == "noupdate"){
		if($altImgUrl == "noupdate"){
			
				$sqlUpdateBlog = "
						UPDATE blog_post SET 
								n_category_id = '$blogCategoryId', 
								v_post_title = '$title',
								v_post_meta_title ='$metaTitle',
								v_post_path = '$blogPath',
								v_post_summary = '$blogSummary', 
								v_post_content = '$blogContent',
								n_home_page_placement = '$homePagePlacement', 
								d_date_updated = '$date',
								d_time_updated = '$time' 
						WHERE n_blog_post_id = '$blogId'";

			
		}else{
			
				$sqlUpdateBlog = "
						UPDATE blog_post SET 
								n_category_id = '$blogCategoryId', 
								v_post_title = '$title',
								v_post_meta_title ='$metaTitle',
								v_post_path = '$blogPath',
								v_post_summary = '$blogSummary', 
								v_post_content = '$blogContent',
								v_alt_image_url = '$altImgUrl',
								n_home_page_placement = '$homePagePlacement', 
								d_date_updated = '$date',
								d_time_updated = '$time' 
						WHERE n_blog_post_id = '$blogId'";
			
		}
		
	}else if($altImgUrl == "noupdate"){
		if($mainImgUrl != "noupdate"){
			
				$sqlUpdateBlog = "
						UPDATE blog_post SET 
								n_category_id = '$blogCategoryId', 
								v_post_title = '$title',
								v_post_meta_title ='$metaTitle',
								v_post_path = '$blogPath',
								v_post_summary = '$blogSummary', 
								v_post_content = '$blogContent',
								v_main_image_url = '$mainImgUrl',
								n_home_page_placement = '$homePagePlacement', 
								d_date_updated = '$date',
								d_time_updated = '$time' 
						WHERE n_blog_post_id = '$blogId'";
			
		}
	}else{
		
				$sqlUpdateBlog = "
						UPDATE blog_post SET 
								n_category_id = '$blogCategoryId', 
								v_post_title = '$title',
								v_post_meta_title ='$metaTitle',
								v_post_path = '$blogPath',
								v_post_summary = '$blogSummary', 
								v_post_content = '$blogContent',
								v_main_image_url = '$mainImgUrl',
								v_alt_image_url = '$altImgUrl',
								n_home_page_placement = '$homePagePlacement', 
								d_date_updated = '$date',
								d_time_updated = '$time' 
						WHERE n_blog_post_id = '$blogId'";
	
	}
 
	$sqlUpdateBlogTags = "UPDATE blog_tag SET v_tag = '$blogTags' WHERE n_blog_post_id = '$blogId'";

    // Check if the query was successful
    if(mysqli_query($conn, $sqlUpdateBlog) && mysqli_query($conn,  $sqlUpdateBlogTags)){
        
		formSuccess();
       
    }else{
        // If the query failed, call formError with SQL error
        formError("sqlerror");
    }
}else{
    // If the form wasn't submitted, redirect to the index page
    header("Location: ../index.php");
    exit();
}

function formSuccess(){
	
	require "dbh.php";
	
	 // Close the database connection
        mysqli_close($conn);
        
        // Clear session variables
        unset($_SESSION['editBlogId']);
        unset($_SESSION['editTitle']);
        unset($_SESSION['editMetaTitle']);
        unset($_SESSION['editCategoryId']);
        unset($_SESSION['editSummary']);
        unset($_SESSION['editContent']);
        unset($_SESSION['editPath']);
		unset($_SESSION['editTags']);
        unset($_SESSION['editHomePagePlacement']);
        
        // Redirect to blogs page with success message
        header("Location: ../blogs.php?updateblog=success");
        exit();
	
}

// Function to handle form errors and redirect with error codes
function formError($errorCode){
    
	require "dbh.php";
    
    // Store form data in session variables for reuse after the error
    $_SESSION['editTitle'] = $_POST['blog-title'];
    $_SESSION['editMetaTitle'] = $_POST['blog-meta-title'];
    $_SESSION['editCategoryId'] = $_POST['blog-category'];
    $_SESSION['editSummary'] = $_POST['blog-summary'];
    $_SESSION['editContent'] = $_POST['blog-content'];
    $_SESSION['editTags'] = $_POST['blog-tags'];
    $_SESSION['editPath'] = $_POST['blog-path'];
    $_SESSION['editHomePagePlacement'] = $_POST['blog-home-page-placement'];
    
    // Close database connection
    mysqli_close($conn);
    
    // Redirect back to the write-a-blog page with the error code
    header("Location: ../edit-blog.php?updateblog=".$errorCode);
    exit();
}

/********************* Image upload function ************************/
// Function to handle image uploads
function uploadImage($img, $imgName, $imgType, $imgDbColumn){
    
	require "dbh.php";
	
    $imgUrl = "";
    
    // Allowed file extensions for the images
    $validExt = array("jpg", "png", "jpeg", "bmp", "gif");
    
 
    if($img == ""){
        return "noupdate";
    }
	else{
		
		  // Check if the image size is 0
	 if($_FILES[$imgName]["size"] <= 0){
        formError($imgType."imageerror");
		
    }else{
        // Get the file extension of the image
        $ext = strtolower(end(explode(".", $img)));
        // Check if the extension is valid
        if(!in_array($ext, $validExt)){
            formError("invalidtype".$imgType."image");
        }
		
		// delete old image
		$blogId = $_POST['blog-id'];
		
		$sqlGetOldImage = "SELECT ".$imgDbColumn." FROM blog_post WHERE n_blog_post_id = '$blogId'";
		$queryGetOldImage = mysqli_query($conn, $sqlGetOldImage);
        
		if($rowGetOldImage = mysqli_fetch_assoc($queryGetOldImage)){
			$oldImgURL = $rowGetOldImage[$imgDbColumn];
		}
		
		if(!empty($oldImgURL)){
			$oldImgURLArray = explode("/", $oldImgURL);
			$oldImgName = end($oldImgURLArray);
			$oldImgPath = "../images/blog-images/".$oldImgName;
			unlink($oldImgPath);
			
		}
		
        // Set the folder to save the uploaded image
        $folder = "../images/blog-images/";
        // Generate a unique name for the image
        $imgNewName = rand(10000, 990000).'_'.time().'.'.$ext;
        $imgPath = $folder.$imgNewName;
        
        // Move the uploaded file to the server directory
        if(move_uploaded_file($_FILES[$imgName]['tmp_name'], $imgPath)){
            // If the upload is successful, return the image URL
            $imgUrl = "http://localhost/blog/admin/images/blog-images/".$imgNewName;
        }else{
            // If the upload fails, call formError with error
            formError("erroruploading".$imgType. "image");
			}
		}
		 // Return the image URL
		return $imgUrl;
	}
	
   
}

?>
