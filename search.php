<?php
get_header('meta');
get_header();
?>
<div class="heading-top">
    当前位置：<a href="<?php bloginfo('siteurl'); ?>/" title="返回首页">首页</a> > “<?php echo $s; ?>”的搜索结果
</div>
<!-- Main Container -->
<div id="body-wrapper">
<!-- Content -->
    <div class="clearfix">

        <!-- Main Content -->
        <div id="main">
        <!-- Simple Post -->
        <div id="search"><?php $allsearch = &new WP_Query("s=$s&showposts=-1"); $key = wp_specialchars($s, 1); $count = $allsearch->post_count; _e(''); _e('共找到含有"'); echo $key; _e('"的文章'); _e(''); echo $count . ' '; _e('篇'); wp_reset_query(); ?></div>
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
                    <div id="post_list_more" class="red"><a href="<?php the_permalink() ?>" title="详细阅读 <?php the_title(); ?>" rel="bookmark">详细阅读</a></div>
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