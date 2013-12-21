<?php
        $show_description = isset($instance['description']) ? $instance['description'] : false;
        $show_name = isset($instance['name']) ? $instance['name'] : false;
        $show_rating = isset($instance['rating']) ? $instance['rating'] : false;
        $show_images = isset($instance['images']) ? $instance['images'] : true;
        $category = isset($instance['category']) ? $instance['category'] : false;
        $orderby = isset( $instance['orderby'] ) ? $instance['orderby'] : 'name';
        $order = $orderby == 'rating' ? 'DESC' : 'ASC';
        $limit = isset( $instance['limit'] ) ? $instance['limit'] : -1;

        $before_widget = preg_replace('/id="[^"]*"/','id="%id"', $before_widget);
        wp_list_bookmarks(apply_filters('widget_links_args', array(
            'title_before' => $before_title, 'title_after' => $after_title,
            'category_before' => $before_widget, 'category_after' => $after_widget,
            'show_images' => $show_images, 'show_description' => $show_description,
            'show_name' => $show_name, 'show_rating' => $show_rating,
            'category' => $category, 'class' => 'linkcat widget',
            'orderby' => $orderby, 'order' => $order,
            'limit' => $limit,
        )));