<?php 

$basename = basename($_SERVER['PHP_SELF']);

?>
<nav class="navbar-default navbar-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav" id="main-menu">
            <li>
                <a class="<?php echo ($basename == 'index.php') ? 'active-menu' : ''; ?>" href="index.php"><i class="fa-solid fa-gauge"></i> Dashboard</a>
            </li>
            <li>
                <a class="<?php echo ($basename == 'blog-category.php') ? 'active-menu' : ''; ?>" href="blog-category.php">
				<i class="fa-solid fa-list"></i> Blog Categories</a>
            </li>
            <li>
                <a class="<?php echo ($basename == 'blogs.php') ? 'active-menu' : ''; ?>" href="blogs.php"><i class="fa fa-bar-chart-o"></i> Blogs</a>
            </li>
            <li>
                <a class="<?php echo ($basename == 'write-a-blog.php') ? 'active-menu' : ''; ?>" href="write-a-blog.php"><i class="fa-solid fa-pen"></i> Write A Blog</a>
            </li>
			<li>
                <a class="<?php echo ($basename == 'logout.php') ? 'active-menu' : ''; ?>" href="logout.php"><i class="fa fa-bar-chart-o"></i> Logout</a>
            </li>
        </ul>
    </div>
</nav>
