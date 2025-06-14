<?php
 
		require "admin/includes/dbh.php";

?>

<!DOCTYPE html>
<html class="no-js" lang="en">
<head>

    <!--- basic page needs
    ================================================== -->
    <meta charset="utf-8">
    <title> Son's Blog
	</title>
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- mobile specific metas
    ================================================== -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSS
    ================================================== -->
    <link rel="stylesheet" href="css/vendor.css">
    <link rel="stylesheet" href="css/styles.css">

    <!-- script
    ================================================== -->
    <script src="js/modernizr.js"></script>
	
    <!-- favicons
    ================================================== -->
    <link rel="apple-touch-icon" sizes="180x180" href="apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon-16x16.png">
    <link rel="manifest" href="site.webmanifest">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


</head>

<body id="top">


    <!-- preloader
    ================================================== -->
    <div id="preloader"> 
    	<div id="loader"></div>
    </div>


    <!-- header
    ================================================== -->
	
	<?php include "header.php"; ?>
	
    <!-- end s-header -->

 
    <!-- hero
    ================================================== -->
    <section id="hero" class="s-hero">

        <div class="s-hero__slider">
		
		<?php
		
			$sqlGetFirstBlog = "SELECT * 
								FROM blog_post 
								INNER JOIN blog_category
									ON blog_post.n_category_id = blog_category.n_category_id 
								WHERE n_home_page_placement = '1' AND f_post_status != '2'
								LIMIT 1";

			$queryGetFirstBlog = mysqli_query($conn, $sqlGetFirstBlog);
			
			if($rowGetFirstBlog = mysqli_fetch_assoc($queryGetFirstBlog)){
				
				$firstBlogCategory = $rowGetFirstBlog['v_category_title'];
				$firstBlogCategoryPath = $rowGetFirstBlog['v_category_path'];
				$firstBlogTitle = $rowGetFirstBlog['v_post_title'];
				$firstBlogPath = $rowGetFirstBlog['v_post_path'];
				$firstBlogMainImageUrl = $rowGetFirstBlog['v_main_image_url'];
				
		?>
		
		<div class="s-hero__slide">	
               <div class="s-hero__slide-bg" style="background-image: url('<?php echo $firstBlogMainImageUrl; ?>');"> </div>
                <div class="row s-hero__slide-content animate-this">
                    <div class="column">
                        <div class="s-hero__slide-meta">
                            <span class="cat-links">
                                <a href="categories.php?group=<?php echo $firstBlogCategoryPath; ?>"><?php echo $firstBlogCategory; ?></a>
                            </span>
                            <span class="byline"> 
                                Posted by 
                                <span class="author">
                                    <a href="#">Son Le</a>
                                </span>
                            </span>
                        </div>
                        <h1 class="s-hero__slide-text">
                            <a href="single-blog.php?blog=<?php echo $firstBlogPath; ?>">
                                <?php echo $firstBlogTitle; ?>
                            </a>
                        </h1>
                    </div>
                </div>
            </div>

		<?php
			}
		
			$sqlGetsecondBlog = "SELECT * 
								FROM blog_post 
								INNER JOIN blog_category
									ON blog_post.n_category_id = blog_category.n_category_id 
								WHERE n_home_page_placement = '2' AND f_post_status != '2'
								LIMIT 1";

			$queryGetsecondBlog = mysqli_query($conn, $sqlGetsecondBlog);
			
			if($rowGetsecondBlog = mysqli_fetch_assoc($queryGetsecondBlog)){
				
				$secondBlogCategory = $rowGetsecondBlog['v_category_title'];
				$secondBlogCategoryPath = $rowGetsecondBlog['v_category_path'];
				$secondBlogTitle = $rowGetsecondBlog['v_post_title'];
				$secondBlogPath = $rowGetsecondBlog['v_post_path'];
				$secondBlogMainImageUrl = $rowGetsecondBlog['v_main_image_url'];
		
		?>

            <div class="s-hero__slide">
                <div class="s-hero__slide-bg" style="background-image: url('<?php echo $secondBlogMainImageUrl; ?>');"></div>
                <div class="row s-hero__slide-content animate-this">
                    <div class="column">
                        <div class="s-hero__slide-meta">
                            <span class="cat-links">
                                <a href="categories.php?group=<?php echo $secondBlogCategoryPath; ?>"><?php echo $secondBlogCategory; ?></a>
                            </span>
                            <span class="byline"> 
                                Posted by 
                                <span class="author">
                                    <a href="#">Son Le</a>
                                </span>
                            </span>
                        </div>
                        <h1 class="s-hero__slide-text">
                            <a href="single-blog.php?blog=<?php echo $secondBlogPath; ?>">
                                <?php echo $secondBlogTitle; ?>
                            </a>
                        </h1>
                    </div>
                </div>

            </div>
		
		<?php 
			}
		
			$sqlGetthirdBlog = "SELECT * 
								FROM blog_post 
								INNER JOIN blog_category
									ON blog_post.n_category_id = blog_category.n_category_id 
								WHERE n_home_page_placement = '3' AND f_post_status != '2'
								LIMIT 1";

			$queryGetthirdBlog = mysqli_query($conn, $sqlGetthirdBlog);
			
			if($rowGetthirdBlog = mysqli_fetch_assoc($queryGetthirdBlog)){
				
				$thirdBlogCategory = $rowGetthirdBlog['v_category_title'];
				$thirdBlogCategoryPath = $rowGetthirdBlog['v_category_path'];
				$thirdBlogTitle = $rowGetthirdBlog['v_post_title'];
				$thirdBlogPath = $rowGetthirdBlog['v_post_path'];
				$thirdBlogMainImageUrl = $rowGetthirdBlog['v_main_image_url'];		
		?>

            <div class="s-hero__slide"">
                <div class="s-hero__slide-bg" style="background-image: url('<?php echo $thirdBlogMainImageUrl; ?>');"></div>

                <div class="row s-hero__slide-content animate-this">
                    <div class="column">
                        <div class="s-hero__slide-meta">
                            <span class="cat-links">
								 <a href="categories.php?group=<?php echo $thirdBlogCategoryPath; ?>"><?php echo $thirdBlogCategory; ?></a>
                            </span>
                            <span class="byline"> 
                                Posted by 
                                <span class="author">
                                    <a href="#0">Son Le</a>
                                </span>
                            </span>
                        </div>
                        <h1 class="s-hero__slide-text">
                           <a href="single-blog.php?blog=<?php echo $thirdBlogPath; ?>">
                                <?php echo $thirdBlogTitle; ?>
                            </a>
                        </h1>
                    </div>
                </div>

            </div> <!-- end s-hero__slide -->
		</div> <!-- end s-hero__slide -->
		<?php
		}
		
		?>
  

        <div class="s-hero__social hide-on-mobile-small">
            <p>Follow</p>
            <span></span>
            <ul class="s-hero__social-icons">
                <li><a href="#0"><i class="fab fa-facebook-f" aria-hidden="true"></i></a></li>
                <li><a href="#0"><i class="fab fa-twitter" aria-hidden="true"></i></a></li>
                <li><a href="#0"><i class="fab fa-instagram" aria-hidden="true"></i></a></li>
                <li><a href="#0"><i class="fa-brands fa-youtube"></i></a></li>
            </ul>
        </div> <!-- end s-hero__social -->

        <div class="nav-arrows s-hero__nav-arrows">
            <button class="s-hero__arrow-prev">
                <svg viewBox="0 0 15 15" xmlns="http://www.w3.org/2000/svg" width="15" height="15"><path d="M1.5 7.5l4-4m-4 4l4 4m-4-4H14" stroke="currentColor"></path></svg>
            </button>
            <button class="s-hero__arrow-next">
               <svg viewBox="0 0 15 15" xmlns="http://www.w3.org/2000/svg" width="15" height="15"><path d="M13.5 7.5l-4-4m4 4l-4 4m4-4H1" stroke="currentColor"></path></svg>
            </button>
        </div> <!-- end s-hero__arrows -->

    </section> <!-- end s-hero -->


    <!-- content
    ================================================== -->
    <section class="s-content s-content--no-top-padding">


        <!-- masonry
        ================================================== -->
        <div class="s-bricks">

            <div class="masonry">
                <div class="bricks-wrapper h-group">

                    <div class="grid-sizer"></div>

                    <div class="lines">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
					
					



                </div> <!-- end brick-wrapper -->

            </div> <!-- end masonry -->
