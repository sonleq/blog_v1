<?php


// Include database connection
require "dbh.php";
// Start a new session or resume the existing session
session_start();

// Check if the submit-blog button was clicked
if(isset($_POST['submit-blog'])){
    
    // Capture all form data
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
    /*if(empty($title)){
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
    }*/
	
	 // Define an array of required fields
    $requiredFields = [
        "emptytitle" => $title,
        "emptycategory" => $blogCategoryId,
        "emptysummary" => $blogSummary,
        "emptycontent" => $blogContent,
        "emptytags" => $blogTags,
        "emptypath" => $blogPath
    ];

    // Loop through the required fields and check if any are empty
    foreach ($requiredFields as $errorCode => $value) {
        if (empty($value)) {
            formError($errorCode);  // Call the formError function with the error code
            return;  // Exit after the first error
        }
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
    $sqlCheckBlogTitle = "SELECT v_post_title FROM blog_post WHERE v_post_title = '$title' AND f_post_status !='2'";
    $queryCheckBlogTitle = mysqli_query($conn, $sqlCheckBlogTitle);
    
    // Check if the blog path already exists in the database (excluding deleted posts)
    $sqlCheckBlogPath = "SELECT v_post_path FROM blog_post WHERE v_post_path = '$blogPath' AND f_post_status !='2'";
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
    $mainImgUrl = uploadImage($_FILES["main-blog-image"]["name"], "main-blog-image", "main");
    $altImgUrl  = uploadImage($_FILES["alt-blog-image"]["name"], "alt-blog-image", "alt");
    
    // SQL query to insert the new blog post into the database
    $sqlAddBlog = "
                    INSERT INTO blog_post (
                        n_category_id, 
                        v_post_title, 
                        v_post_meta_title, 
                        v_post_path, 
                        v_post_summary, 
                        v_post_content,
                        v_main_image_url,
                        v_alt_image_url,
                        n_home_page_placement, 
                        f_post_status, 
                        d_date_created, 
                        d_time_created
                    ) 
                    VALUES (
                        '$blogCategoryId', 
                        '$title', 
                        '$metaTitle', 
                        '$blogPath', 
                        '$blogSummary', 
                        '$blogContent', 
                        '$mainImgUrl',
                        '$altImgUrl',
                        '$homePagePlacement',
                        '1', 
                        '$date', 
                        '$time'
                    )";

    // Check if the query was successful
    if(mysqli_query($conn, $sqlAddBlog)){
        
		$blogPostId = mysqli_insert_id($conn);
		$sqlAddTags = "INSERT INTO blog_tag(n_blog_post_id, v_tag) VALUES ('$blogPostId', '$blogTags')";
		
		if(mysqli_query($conn, $sqlAddTags)){
			
			// Close the database connection
			mysqli_close($conn);
			
			// Clear session variables
			unset($_SESSION['blogTitle']);
			unset($_SESSION['blogMetaTitle']);
			unset($_SESSION['blogCategoryId']);
			unset($_SESSION['blogSummary']);
			unset($_SESSION['blogContent']);
			unset($_SESSION['blogTags']);
			unset($_SESSION['blogPath']);
			unset($_SESSION['blogHomePagePlacement']);
			
			// Redirect to blogs page with success message
			header("Location: ../blogs.php?addblog=success");
			exit();
		}
		
    else{
        // If the query failed, call formError with SQL error
        formError("sqlerror");
    }
}else{
    // If the form wasn't submitted, redirect to the index page
    header("Location: ../index.php");
    exit();
	}

}

// Function to handle form errors and redirect with error codes
function formError($errorCode){
    require "dbh.php";
    
    // Store form data in session variables for reuse after the error
    $_SESSION['blogTitle'] = $_POST['blog-title'];
    $_SESSION['blogMetaTitle'] = $_POST['blog-meta-title'];
    $_SESSION['blogCategoryId'] = $_POST['blog-category'];
    $_SESSION['blogSummary'] = $_POST['blog-summary'];
    $_SESSION['blogContent'] = $_POST['blog-content'];
    $_SESSION['blogTags'] = $_POST['blog-tags'];
    $_SESSION['blogPath'] = $_POST['blog-path'];
    $_SESSION['blogHomePagePlacement'] = $_POST['blog-home-page-placement'];
    
    // Close database connection
    mysqli_close($conn);
    
    // Redirect back to the write-a-blog page with the error code
    header("Location: ../write-a-blog.php?addblog=".$errorCode);
    exit();
}

/********************* Image upload function ************************/
// Function to handle image uploads
function uploadImage($img, $imgName, $imgType){
    
    $imgUrl = "";
    
    // Allowed file extensions for the images
    $validExt = array("jpg", "png", "jpeg", "bmp", "gif");
    
    // Check if the image is empty
    if($img == ""){
        formError("empty".$imgType."image");
    // Check if the image size is 0
    }else if($_FILES[$imgName]["size"] <= 0){
        formError($imgType."imageerror");
    }else{
        // Get the file extension of the image
        $ext = strtolower(end(explode(".", $img)));
        // Check if the extension is valid
        if(!in_array($ext, $validExt)){
            formError("invalidtype".$imgType."image");
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

?>
