 <?php
 get_header('meta');
 get_header(); 
 ?>
 <div class="heading-top">
当前位置：<a href="<?php bloginfo('siteurl'); ?>/" title="返回首页">首页</a> > <?php $categories = get_the_category(); echo(get_category_parents($categories[0]->term_id, TRUE, ' > '));  ?>正文
</div>
<!-- Main Container -->
<div id="body-wrapper">
 <!-- Content -->
    <div id="content" class="clearfix">
        <!-- Main Content -->
        <div class="main">
            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            <!-- Post -->
            <div class="divleft">
                <div class="single_list">
                    <span class="comment-count"><?php comments_popup_link ('0°','+1°','+%°'); ?></span>
                    <h2 class="title"><?php the_title() ?></h2>
                    <ul class="post-meta">
                        <li class="author">作者：<a href="javascript:void(0)"><?php the_author(); ?></a></li>
                        <li class="date"><?php the_time('Y年m月d日') ?></li>
                        <li class="comments"><a href="javascript:void(0)"><?php if(function_exists('the_views')) { print '被围观 '; the_views();  } ?><?php edit_post_link('编辑', '　|　'); ?></a></li>
                    </ul>
                    <div class="post-entry">
                        <?php the_content(); ?>
                    </div>
                </div>
            </div>
            <div class="divleft">
                <div class="single_list">本文固定链接：<a href="<?php the_permalink() ?>" title="<?php the_title(); ?>" rel="bookmark"><?php the_permalink() ?></a></div></div>
            <div class="divleft">
                <div class="single_list"><div class="content_tx"><?php echo get_avatar( get_the_author_email(), '80' ); ?></div><div class="content_sm">本文章由 <a href="<?php bloginfo('siteurl'); ?>/"><strong><?php the_author() ?></strong></a> 于<?php the_time('Y年m月d日') ?>发布在<?php the_category(', ') ?>分类下，<?php if (('open' == $post-> comment_status) && ('open' == $post->ping_status)) {?>您可以<a href="#respond">发表评论</a>，并在保留<a href="<?php the_permalink() ?>" rel="bookmark">原文地址</a>及作者的情况下<a href="<?php trackback_url(); ?>" rel="trackback">引用</a>到你的网站或博客。
                <?php } elseif (('open' == $post-> comment_status) && !('open' == $post->ping_status)) { ?>
                通告目前不可用，你可以至底部留下评论。
                <?php } ?><br/>
                转载请注明：<a href="<?php the_permalink() ?>" rel="bookmark" title="本文固定链接 <?php the_permalink() ?>"><?php the_title(); ?>-<?php bloginfo('name');?></a>
                <br/>
                <?php the_tags('关键字：', ', ', ''); ?></div></div></div>
            <div class="divleft">
                <div class="single_list"><div class="single_listl"><?php  if (get_next_post()) {next_post_link('%link'); } else { echo "已经最新的文章！"; }; ?></div>
                <div class="single_listr"><?php  if (get_previous_post()) {previous_post_link('%link'); } else { echo "后面已经没有文章了"; }; ?></div></div></div>
            <div class="divleft">
                <div class="single_list">
                <h2 class="bdshare-title">好文章就要一起分享！</h2>
                <!-- Baidu Button BEGIN -->

                <div id="bdshare" class="bdshare_t bds_tools_32 get-codes-bdshare">
                    <a class="bds_tsina"></a>
                    <a class="bds_qzone"></a>
                    <a class="bds_tqq"></a>
                    <a class="bds_renren"></a>
                    <a class="bds_ty"></a>
                    <a class="bds_ff"></a>
                    <a class="bds_fbook"></a>
                    <a class="bds_baidu"></a>
                    <a class="bds_hi"></a>
                    <a class="bds_zx"></a>
                    <a class="bds_douban"></a>
                    <a class="bds_t163"></a>
                    <a class="bds_xg"></a>
                    <a class="bds_qq"></a>
		            <a class="bds_tieba"></a>
                    <span class="bds_more">更多</span>
                    <a class="shareCount"></a>
                </div>
                <script type="text/javascript" id="bdshare_js" data="type=tools&amp;uid=730973" ></script>
                <script type="text/javascript" id="bdshell_js"></script>
                <script type="text/javascript">
	               document.getElementById("bdshell_js").src = "http://bdimg.share.baidu.com/static/js/shell_v2.js?cdnversion=" + new Date().getHours();
                </script>
                <!-- Baidu Button END --></div></div>
                <div class="rand-article">
                    <div class="textlist_s"><h2>真的，我想您也会喜欢</h2>
                    <div class="hr-line"></div><ul><?php include("lib/Get_Related_Post.php"); ?></ul></div></div>
                    <div class="rand-article"><div class="textlist_s"><h2>随便找了点看您喜欢不</h2>
                    <div class="hr-line"></div><ul><?php Get_Random_Post(); ?></ul></div></div>
                <!-- /Post -->
  <?php comments_template('', true); ?>
    <?php endwhile; ?>
        <?php endif; ?>	
        </div>
        <!-- /Main Content -->
<?php get_sidebar(); ?></div>

<?php get_footer(); ?> 