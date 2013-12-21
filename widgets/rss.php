<?php

        $url = ! empty( $instance['url'] ) ? $instance['url'] : '';
        while ( stristr($url, 'http') != $url )
            $url = substr($url, 1);

        if ( empty($url) )
            return;

        // self-url destruction sequence
        if ( in_array( untrailingslashit( $url ), array( site_url(), home_url() ) ) )
            return;

        $rss = fetch_feed($url);
        $title = $instance['title'];
        $desc = '';
        $link = '';

        if ( ! is_wp_error($rss) ) {
            $desc = esc_attr(strip_tags(@html_entity_decode($rss->get_description(), ENT_QUOTES, get_option('blog_charset'))));
            if ( empty($title) )
                $title = esc_html(strip_tags($rss->get_title()));
            $link = esc_url(strip_tags($rss->get_permalink()));
            while ( stristr($link, 'http') != $link )
                $link = substr($link, 1);
        }

        if ( empty($title) )
            $title = empty($desc) ? __('Unknown Feed') : $desc;

        $title = apply_filters('widget_title', $title, $instance, $this->id_base);
        $url = esc_url(strip_tags($url));
        $icon = includes_url('images/rss.png');
        if ( $title )
            $title = "<a class='rsswidget' href='$url' title='" . esc_attr__( 'Syndicate this content' ) ."'><img style='border:0' width='14' height='14' src='$icon' alt='RSS' /></a> <a class='rsswidget' href='$link' title='$desc'>$title</a>";

        echo $before_widget;
        if ( $title )
            echo $before_title . $title . $after_title;
        wp_widget_rss_output( $rss, $instance );
        echo $after_widget;

        if ( ! is_wp_error($rss) )
            $rss->__destruct();
        unset($rss);