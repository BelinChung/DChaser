<?php
global $post;
$cats = wp_get_post_categories($post->ID);
if ($cats) {
$args = array(
        'category__in' => array( $cats[0] ),
        'post__not_in' => array( $post->ID ),
        'showposts' => 5,
        'caller_get_posts' => 1
    );
query_posts($args);

if (have_posts()) : 
    while (have_posts()) : the_post(); update_post_caches($posts); ?>
<li><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></li>
<?php endwhile; else : ?>
<li>暂无相关文章</li>
<?php endif; wp_reset_query(); } ?>