<?php

get_header('meta');
get_header();
?>
<!-- 首页小横栏
<div class="heading-top">
<img src="<?php bloginfo('template_url'); ?>/static/img/gonggao.gif" style="vertical-align: middle;margin-bottom: 3px;border:0"/>
</div> -->
<!-- Main Container -->
<div id="body-wrapper" class="body-wraper-path">
 <!-- Content -->
    <div id="content" class="container clearfix">

        <!-- Slider -->
        <div id="main-slider" class="flexslider">
            <ul class="slides">
            <?=get_banner_slide(); ?>
            </ul>

        </div>
        <div class="has-line" style="margin-top: 45px;"></div>
        <!-- /Slider -->

        <!-- main-services -->
        <div class="main-services">
        <ul>
          <?=get_main_services_post(); ?>
        </ul>
        <div class="has-line"></div>
      </div>
        <!-- Project Carousel -->
        <div id="project-wrapper" class="clearfix">

            <div class="section-title one-fourth">
                <h4>最新图文</h4>
                <p>这里汇集了我们网站最新的图文页面，你可以点击查阅详细！</p>
                <p><a href="javascript:void(0)">查看更多</a></p>
                <div class="carousel-nav">
                    <a id="project-prev" class="jcarousel-prev" href="javascript:void(0)" title="上一页"></a>
                    <a id="project-next" class="jcarousel-next" href="javascript:void(0)" title="下一页"></a>
                </div>
            </div>

            <ul class="project-carousel">
                <?php
                global $query_string;
                query_posts($query_string.'&showposts=100&caller_get_posts=1');
                $i = 0;
                if (have_posts()){
                    while (have_posts()){
                        the_post();
                        $content = $post->post_content;
                        $searchimages = '~<img [^>]* />~';
                        preg_match_all( $searchimages, $content, $pics );
                        $iNumberOfPics = count($pics[0]);
                        if ( $iNumberOfPics > 0 &&$i <6) {
                          echo "<li><a href='";
                          echo the_permalink();
                          echo "' title='' class='project-item'><img src='";
                          echo catch_that_image();
                          echo "' alt='";
                          echo the_title();
                          echo "'/><div class='overlay'><h5>";
                          echo strip_tags(the_excerpt());
                          echo "</h5><p>";
                          echo the_title();
                          echo "</p></div> </a></li>\n";
                          $i ++;
                        }
                    }
                }
                ?>
            </ul>
            <div class="has-line" style="margin-top: 50px;"></div>
        </div>
        <!-- /Project Carousel -->

        <!-- Blog Carousel -->
        <div id="blog-wrapper" class="clearfix">

            <div class="section-title one-fourth">
                <h4>热评文章</h4>
                <p>最受欢迎的文章当然有它的出彩之处，点进去看看吧！</p>
                <p><a href="javascript:void(0)">查看更多</a></p>
                <div class="carousel-nav">
                    <a id="blog-prev" class="jcarousel-prev" href="javascript:void(0)" title="上一页"></a>
                    <a id="blog-next" class="jcarousel-next" href="javascript:void(0)" title="下一页"></a>
                </div>
            </div>

            <ul class="blog-carousel">
            <?=simple_get_most_viewed(); ?>
            </ul>
        </div>
        <!-- /Blog Carousel -->

    </div>
    <!-- /Content -->
<?php
get_footer();
?>