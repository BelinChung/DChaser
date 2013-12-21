<!DOCTYPE HTML>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta name="viewport" content="width=device-width" />
    <title>404: Page Not Found</title>
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
    <?php
    //加载css
    gk_load_css();
    wp_head();
    //加载js
    gk_load_js();
    if ( is_singular() && get_option( 'thread_comments' ) ) wp_enqueue_script( 'comment-reply' );
    ?>
    <script type="text/javascript">
	$(window).load(function() {
		$('.flexslider').flexslider();

	});
    </script>
</head>
<body >
    <!-- Header -->
    <div id="header" class="container clearfix">

        <a href="<?php bloginfo('url'); ?>" id="logo"><img src="<?php bloginfo('template_url'); ?>/static/img/logo.png" alt="" /></a>

        <!-- Navigation -->

             <?php
             wp_nav_menu( array(
                'theme_location'  =>'primary' ,
                'container' => 'nav',
                'container_id' => 'main-nav-menu',
                'items_wrap' => '<ul class=sf-menu >%3$s</ul>',
                //'menu_class'  =>  'sf-menu' ,
                'fallback_cb'  => '',
                'depth'       =>  3,
                'walker'      =>  new GK_Nav_Walker
             ) );
             ?>

        <!-- /Navigation -->

    <select id="responsive-main-nav-menu" onchange="javascript:window.location.replace(this.value);">
    </select>
    </div>
    <!-- /Header -->

<!-- Main Container -->
<div id="body-wrapper" class="body-wraper-path">
 <!-- Content -->
    <div id="content" class="container error-404 clearfix">
        <img src="<?php bloginfo('template_directory'); ?>/static/img/404.jpg" />
        <h3>无法找到该网页</h3>
        <p>最可能的原因是：</p>
        <p>在地址中可能存在键入错误</p>
        <p>当您点击某个链接时，它可能已过期</p> 
        <p>您可以尝试以下操作：</p> 
        <p>重新键入地址</p>  
        <p><a href="<?php bloginfo('siteurl'); ?>/">点此返回主页</a></p>
    </div>

 
<?php get_footer(); ?> 