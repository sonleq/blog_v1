<?php

		require "admin/includes/dbh.php";

?>

<!DOCTYPE html>
<html class="no-js" lang="en">
<head>

    <!--- basic page needs
    ================================================== -->
    <meta charset="utf-8">
    <title>The Living Free - About</title>
    <meta name="description" content="Learn more about Son Le — a writer and slow-living enthusiast exploring intentional living, wellness, and creative routines through The Living Free blog."">
    <meta name="author" content="Son Le">

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
    <script defer src="js/fontawesome/all.min.js"></script>

    <!-- favicons
    ================================================== -->
    <link rel="apple-touch-icon" sizes="180x180" href="apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon-16x16.png">
    <link rel="manifest" href="site.webmanifest">

</head>

<body id="top">


    <!-- preloader
    ================================================== -->
    <div id="preloader"> 
    	<div id="loader"></div>
    </div>


    <!-- header
    ================================================== -->
	
	<?php include "header-opaque.php"; ?>
	
    <!-- end s-header -->


    <!-- content
    ================================================== -->
    <section class="s-content">

        <div class="row">
            <div class="column large-12">

                <article class="s-content__entry">

                    <div class="s-content__media">
                        <img src="images/thumbs/about/about-1050.jpg" 
                                srcset="images/thumbs/about/about-2100.jpg 2100w, 
                                        images/thumbs/about/about-1050.jpg 1050w, 
                                        images/thumbs/about/about-525.jpg 525w" sizes="(max-width: 2100px) 100vw, 2100px" alt="">

                    </div> <!-- end s-content__media -->

                    <div class="s-content__entry-header">
                        <h1 class="s-content__title">Learn More About My Story.</h1>
                    </div> <!-- end s-content__entry-header -->

                    <div class="s-content__primary">

                        <div class="s-content__page-content">

                            <p class="lead">
                            I’m Son Le — a writer, designer, and slow-living enthusiast based in Charlotte, NC. I created <strong>The Living Free</strong> as a personal journal 
							of sorts — a place to explore what it means to live with less noise and more care. What started as a quiet experiment in intentional living has 
							grown into a space for thoughtful reflection, creative practice, and mindful routines.
                            </p>
                            
                            <p class="lead">
                            This blog is a living process. I’m not an expert — just someone trying to be more awake to the world, and to myself.
							I started this blog because I was overwhelmed by noise, clutter, and the pressure to always be doing more. I wanted to slow down, live more intentionally, and create a life that feels meaningful — not just productive.
							If you’re craving a slower, more intentional way to be — I hope you’ll find something here that speaks to you.
                            </p> 
 
                            <br>

                            <div class="row block-large-1-2 block-tab-full s-content__blocks">
                                <div class="column">
                                    <h4>Who.</h4>
                                    <p>
                                   I’m a curious learner with a deep love for words, aesthetics, and quiet transformation. Offline, I enjoy tea, sketchbooks,
								   long walks, and clean, functional design. My approach is rooted in mindfulness, simplicity, and a desire to live aligned with my values.
                                    </p>
                                </div>

                                <div class="column">
                                    <h4>When.</h4>
                                    <p>
                                    I began exploring intentional living around 2020, during a time of burnout and uncertainty. 
									Over time, it evolved into this blog — a way to document the path, share insights, and connect with others on similar journeys.
                                    </p>
                                </div>

                                <div class="column">
                                    <h4>What.</h4>
                                    <p>
                                    The Living Free offers essays, tools, and reflections on slow living, wellness, creativity, and ethical habits. 
									I write about things that help me come back to center — whether that’s morning routines, mental clarity, minimalism, or choosing kindness.
                                    </p>
                                </div>

                                <div class="column">
                                    <h4>How.</h4>
                                    <p>
                                    I try to keep things simple. I write honestly, publish when it feels right, and stay open to change.
									If something here resonates with you, feel free to explore, subscribe, or reach out — I’d love to hear your story too.
                                    </p>
                                </div>

                            </div>
                        </div> <!-- end s-entry__page-content -->

                    </div> <!-- end s-content__primary -->
                </article> <!-- end entry -->

            </div> <!-- end column -->
        </div> <!-- end row -->

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
