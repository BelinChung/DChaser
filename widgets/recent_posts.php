<?php

        $title = apply_filters('widget_title', empty($instance['title']) ? __('Recent Posts') : $instance['title'], $instance, $this->id_base);
        if ( empty( $instance['number'] ) || ! $number = absint( $instance['number'] ) )
            $number = 10;

        $r = new WP_Query( apply_filters( 'widget_posts_args', array( 'posts_per_page' => $number, 'no_found_rows' => true, 'post_status' => 'publish', 'ignore_sticky_posts' => true ) ) );
        if ($r->have_posts()) :
?>
        <?php echo $before_widget; ?>

        <?php
            if ( $title ) echo $before_title . $title . $after_title;
            $i = 0;
        ?>

        <ul id="accordion">
            <?php  while ($r->have_posts()) : $r->the_post(); ?>
            <li>
                <a class="accordion-button" href="javascript:void(0)"><?php if ( get_the_title() ) the_title(); else the_ID(); ?></a>
                <ul class="accordion-content">
                    <li><a  href="<?php the_permalink() ?>" title="<?php echo esc_attr(get_the_title() ? get_the_title() : get_the_ID()); ?>"><?php the_excerpt();?></a></li>
                </ul>
            </li>
            <?php endwhile; ?>
        </ul>
        <?php echo $after_widget; ?>
        <?php
            // Reset the global $the_post as this query will have stomped on it
            wp_reset_postdata();
            endif;
        ?>