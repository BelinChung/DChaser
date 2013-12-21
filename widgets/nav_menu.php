<?php
// Get menu
        $nav_menu = ! empty( $instance['nav_menu'] ) ? wp_get_nav_menu_object( $instance['nav_menu'] ) : false;

        if ( !$nav_menu )
            return;
        $instance['title'] = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
        if ( !empty($instance['title']) )
            echo $before_title . $instance['title'] . $args['after_title'];

        wp_nav_menu( array( 'fallback_cb' => '', 'menu' => $nav_menu ) );

        echo $after_widget;