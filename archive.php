<?php
get_header('meta');
get_header();
?>
<div class="heading-top">
<?php if ( is_category() ) { ?>当前位置：<a href="<?php bloginfo('siteurl'); ?>/" title="返回首页">首页</a> > <?php echo get_category_parents( get_query_var('cat') , true , ' > ' ); ?>文章<?php } ?>
<?php if ( is_month() ) { ?>当前位置：<a href="<?php bloginfo('siteurl'); ?>/" title="返回首页">首页</a> > <?php the_time('Y, F'); ?><?php } ?>
<?php if (function_exists('is_tag')) { if ( is_tag() ) { ?>当前位置：<a href="<?php bloginfo('siteurl'); ?>/" title="返回首页">首页</a> > 标签 > <?php single_tag_title(); ?><?php } ?>
<?php if ( is_day() ) { ?>当前位置：<a href="<?php bloginfo('siteurl'); ?>/" title="返回首页">首页</a> > <?php the_time('Y, F jS'); ?><?php } ?>
<?php if ( is_year() ) { ?>当前位置：<a href="<?php bloginfo('siteurl'); ?>/" title="返回首页">首页</a> > <?php the_time('Y'); ?><?php } ?>
<?php if ( is_author() ) { ?>当前位置：<a href="<?php bloginfo('siteurl'); ?>/" title="返回首页">首页</a> > 作者文章列表<?php } ?>
<?php if ( isset($_GET['paged']) && !empty($_GET['paged'])) { ?>当前位置：<a href="<?php bloginfo('siteurl'); ?>/" title="返回首页">首页</a> > 存档<?php } ?><?php } ?>
</div>
<!-- Main Container -->
<div id="body-wrapper">
<!-- Content -->
    <div class=" clearfix">

        <!-- Main Content -->
        <div id="main">
        <!-- Simple Post -->
        <?php if(have_posts()) : ?><?php while(have_posts()) : the_post(); ?>

            <div id="post_list" class="clearfix">
                <span><?php comments_popup_link ('0°','+1°','+%°'); ?></span><a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>">
                <h2 class="title"><?php the_title(); ?></h2></a>
                <ul class="post-meta">
                    <li class="author">作者：<a href="javascript:void(0)"> <?php the_author (); ?></a></li>
                    <li class="date"><?php the_time('Y年m月d日') ?></li>
                    <li class="comments"><a href="<?php the_permalink() ?>"><?php if(function_exists('the_views')) { print '被围观 '; the_views();  } ?><?php edit_post_link('编辑', ' | '); ?></a></li>
                </ul>
                <div class="post-entry">
                    <div id="post_listl"><?php include('lib/thumbnail.php'); ?></div>
                    <div id="post_listr"><?php echo mb_strimwidth(strip_tags(apply_filters('the_content', $post->post_content)), 0, 420,"......"); ?></div>
                    <div id="post_list_tags">关键词：<?php the_tags('', ', ', ''); ?></div>
                    <div id="post_list_more" class="red"><a href="<?php the_permalink() ?>" title="详细阅读 <?php the_title(); ?>" rel="bookmark" >详细阅读</a></div>
                </div>
            </div>
            
        <?php endwhile; ?>  
        <!-- /Simple Post -->  
            <!-- Pagination -->
            <ul class="pagination">
                <?php gk_page($query_string);?>
            </ul>
            <!-- /Pagination -->
        <?php endif; ?>
        </div>
        <!-- /Main Content -->
<?php get_sidebar(); ?></div>

<?php get_footer(); ?> 