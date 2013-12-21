<div id="single_comments">
	<?php if ( post_password_required() ) : ?>
	<p class="nopassword"><?php _e( '这篇文章是被保护的！', 'lovnvns' ); ?></p>
</div>
<?php
	return;
	endif;
?>


<?php if ( have_comments() ) : ?>
			<h3 id="comments-title"><?php
			printf( _n( '目前共有1条留言', '目前共有%1$s条留言', get_comments_number(), 'lovnvns' ),
			number_format_i18n( get_comments_number() ), get_the_title() );
			?></h3>

			<ul class="commentlist">
				<?php wp_list_comments( array( 'callback' => 'lovnvns_comment' ) );?>
			</ul>

<?php if ( get_comment_pages_count() > 1  ) : // Are there comments to navigate through? ?>
			<div class="pagination">
				<?php paginate_comments_links('prev_text=上一页&next_text=下一页');?>
			</div><!-- .navigation -->
            <div class="clear"></div>
<?php endif; // check for comment navigation ?>

<?php else : // or, if we don't have comments:
	if ( ! comments_open() ) :
?>
	<p class="nocomments"><?php _e( '评论已关闭！', 'lovnvns' ); ?></p>   
    <input type="hidden" id="comment_parent" name="comment_parent" value="" />
<?php endif; // end ! comments_open() ?>

<?php endif; // end have_comments() ?>



<?php if ( comments_open() ) : ?>
<div id="respond">
<h3><?php comment_form_title( __('发表评论','lovnvns'), __('回复 %s 的评论','lovnvns') );cancel_comment_reply_link(__('  取消','lovnvns')); ?></h3>
<?php if(get_option('lovnvns_comment_ad')!="")
    	echo '<div id="form_ad">'.get_option('lovnvns_comment_ad').'</div>';
?>
<div id="formcomment">
<?php if ( get_option('comment_registration') && !is_user_logged_in() ) : ?>
<p><?php _e('您必须','lovnvns'); ?> <a href="<?php echo wp_login_url( get_permalink() ); ?>"><?php _e('登陆','lovnvns'); ?></a> <?php _e('才可以评论','lovnvns'); ?></p></div>
<?php else : ?>



<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
<?php if ( is_user_logged_in() ) : ?>
<p><?php _e('登陆者','lovnvns'); ?> <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo wp_logout_url(get_permalink()); ?>" title="<?php _e('注销账户','lovnvns'); ?>"><?php _e('退出','lovnvns'); ?> &raquo;</a></p><div class="clear"></div>
<?php else : ?>
<div id="form_info">
    <p>你的大名（必填）<br /><input type="text" name="author" id="author" value="<?php echo esc_attr($comment_author); ?>" tabindex="1" /></p><div class="clear"></div>
    
    <p>你的邮箱（必填）<br /><input type="text" name="email" id="email" value="<?php echo esc_attr($comment_author_email); ?>"  tabindex="2" /></p><div class="clear"></div>
    
    <p>你的网站（选填）<br /><input type="text" name="url" id="url" value="<?php echo esc_attr($comment_author_url); ?>" tabindex="3" /></p><div class="clear"></div>
</div>
<?php endif; ?>
<div id="form_text">
	<p>评论内容（必填）<br /><textarea name="comment" id="comment" tabindex="4" rows="40" cols="43"></textarea></p>
</div>
<div class="clear"></div>
<p id="bq"><?php wp_smilies();?></p>
<p><input type="submit" name="submit" value="提交评论" class="red" tabindex="5" />
	<input class="red" name="reset" type="reset" id="reset" tabindex="6" value="<?php esc_attr_e( '重写' ); ?>" />
<?php comment_id_fields(); ?>
</p>
<?php do_action('comment_form', $post->ID); ?>
</form>
</div>
<?php endif; // If registration required and not logged in ?>
</div>

<?php endif; // if you delete this the sky will fall on your head ?>

</div>
