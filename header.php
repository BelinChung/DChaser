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