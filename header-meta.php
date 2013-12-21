<!DOCTYPE HTML>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta name="viewport" content="width=device-width" />
    <meta name="renderer" content="webkit">
    <?php include('lib/seo.php'); ?>
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
