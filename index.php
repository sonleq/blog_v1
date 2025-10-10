<?php
 
		require "admin/includes/dbh.php";

?>

<!DOCTYPE html>
<html class="no-js" lang="en">
<head>

    <!--- basic page needs
    ================================================== -->
    <meta charset="utf-8">
    <title>The Living Free – A Simpler, Intentional Life</title>
	<meta name="description" content="Slow living, wellness, and creative reflections by Son Le. Join the journey toward a more mindful and intentional life.">

    <meta name="author" content="Son Le">
	
		<!-- Open Graph -->
	<meta property="og:title" content="The Living Free – A Simpler, Intentional Life">
	<meta property="og:description" content="Slow living, wellness, and creative reflections by Son Le.">
	<meta property="og:image" content="https://yourdomain.com/images/sample-2100.jpg">
	<meta property="og:url" content="https://yourdomain.com/">
	<meta property="og:type" content="website">

	<!-- Twitter Card -->
	<meta name="twitter:card" content="summary_large_image">
	<meta name="twitter:title" content="The Living Free – A Simpler, Intentional Life">
	<meta name="twitter:description" content="Slow living, wellness, and creative reflections by Son Le.">
	<meta name="twitter:image" content="https://yourdomain.com/images/sample-2100.jpg">


    <!-- mobile specific metas
    ================================================== -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSS
    ================================================== -->
	
	<link href="https://fonts.googleapis.com/css2?family=DM+Serif+Text&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/vendor.css">
    <link rel="stylesheet" href="css/styles.css">
	<link rel="canonical" href="https://yourdomain.com/" />


    <!-- script
    ================================================== -->
    <script src="js/modernizr.js"></script>
	
    <!-- favicons
    ================================================== -->
    <link rel="apple-touch-icon" sizes="180x180" href="apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon-16x16.png">
    <link rel="manifest" href="site.webmanifest">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


</head>

<script>
  window.addEventListener('scroll', function() {
    const header = document.querySelector('.s-header');
    if (window.scrollY > 50) {
      header.classList.add('scrolled');
    } else {
      header.classList.remove('scrolled');
    }
  });
</script>

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
    // Loop through placements 1 to 3 and output slide HTML dynamically
    for ($placement = 1; $placement <= 3; $placement++) {
        $sql = "SELECT * 
                FROM blog_post 
                INNER JOIN blog_category ON blog_post.n_category_id = blog_category.n_category_id 
                WHERE n_home_page_placement = $placement AND f_post_status != '2' 
                LIMIT 1";

        $result = mysqli_query($conn, $sql);
        if ($blog = mysqli_fetch_assoc($result)) {
            $category = htmlspecialchars($blog['v_category_title']);
            $categoryPath = htmlspecialchars($blog['v_category_path']);
            $title = htmlspecialchars($blog['v_post_title']);
            $path = htmlspecialchars($blog['v_post_path']);
            $imageUrl = htmlspecialchars($blog['v_main_image_url']);
    ?>
            <div class="s-hero__slide">
                <div class="s-hero__slide-bg" style="background-image: url('<?php echo $imageUrl; ?>');"></div>
                <div class="row s-hero__slide-content animate-this">
                    <div class="column">
                        <div class="s-hero__slide-meta">
                            <span class="cat-links">
                                <a href="categories.php?group=<?php echo $categoryPath; ?>"><?php echo $category; ?></a>
                            </span>
                            <span class="byline"> 
                                Posted by 
                                <span class="author">
                                    <a href="#">Son Le</a>
                                </span>
                            </span>
                        </div>
                        <h1 class="s-hero__slide-text">
                            <a href="single-blog.php?blog=<?php echo $path; ?>">
                                <?php echo $title; ?>
                            </a>
                        </h1>
                    </div>
                </div>
            </div>
    <?php
        }
    }
    ?>
	</div>
	
		<div class="s-hero__social hide-on-mobile-small">
            <p>Follow</p>
            <span></span>
            <ul class="s-hero__social-icons">
                <li><a href="#0"><i class="fa-brands fa-facebook" aria-hidden="true"></i></a></li>
                <li><a href="#0"><i class="fa-brands fa-x-twitter" aria-hidden="true"></i></a></li>
                <li><a href="#0"><i class="fab fa-instagram" aria-hidden="true"></i></a></li>
                <li><a href="#0"><i class="fa-brands fa-youtube"></i></a></li>
            </ul>
        </div> <!-- end s-hero__social  https://fontawesome.com/search?q=facebook&o=r -->

        <div class="nav-arrows s-hero__nav-arrows">
            <button class="s-hero__arrow-prev">
                <svg viewBox="0 0 15 15" xmlns="http://www.w3.org/2000/svg" width="15" height="15"><path d="M1.5 7.5l4-4m-4 4l4 4m-4-4H14" stroke="currentColor"></path></svg>
            </button>
            <button class="s-hero__arrow-next">
               <svg viewBox="0 0 15 15" xmlns="http://www.w3.org/2000/svg" width="15" height="15"><path d="M13.5 7.5l-4-4m4 4l-4 4m4-4H1" stroke="currentColor"></path></svg>
            </button>
    </div>
