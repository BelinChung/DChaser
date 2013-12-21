<?php if ( get_post_meta($post->ID, 'thumbnail', true) ) : ?><div class="thumbnail_t">
	<?php $image = get_post_meta($post->ID, 'thumbnail', true); ?>
	<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><img src="<?php echo $image; ?>" alt="<?php the_title(); ?>"/></a></div>
	<?php else: ?>

<!-- 截图 -->
<div class="thumbnail">
<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>">
<?php if (has_post_thumbnail()) { the_post_thumbnail('thumbnail'); }
else { ?>
<img src='<?=catch_that_image() ?>' alt="<?php the_title(); ?>"/>
<?php } ?>
</a>
<?php endif; ?>
</div>
