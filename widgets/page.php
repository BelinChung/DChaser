<?php
        $title = apply_filters('widget_title', empty( $instance['title'] ) ? __( 'Pages' ) : $instance['title'], $instance, $this->id_base);
        $sortby = empty( $instance['sortby'] ) ? 'menu_order' : $instance['sortby'];
        $exclude = empty( $instance['exclude'] ) ? '' : $instance['exclude'];

        if ( $sortby == 'menu_order' )
            $sortby = 'menu_order, post_title';

        $out = wp_list_pages( apply_filters('widget_pages_args', array('title_li' => '', 'echo' => 0, 'sort_column' => $sortby, 'exclude' => $exclude) ) );

        if ( !empty( $out ) ) {
            echo $before_widget;
            if ( $title)
                echo $before_title . $title . $after_title;
        ?>
        <ul>
            <?php echo $out; ?>
        </ul>
        <?php
            echo $after_widget;
        }