</section>


    <!-- content
    ================================================== -->
    <section class="s-content s-content--no-top-padding">
  <!-- walkthrough/introduction section -->
  <section id="about-overview" class="s-about-overview">
    <div class="row">
      <div class="column large-10 tab-12 s-about-overview__content">

        <h1>A Simpler, More Intentional Life</h1>


        <p class="lead">
          Welcome to <strong>The Living Free</strong> — my corner of the internet where I share the journey toward living with less noise, more intention, and a deeper sense of care — for myself, others, and the world around us.
        </p>

        <p>
          Here, you’ll find honest reflections on <strong>wellness and creativity</strong>, practical <strong>tools to build mindful habits</strong>, and stories that invite you to slow down and live well. Whether you’re simplifying your space, your routines, or your mindset, this space is here to walk alongside you — never ahead of you.
        </p>

        <p>
          I don’t claim to have it all figured out. This site is a living, breathing reflection of the process. Thanks for being here.
        </p>

        <!-- Category Links with Icons and Descriptions -->
        <div class="category-links" style="display: flex; gap: 1rem; margin-top: 2rem; flex-wrap: wrap;">
          <a href="categories.php?group=wellness" class="category-item" title="Wellness" style="
            flex: 1 1 180px;
            background-color: #f0f4f8;
            border-radius: 8px;
            padding: 1.75rem 2.5rem;
            text-decoration: none;
            color: #2c3e50;
            font-weight: 700;
            transition: background-color 0.3s ease, color 0.3s ease;
            display: flex;
            flex-direction: column;
            gap: 1rem;
          ">
            <div style="display: flex; align-items: center; gap: 1.25rem;">
              <i class="fas fa-spa" style="color: #4CAF50; font-size: 1.8rem;"></i>
              <span style="font-size: 1.5rem;">Wellness</span>
            </div>
            <p style="margin: 0; font-weight: 400; font-size: 1.25rem; line-height: 1.5; color: #555;">
              Mindful self-care, nutrition, and holistic health practices to nurture your body and mind.
            </p>
          </a>

          <a href="categories.php?group=reflections" class="category-item" title="Reflections & Creativity" style="
            flex: 1 1 180px;
            background-color: #f0f4f8;
            border-radius: 8px;
            padding: 1.75rem 2.5rem;
            text-decoration: none;
            color: #2c3e50;
            font-weight: 700;
            transition: background-color 0.3s ease, color 0.3s ease;
            display: flex;
            flex-direction: column;
            gap: 1rem;
          ">
            <div style="display: flex; align-items: center; gap: 1.25rem;">
              <i class="fas fa-feather-alt" style="color: #4CAF50; font-size: 1.8rem;"></i>
              <span style="font-size: 1.5rem;">Reflections & Creativity</span>
            </div>
            <p style="margin: 0; font-weight: 400; font-size: 1.25rem; line-height: 1.5; color: #555;">
              Thoughtful essays, personal reflections, and creative explorations to inspire your inner life.
            </p>
          </a>

          <a href="categories.php?group=tools" class="category-item" title="Tools & Resources" style="
            flex: 1 1 180px;
            background-color: #f0f4f8;
            border-radius: 8px;
            padding: 1.75rem 2.5rem;
            text-decoration: none;
            color: #2c3e50;
            font-weight: 700;
            transition: background-color 0.3s ease, color 0.3s ease;
            display: flex;
            flex-direction: column;
            gap: 1rem;
          ">
            <div style="display: flex; align-items: center; gap: 1.25rem;">
              <i class="fas fa-tools" style="color: #4CAF50; font-size: 1.8rem;"></i>
              <span style="font-size: 1.5rem;">Tools & Resources</span>
            </div>
            <p style="margin: 0; font-weight: 400; font-size: 1.25rem; line-height: 1.5; color: #555;">
              Practical guides, apps, and resources to support your intentional living journey.
            </p>
          </a>
        </div>

        <hr style="margin: 2.5rem auto; width: 60px; border: 1px solid #ddd;">
<div style="display: flex; align-items: center; gap: 2rem;">
  <img src="images/sample-2100.jpg" alt="Son Le" style="width: 150px; border-radius: 50%;">
  <div>
        <h3>Meet the Writer</h3>
        <p>
          I’m Son Le, a writer and slow-living enthusiast based in Charlotte, NC. I created The Living Free as a place to share, learn, and grow — not as an expert, but as a fellow traveler on this path to living more simply, honestly, and kindly.
        </p>
   

        <a class="btn" href="about.php">Curious to learn more?</a>

      </div>
    </div>
  </section>

 
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
