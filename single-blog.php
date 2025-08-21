<?php
require "admin/includes/dbh.php";

// Initialize variables to avoid undefined variable warnings
$blogPostId = $blogCategoryId = $blogTitle = $blogMetaTitle = $blogContent = $blogMainImgUrl = $blogCreationDate = '';
$categoryTitle = $blogCategoryPath = '';
$blogTagsArr = [];
$numComments = 0;

// Validate and sanitize input
if(isset($_GET['blog'])) {
    $blogPath = $_GET['blog'];
    
    // Get blog post
    $sqlGetBlog = "SELECT * FROM blog_post WHERE v_post_path = ? AND f_post_status = '1'";
    $stmt = mysqli_prepare($conn, $sqlGetBlog);
    mysqli_stmt_bind_param($stmt, "s", $blogPath);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if($rowGetBlog = mysqli_fetch_assoc($result)) {
        $blogPostId = $rowGetBlog['n_blog_post_id'];
        $blogCategoryId = $rowGetBlog['n_category_id'];
        $blogTitle = htmlspecialchars($rowGetBlog['v_post_title']);
        $blogMetaTitle = htmlspecialchars($rowGetBlog['v_post_meta_title']);
        $blogContent = $rowGetBlog['v_post_content']; // Note: This is HTML content
        $blogMainImgUrl = htmlspecialchars($rowGetBlog['v_main_image_url']);
        $blogCreationDate = $rowGetBlog['d_date_created'];
    } else {
        header("Location: index.php");
        exit();
    }
    
    // Get category
    $sqlGetCategory = "SELECT * FROM blog_category WHERE n_category_id = ?";
    $stmt = mysqli_prepare($conn, $sqlGetCategory);
    mysqli_stmt_bind_param($stmt, "i", $blogCategoryId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if($rowGetCategory = mysqli_fetch_assoc($result)) {
        $categoryTitle = htmlspecialchars($rowGetCategory['v_category_title']);
        $blogCategoryPath = htmlspecialchars($rowGetCategory['v_category_path']);
    }
    
    // Get tags
    $sqlGetTags = "SELECT * FROM blog_tag WHERE n_blog_post_id = ?";
    $stmt = mysqli_prepare($conn, $sqlGetTags);
    mysqli_stmt_bind_param($stmt, "i", $blogPostId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if($rowGetTags = mysqli_fetch_assoc($result)) {
        $blogTags = $rowGetTags['v_tag'];
        $blogTagsArr = array_filter(explode(",", $blogTags));
    }
    
    // Get comment count
    $sqlGetAllComments = "SELECT COUNT(*) as count FROM blog_comment WHERE n_blog_post_id = ?";
    $stmt = mysqli_prepare($conn, $sqlGetAllComments);
    mysqli_stmt_bind_param($stmt, "i", $blogPostId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    $numComments = $row['count'];
}
?>

<!DOCTYPE html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <title>Son's Blog | <?php echo htmlspecialchars($blogMetaTitle) ?></title>
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSS -->
    <link rel="stylesheet" href="css/vendor.css">
    <link rel="stylesheet" href="css/styles.css">

    <!-- Scripts -->
    <script src="js/modernizr.js" defer></script>
    <script src="js/fontawesome/all.min.js" defer></script>

    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="180x180" href="apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon-16x16.png">
    <link rel="manifest" href="site.webmanifest">
</head>

<body id="top">
    <!-- Preloader -->
    <div id="preloader"> 
        <div id="loader"></div>
    </div>

    <!-- Header -->
    <?php include "header-opaque.php" ?>

    <!-- Main Content -->
    <section class="s-content">
        <div class="row">
            <div class="column large-12">
                <article class="s-content__entry format-standard">
                    <div class="s-content__media">
                        <div class="s-content__post-thumb">
                            <img src="<?php echo htmlspecialchars($blogMainImgUrl) ?>" 
                                 srcset="<?php echo htmlspecialchars($blogMainImgUrl) ?> 2100w, 
                                         <?php echo htmlspecialchars($blogMainImgUrl) ?> 1050w, 
                                         <?php echo htmlspecialchars($blogMainImgUrl) ?> 525w" 
                                 sizes="(max-width: 2100px) 100vw, 2100px" 
                                 alt="<?php echo htmlspecialchars($blogTitle) ?>">
                        </div>
                    </div>

                    <div class="s-content__entry-header">
                        <h1 class="s-content__title s-content__title--post"><?php echo htmlspecialchars($blogTitle) ?></h1>
                    </div>

                    <div class="s-content__primary">
                        <div class="s-content__entry-content">
                            <?php echo $blogContent // Note: This is trusted HTML content ?>
                        </div>

                        <div class="s-content__entry-meta">
                            <div class="entry-author meta-blk">
                                <div class="author-avatar">
                                    <img class="avatar" src="images/avatars/user-05.JPG" alt="Son Le">
                                </div>
                                <div class="byline">
                                    <span class="bytext">Posted By</span>
                                    <a href="#">Son Le</a>
                                </div>
                            </div>

                            <div class="meta-bottom">
                                <div class="entry-cat-links meta-blk">
                                    <div class="cat-links">
                                        <span>In</span> 
                                        <a href="categories.php?group=<?php echo urlencode($blogCategoryPath) ?>">
                                            <?php echo $categoryTitle ?>
                                        </a>
                                    </div>
                                    <span>On</span>
                                    <?php echo date("M j, Y", strtotime($blogCreationDate)) ?>
                                </div>

                                <div class="entry-tags meta-blk">
                                    <span class="tagtext">Tags</span>
                                    <?php foreach ($blogTagsArr as $tag): ?>
                                        <?php if (!empty(trim($tag))): ?>
                                            <a href="search.php?query=<?php echo urlencode(trim($tag)) ?>">
                                                <?php echo htmlspecialchars(trim($tag)) ?>
                                            </a>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Previous/Next Navigation -->
                        <div class="s-content__pagenav">
                            <?php
                            // Previous blog post
                            $sqlGetPreviousBlog = "SELECT v_post_title, v_post_path 
                                                  FROM blog_post 
                                                  WHERE n_blog_post_id = (
                                                      SELECT MAX(n_blog_post_id) 
                                                      FROM blog_post 
                                                      WHERE n_blog_post_id < ? AND f_post_status = '1'
                                                  )";
                            $stmt = mysqli_prepare($conn, $sqlGetPreviousBlog);
                            mysqli_stmt_bind_param($stmt, "i", $blogPostId);
                            mysqli_stmt_execute($stmt);
                            $result = mysqli_stmt_get_result($stmt);
                            
                            if ($rowGetPreviousBlog = mysqli_fetch_assoc($result)) {
                                echo '<div class="prev-nav">
                                        <a href="single-blog.php?blog=' . urlencode($rowGetPreviousBlog['v_post_path']) . '" rel="prev">
                                            <span>Previous</span>
                                            ' . htmlspecialchars($rowGetPreviousBlog['v_post_title']) . '
                                        </a>
                                      </div>';
                            }
                            
                            // Next blog post
                            $sqlGetNextBlog = "SELECT v_post_title, v_post_path 
                                              FROM blog_post 
                                              WHERE n_blog_post_id = (
                                                  SELECT MIN(n_blog_post_id) 
                                                  FROM blog_post 
                                                  WHERE n_blog_post_id > ? AND f_post_status = '1'
                                              )";
                            $stmt = mysqli_prepare($conn, $sqlGetNextBlog);
                            mysqli_stmt_bind_param($stmt, "i", $blogPostId);
                            mysqli_stmt_execute($stmt);
                            $result = mysqli_stmt_get_result($stmt);
                            
                            if ($rowGetNextBlog = mysqli_fetch_assoc($result)) {
                                echo '<div class="next-nav">
                                        <a href="single-blog.php?blog=' . urlencode($rowGetNextBlog['v_post_path']) . '" rel="next">
                                            <span>Next</span>
                                            ' . htmlspecialchars($rowGetNextBlog['v_post_title']) . '
                                        </a>
                                      </div>';
                            }
                            ?>
                        </div>
                    </div>
                </article>
            </div>
        </div>

        <!-- Comments Section -->
        <div class="comments-wrap">
            <div id="comments" class="row">
                <div class="column large-12">
                    <h3><?php echo $numComments ?> Comments</h3>

                    <ol class="commentlist" id="commentlist">
                        <?php
                        $sqlGetComments = "SELECT * FROM blog_comment 
                                         WHERE n_blog_post_id = ? AND n_blog_comment_parent_id = '0' 
                                         ORDER BY d_date_created ASC";
                        $stmt = mysqli_prepare($conn, $sqlGetComments);
                        mysqli_stmt_bind_param($stmt, "i", $blogPostId);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);
                        
                        while ($rowComments = mysqli_fetch_assoc($result)) {
                            $commentId = $rowComments['n_blog_comment_id'];
                            $commentAuthor = htmlspecialchars($rowComments['v_comment_author']);
                            $comment = htmlspecialchars($rowComments['v_comment']);
                            $commentDate = $rowComments['d_date_created'];
                            
                            // Check for replies
                            $sqlCheckCommentChildren = "SELECT * FROM blog_comment 
                                                      WHERE n_blog_comment_parent_id = ? 
                                                      ORDER BY d_date_created ASC";
													  
                            $stmtChildren = mysqli_prepare($conn, $sqlCheckCommentChildren);
                            mysqli_stmt_bind_param($stmtChildren, "i", $commentId);
                            mysqli_stmt_execute($stmtChildren);
                            $resultChildren = mysqli_stmt_get_result($stmtChildren);
                            $numCommentChildren = mysqli_num_rows($resultChildren);
                            
                            $hasChildren = $numCommentChildren > 0;
                            ?>
                            
                            <li class="<?php echo $hasChildren ? 'thread-alt' : '' ?> depth-1 comment">                         
                                <div class="comment__content">
                                    <div class="comment__info">
                                        <input type="hidden" id="comment-author-<?php echo $commentId ?>" value="<?php echo $commentAuthor ?>" />
                                        <div class="comment__author"><?php echo $commentAuthor ?></div>
                                        <div class="comment__meta">
                                            <div class="comment__time"><?php echo date("M j, Y", strtotime($commentDate)) ?></div>
                                            <div class="comment__reply">
                                                <a class="comment-reply-link" href="#reply-comment-section" onclick="prepareReply('<?php echo $commentId ?>');">Reply</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="comment__text">
                                        <p><?php echo $comment ?></p>
                                    </div>
                                </div>
                                
                                <?php if ($hasChildren): ?>
                                    <ul class="children">
                                        <?php while ($rowCommentChildren = mysqli_fetch_assoc($resultChildren)): ?>
                                            <?php
                                            $commentIdChild = $rowCommentChildren['n_blog_comment_id'];
                                            $commentAuthorChild = htmlspecialchars($rowCommentChildren['v_comment_author']);
                                            $commentChild = htmlspecialchars($rowCommentChildren['v_comment']);
                                            $commentDateChild = $rowCommentChildren['d_date_created'];
                                            ?>
                                            <li class="depth-2 comment">     
                                                <div class="comment__content">
                                                    <div class="comment__info">
                                                        <div class="comment__author"><?php echo $commentAuthorChild ?></div>
                                                        <div class="comment__meta">
                                                            <div class="comment__time"><?php echo date("M j, Y", strtotime($commentDateChild)) ?></div>
                                                        </div>
                                                    </div>
                                                    <div class="comment__text">
                                                        <p><?php echo $commentChild; ?></p>
                                                    </div>
                                                </div>
                                            </li>
                                        <?php endwhile; ?>
                                    </ul>
                                <?php endif; ?>
                            </li>
                        <?php } ?>
                    </ol>
                </div>
            </div>

            <!-- Reply Form (hidden by default) -->
            <div class="row comment-respond" id="reply-comment-section" style="display: none;">
                <div class="column">
                    <h3 id="reply-h3"></h3>
                    <p class="success-message" id="reply-success" style="color:green; display:none;">Your reply was added successfully.</p>      
                    <p class="error-message" id="reply-error" style="display:none;"></p>

                    <form name="replyForm" id="replyForm">
                        <fieldset>
                            <input type="hidden" name="replyBlogPostId" id="replyBlogPostId" value="<?php echo $blogPostId ?>"/>
                            <input type="hidden" name="commentParentId" id="commentParentId" value="" />
                            
                            <div class="form-field">
                                <input name="replycName" id="replycName" class="h-full-width h-remove-bottom" placeholder="Your Name" type="text" maxlength="50" required>
                            </div>
                            <div class="form-field">
                                <input name="replycEmail" id="replycEmail" class="h-full-width h-remove-bottom" placeholder="Your Email" type="email" maxlength="50" required>
                            </div>       
                            <div class="message form-field">
                                <textarea name="replycMessage" id="replycMessage" class="h-full-width" placeholder="Your Message" maxlength="500" required></textarea>
                            </div>
                            <br>
                            <button type="submit" id="submitReplyForm" class="btn btn--primary btn-wide btn--large h-full-width">Reply</button>
                            <button type="button" id="addComment" class="btn btn--primary btn-wide btn--large h-full-width" onclick="prepareComment();">Add Comment</button>
                        </fieldset>
                    </form>
                </div>
            </div>

            <!-- Add Comment Form -->
            <div class="row comment-respond" id="add-comment-section">
                <div class="column">
                    <h3>
                        Add Comment 
                        <span>Your email address will not be published.</span>
                    </h3>
                    
                    <p class="success-message" id="comment-success" style="color:green; display:none;">Your comment was added successfully.</p>      
                    <p class="error-message" id="comment-error" style="display:none;"></p>

                    <form name="commentForm" id="commentForm">
                        <fieldset>
                            <input type="hidden" name="blogPostId" id="blogPostId" value="<?php echo $blogPostId ?>" />
                            <div class="form-field">
                                <input name="cName" id="cName" class="h-full-width h-remove-bottom" placeholder="Your Name" type="text" maxlength="50" required>
                            </div>
                            <div class="form-field">
                                <input name="cEmail" id="cEmail" class="h-full-width h-remove-bottom" placeholder="Your Email" type="email" maxlength="50" required>
                            </div>       
                            <div class="message form-field">
                                <textarea name="cMessage" id="cMessage" class="h-full-width" placeholder="Your Message" maxlength="500" required></textarea>
                            </div>
                            <br>
                            <button type="submit" id="submitCommentForm" class="btn btn--primary btn-wide btn--large h-full-width">Add Comment</button>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include "footer.php"; ?>


    <!-- JavaScript
    ================================================== -->
  
	<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="js/plugins.js"></script>
    <script src="js/main.js"></script>

	<script>
       $(document).ready(function() {
            prepareComment();
        });

        function checkEmail(email) {
            var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            if (!regex.test(email)) {
                return false;
            }
            else {
                return true;
            }
        }

        function prepareReply(commentId) {
            $("#comment-success").css("display", "none");
            $("#comment-error").css("display", "none");
            $("#reply-comment-section").show();
            $("#add-comment-section").hide();
            var authorName = $("#comment-author-" + commentId).val();
            $("#reply-h3").html("Reply to: " + authorName);
            $("#commentParentId").val(commentId);
        }

        function prepareComment() {
            $("#comment-success").css("display", "none");
            $("#comment-error").css("display", "none");
            $("#reply-comment-section").hide();
            $("#add-comment-section").show();
        }

        $(document).on('submit', '#commentForm', function(e) {

            e.preventDefault();

            $("#comment-success").css("display", "none");
            $("#comment-error").css("display", "none");

            var name = $("#cName").val();
            var email = $("#cEmail").val();
            var comment = $("#cMessage").val();

            if (!name || !email || !comment) {
                $("#comment-error").css("display", "block");
                $("#comment-error").html("Please fill all fields.");
            } else if (name.length > 50) {
                $("#comment-error").css("display", "block");
                $("#comment-error").html("The name input field can only be a max of 50 characters.");
            } else if (email.length > 50) {
                $("#comment-error").css("display", "block");
                $("#comment-error").html("The email input field can only be a max of 50 characters.");
            } else if (comment.length > 500) {
                $("#comment-error").css("display", "block");
                $("#comment-error").html("The comment input field can only be a max of 500 characters.");
            } else if (checkEmail(email) == false) {
                $("#comment-error").css("display", "block");
                $("#comment-error").html("Please enter a valid email address.");
            } else {

                var date = new Date();
                var months = ["JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"];
                var dateFormatted = months[date.getMonth()] + " " + date.getDate() + ", " + date.getFullYear();

                $.ajax({
                    method: "POST",
                    url: "/blog/admin/includes/add-comment.php",
                    data: $(this).serialize(),
                    success: function(data) {
                        if (data == "success") {
							var newComment = 
								"<li class='depth-1 comment'>" +
									"<div class='comment__content'>" +
										"<div class='comment__info'>" +
											"<input type='hidden' id='comment-author-temp' value='" + name + "' />" +
											"<div class='comment__author'>" + name + "</div>" +
											"<div class='comment__meta'>" +
												"<div class='comment__time'>" + dateFormatted + "</div>" +
												"<div class='comment__reply'>" +
													"<a class='comment-reply-link' href='#reply-comment-section' onclick=\"prepareReply('temp');\">Reply</a>" +
												"</div>" +
											"</div>" +
										"</div>" +
										"<div class='comment__text'>" +
											"<p>" + comment + "</p>" +
										"</div>" +
									"</div>" +
								"</li>";
                            $("#comment-success").css("display", "block");
                            $("#commentlist").append(newComment);
                            $("#commentForm").hide();
                        }
                        else {
                            $("#comment-error").css("display", "block");
                            $("#comment-error").html("There was an error while adding your comment. Please try again later.");
                        }
                    }
                });
            }
        });
		
		
$(document).on('submit', '#replyForm', function(e) {
    e.preventDefault();

    // Hide error and success messages initially
    $("#reply-success").css("display", "none");
    $("#reply-error").css("display", "none");

    // Get values from the form fields
    var name = $("#replycName").val();
    var email = $("#replycEmail").val();
    var reply = $("#replycMessage").val();
    var parentId = $("#commentParentId").val(); // This should be the ID of the comment you're replying to

    // Validate the form inputs
    if (!name || !email || !reply) {
        $("#reply-error").css("display", "block").html("Please fill all fields.");
    } else if (name.length > 50) {
        $("#reply-error").css("display", "block").html("The name input field can only be a max of 50 characters.");
    } else if (email.length > 50) {
        $("#reply-error").css("display", "block").html("The email input field can only be a max of 50 characters.");
    } else if (reply.length > 500) {
        $("#reply-error").css("display", "block").html("The message input field can only be a max of 500 characters.");
    } else if (!checkEmail(email)) {
        $("#reply-error").css("display", "block").html("Please enter a valid email address.");
    } else if (!parentId) {
        $("#reply-error").css("display", "block").html("There was an unexpected error. Try refreshing the page.");
    } else {
        // Create a formatted date
        var date = new Date();
        var months = ["JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"];
        var dateFormatted = months[date.getMonth()] + " " + date.getDate() + ", " + date.getFullYear();

        // Perform AJAX to submit the reply
        $.ajax({
            method: "POST",
            url: "/blog/admin/includes/add-reply.php",
            data: $(this).serialize(),
            success: function(data) {
                if (data.trim() === "success") {
                    // Assuming the new reply is successfully added, generate the new reply's HTML
                    var newReply = 
                        "<li class='depth-2 reply'>" +
                            "<div class='comment__content'>" +
                                "<div class='comment__info'>" +
                                    "<input type='hidden' class='comment-author-temp' value='" + name + "' />" + 
                                    "<div class='comment__author'>" + name + "</div>" +
                                    "<div class='comment__meta'>" +
                                        "<div class='comment__time'>" + dateFormatted + "</div>" +
                                    "</div>" +
                                "</div>" +
                                "<div class='comment__text'>" +
                                    "<p>" + reply + "</p>" +
                                "</div>" +
                            "</div>" +
                        "</li>";

                    // Append the new reply to the comment's replies section
                    $("#" + parentId + "-replies").append(newReply);

                    // Show success message and reset the form
                    $("#reply-success").css("display", "block");
                    $("#replyForm")[0].reset(); // Reset the reply form
                    $("#replyForm").hide(); // Hide the reply form after submission
                } else {
                    $("#reply-error").css("display", "block").html("There was an error while adding your reply. Please try again later.");
                }
            }
        });
    }
});


</script>
</body>
</html>
