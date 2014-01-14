<?php
if ( get_post_meta($post->ID, 'thumbnail', true) ) :
	echo "<div class='thumbnail_t'>";
	$image = get_post_meta($post->ID, 'thumbnail', true);
	echo "<a href='".get_permalink()."' rel='bookmark' title='".get_the_title()."'><img src='".$image."' alt='".get_the_title()."'/></a></div>";
else:
	echo "<div class='thumbnail'>";
	echo "<a href='".get_permalink()."' rel='bookmark' title='".get_the_title()."'>";
	if (has_post_thumbnail()):
		the_post_thumbnail('thumbnail');
	else:
		echo "<img src='".catch_that_image()."' alt='".get_the_title()."'/>";
	endif;
	echo "</a></div>";
endif;
?>
