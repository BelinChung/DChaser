<?php 
/**
 * Template Name: 留言
 *
 */
 get_header('meta');
 get_header(); 
 ?>
<div class="heading-top">
当前位置：<a href="<?php bloginfo('siteurl'); ?>/" title="返回首页">首页</a> > <?php the_title(); ?>
</div>
<!-- Main Container -->
<div id="body-wrapper">
 <!-- Content -->
    <div id="content" class="clearfix">
        <!-- Main Content -->
        <div class="main">
            <?php if(have_posts()) : ?><?php while(have_posts()) : the_post(); ?>
            <div class="divleft">
                <div class="single_list">
                    <span class="comment-count"><?php comments_popup_link ('0°','+1°','+%°'); ?></span><h2><?php the_title() ?></h2>
                    <div class="hr-line" style="margin-bottom: 8px;"></div>
                    <div class="post-entry">      
                    <?php the_content() ?>
                    <?php
                        $query = "SELECT COUNT(comment_ID) AS cnt, comment_author, comment_author_url, comment_author_email FROM (SELECT * FROM $wpdb->comments LEFT OUTER JOIN $wpdb->posts ON ($wpdb->posts.ID=$wpdb->comments.comment_post_ID) WHERE comment_date > date_sub( NOW(), INTERVAL 24 MONTH ) AND user_id='0' AND comment_author_email != '改成你的邮箱账号' AND post_password='' AND comment_approved='1' AND comment_type='') AS tempcmt GROUP BY comment_author_email ORDER BY cnt DESC LIMIT 40";
                        $wall = $wpdb->get_results($query);
                        $maxNum = $wall[0]->cnt;
                        foreach ($wall as $comment)
                        {
                            $width = round(40 / ($maxNum / $comment->cnt),2);//此处是对应的血条的宽度
                            if( $comment->comment_author_url )
                                $url = $comment->comment_author_url;
                            else $url="#";
                                $avatar = get_avatar( $comment->comment_author_email, $size = '36' );
                                $tmp = "<li><a target=\"_blank\" href=\"".$comment->comment_author_url."\">".$avatar."<span>".$comment->comment_author."</span> <strong>+".$comment->cnt."</strong></br>".$comment->comment_author_url."</a></li>";
                                $output .= $tmp;
                        }
                        $output = "<ul class=\"readers\">".$output."</ul>";
                        echo $output ;
                    ?>
                    </div>
                </div>
            </div>
         <!-- /Post -->
                <?php comments_template('', true); ?>
            <?php endwhile; ?>
                <?php endif; ?>	
        </div>
        <!-- /Main Content -->
<?php get_sidebar(); ?></div>

<?php get_footer(); ?> 