<!--
            <div class="row">
                <div class="column large-12">
                    <nav class="pgn">
                        <ul>
                            <li>
                                <span class="pgn__prev" href="#0">
                                    Prev
                                </span>
                            </li>
                            <li><a class="pgn__num" href="#0">1</a></li>
                            <li><span class="pgn__num current">2</span></li>
                            <li><a class="pgn__num" href="#0">3</a></li>
                            <li><a class="pgn__num" href="#0">4</a></li>
                            <li><a class="pgn__num" href="#0">5</a></li>
                            <li><span class="pgn__num dots">…</span></li>
                            <li><a class="pgn__num" href="#0">8</a></li>
                            <li>
                                <span class="pgn__next" href="#0">
                                    Next
                                </span>
                            </li>
                        </ul>
                    </nav> <!-- end pgn -->
                </div> <!-- end column -->
            </div> <!-- end row --> 

        </div> <!-- end s-bricks -->

    </section> <!-- end s-content -->


    <!-- footer
    ================================================== -->
	
	<?php include "footer.php"; ?>


    <!-- end s-footer -->


    <!-- Java Script
    ================================================== -->
    <script src="js/jquery-3.5.0.min.js"></script>
    <script src="js/plugins.js"></script>
    <script src="js/main.js"></script>

</body>

</html>
