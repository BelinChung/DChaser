<?php
/**
 * Copyright (c) 2012 3g4k.com.
 * All rights reserved.
 * @package     gkwp
 * @author      luofei614 <weibo.com/luofei614>
 * @copyright   2012 3g4k.com.
 */

//评论小工具
class GK_Widget_Recent_Comments extends WP_Widget {

    function __construct() {

        $widget_ops = array('classname' => 'widget_recent_comments', 'description' => __( 'The most recent comments' ).__('，注：此小工具已被改装过了！','gkwp')  );
        parent::__construct('recent-comments', __('Recent Comments'), $widget_ops);
        $this->alt_option_name = 'widget_recent_comments';
    }

    function flush_widget_cache() {
        wp_cache_delete('widget_recent_comments', 'widget');
    }

    function widget( $args, $instance ) {
        global $comments, $comment;
        extract($args, EXTR_SKIP);
        include get_gk_file('widgets/comments.php');
    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['number'] = absint( $new_instance['number'] );
        $this->flush_widget_cache();

        $alloptions = wp_cache_get( 'alloptions', 'options' );
        if ( isset($alloptions['widget_recent_comments']) )
            delete_option('widget_recent_comments');

        return $instance;
    }

    function form( $instance ) {
        $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
        $number = isset($instance['number']) ? absint($instance['number']) : 3;
?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

        <p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of comments to show:'); ?></label>
        <input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>
<?php
    }
}


//页面
class GK_Widget_Pages extends WP_Widget {

    function __construct() {
        $widget_ops = array('classname' => 'widget_pages', 'description' => __( 'Your site&#8217;s WordPress Pages').__('，注：此小工具已被改装过了！','gkwp') );
        parent::__construct('pages', __('Pages'), $widget_ops);
    }

    function widget( $args, $instance ) {
        extract( $args );
        include get_gk_file('widgets/page.php');
    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        if ( in_array( $new_instance['sortby'], array( 'post_title', 'menu_order', 'ID' ) ) ) {
            $instance['sortby'] = $new_instance['sortby'];
        } else {
            $instance['sortby'] = 'menu_order';
        }

        $instance['exclude'] = strip_tags( $new_instance['exclude'] );

        return $instance;
    }

    function form( $instance ) {
        //Defaults
        $instance = wp_parse_args( (array) $instance, array( 'sortby' => 'post_title', 'title' => '', 'exclude' => '') );
        $title = esc_attr( $instance['title'] );
        $exclude = esc_attr( $instance['exclude'] );
    ?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>
        <p>
            <label for="<?php echo $this->get_field_id('sortby'); ?>"><?php _e( 'Sort by:' ); ?></label>
            <select name="<?php echo $this->get_field_name('sortby'); ?>" id="<?php echo $this->get_field_id('sortby'); ?>" class="widefat">
                <option value="post_title"<?php selected( $instance['sortby'], 'post_title' ); ?>><?php _e('Page title'); ?></option>
                <option value="menu_order"<?php selected( $instance['sortby'], 'menu_order' ); ?>><?php _e('Page order'); ?></option>
                <option value="ID"<?php selected( $instance['sortby'], 'ID' ); ?>><?php _e( 'Page ID' ); ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('exclude'); ?>"><?php _e( 'Exclude:' ); ?></label> <input type="text" value="<?php echo $exclude; ?>" name="<?php echo $this->get_field_name('exclude'); ?>" id="<?php echo $this->get_field_id('exclude'); ?>" class="widefat" />
            <br />
            <small><?php _e( 'Page IDs, separated by commas.' ); ?></small>
        </p>
<?php
    }

}

/**
 * Links widget class
 *
 * @since 2.8.0
 */
class GK_Widget_Links extends WP_Widget {

    function __construct() {
        $widget_ops = array('description' => __( "Your blogroll" ).__('，注：此小工具已被改装过了！','gkwp')  );
        parent::__construct('links', __('Links'), $widget_ops);
    }

    function widget( $args, $instance ) {
        extract($args, EXTR_SKIP);
        include get_gk_file('widgets/links.php');
    }

    function update( $new_instance, $old_instance ) {
        $new_instance = (array) $new_instance;
        $instance = array( 'images' => 0, 'name' => 0, 'description' => 0, 'rating' => 0 );
        foreach ( $instance as $field => $val ) {
            if ( isset($new_instance[$field]) )
                $instance[$field] = 1;
        }

        $instance['orderby'] = 'name';
        if ( in_array( $new_instance['orderby'], array( 'name', 'rating', 'id', 'rand' ) ) )
            $instance['orderby'] = $new_instance['orderby'];

        $instance['category'] = intval( $new_instance['category'] );
        $instance['limit'] = ! empty( $new_instance['limit'] ) ? intval( $new_instance['limit'] ) : -1;

        return $instance;
    }

    function form( $instance ) {

        //Defaults
        $instance = wp_parse_args( (array) $instance, array( 'images' => true, 'name' => true, 'description' => false, 'rating' => false, 'category' => false, 'orderby' => 'name', 'limit' => -1 ) );
        $link_cats = get_terms( 'link_category' );
        if ( ! $limit = intval( $instance['limit'] ) )
            $limit = -1;
?>
        <p>
        <label for="<?php echo $this->get_field_id('category'); ?>"><?php _e( 'Select Link Category:' ); ?></label>
        <select class="widefat" id="<?php echo $this->get_field_id('category'); ?>" name="<?php echo $this->get_field_name('category'); ?>">
        <option value=""><?php _ex('All Links', 'links widget'); ?></option>
        <?php
        foreach ( $link_cats as $link_cat ) {
            echo '<option value="' . intval($link_cat->term_id) . '"'
                . ( $link_cat->term_id == $instance['category'] ? ' selected="selected"' : '' )
                . '>' . $link_cat->name . "</option>\n";
        }
        ?>
        </select>
        <label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e( 'Sort by:' ); ?></label>
        <select name="<?php echo $this->get_field_name('orderby'); ?>" id="<?php echo $this->get_field_id('orderby'); ?>" class="widefat">
            <option value="name"<?php selected( $instance['orderby'], 'name' ); ?>><?php _e( 'Link title' ); ?></option>
            <option value="rating"<?php selected( $instance['orderby'], 'rating' ); ?>><?php _e( 'Link rating' ); ?></option>
            <option value="id"<?php selected( $instance['orderby'], 'id' ); ?>><?php _e( 'Link ID' ); ?></option>
            <option value="rand"<?php selected( $instance['orderby'], 'rand' ); ?>><?php _e( 'Random' ); ?></option>
        </select>
        </p>
        <p>
        <input class="checkbox" type="checkbox" <?php checked($instance['images'], true) ?> id="<?php echo $this->get_field_id('images'); ?>" name="<?php echo $this->get_field_name('images'); ?>" />
        <label for="<?php echo $this->get_field_id('images'); ?>"><?php _e('Show Link Image'); ?></label><br />
        <input class="checkbox" type="checkbox" <?php checked($instance['name'], true) ?> id="<?php echo $this->get_field_id('name'); ?>" name="<?php echo $this->get_field_name('name'); ?>" />
        <label for="<?php echo $this->get_field_id('name'); ?>"><?php _e('Show Link Name'); ?></label><br />
        <input class="checkbox" type="checkbox" <?php checked($instance['description'], true) ?> id="<?php echo $this->get_field_id('description'); ?>" name="<?php echo $this->get_field_name('description'); ?>" />
        <label for="<?php echo $this->get_field_id('description'); ?>"><?php _e('Show Link Description'); ?></label><br />
        <input class="checkbox" type="checkbox" <?php checked($instance['rating'], true) ?> id="<?php echo $this->get_field_id('rating'); ?>" name="<?php echo $this->get_field_name('rating'); ?>" />
        <label for="<?php echo $this->get_field_id('rating'); ?>"><?php _e('Show Link Rating'); ?></label>
        </p>
        <p>
        <label for="<?php echo $this->get_field_id('limit'); ?>"><?php _e( 'Number of links to show:' ); ?></label>
        <input id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" type="text" value="<?php echo $limit == -1 ? '' : intval( $limit ); ?>" size="3" />
        </p>
<?php
    }
}



/**
 * Search widget class
 *
 * @since 2.8.0
 */
class GK_Widget_Search extends WP_Widget {

    function __construct() {
        $widget_ops = array('classname' => 'widget_search', 'description' => __( "A search form for your site").__('，注：此小工具已被改装过了！','gkwp')  );
        parent::__construct('search', __('Search'), $widget_ops);
    }

    function widget( $args, $instance ) {
        extract($args);
        include get_gk_file('widgets/search.php');
    }

    function form( $instance ) {
        $instance = wp_parse_args( (array) $instance, array( 'title' => '') );
        $title = $instance['title'];
?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>
<?php
    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $new_instance = wp_parse_args((array) $new_instance, array( 'title' => ''));
        $instance['title'] = strip_tags($new_instance['title']);
        return $instance;
    }

}

/**
 * weiboShow widget class
 *
 * @since 2.8.0
 */
class GK_Widget_weiboShow extends WP_Widget {

    function __construct() {
        $widget_ops = array('classname' => 'widget_weiboShow', 'description' => __( "微博秀").__('，注：此小工具只支持新浪微博秀代码！','gkwp')  );
        parent::__construct('weiboShow', __('微博秀'), $widget_ops);
    }
    
    function widget( $args, $instance ) {
        extract($args);
        include get_gk_file('widgets/weiboShow.php');
    }
    
     function form( $instance ) {
        $instance = wp_parse_args( (array) $instance, array( 'uid' => '','verifier'=>'','sitemap'=>'','bd_sitemap'=>'') );
        $uid = $instance['uid'];
        $verifier = $instance['verifier'];
        $sitemap = $instance['sitemap'];
        $bd_sitemap = $instance['bd_sitemap'];
?>
        <p><label for="<?php echo $this->get_field_id('uid'); ?>"><?php _e('uid:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('uid'); ?>" name="<?php echo $this->get_field_name('uid'); ?>" type="text" value="<?php echo esc_attr($uid); ?>" /></label></p>
        <p><label for="<?php echo $this->get_field_id('verifier'); ?>"><?php _e('verifier:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('verifier'); ?>" name="<?php echo $this->get_field_name('verifier'); ?>" type="text" value="<?php echo esc_attr($verifier); ?>" /></label></p>
        <p><label for="<?php echo $this->get_field_id('sitemap'); ?>"><?php _e('sitemap:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('sitemap'); ?>" name="<?php echo $this->get_field_name('sitemap'); ?>" type="text" value="<?php echo esc_attr($sitemap); ?>" /></label></p>
        <p><label for="<?php echo $this->get_field_id('bd_sitemap'); ?>"><?php _e('bd_sitemap:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('bd_sitemap'); ?>" name="<?php echo $this->get_field_name('bd_sitemap'); ?>" type="text" value="<?php echo esc_attr($bd_sitemap); ?>" /></label></p>
<?php
    }
    
    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $new_instance = wp_parse_args((array) $new_instance, array( 'uid' => '','verifier'=>'','sitemap'=>'','bd_sitemap'=>''));
        $instance['uid'] = strip_tags($new_instance['uid']);
        $instance['verifier'] = strip_tags($new_instance['verifier']);
        $instance['sitemap'] = strip_tags($new_instance['sitemap']);
        $instance['bd_sitemap'] = strip_tags($new_instance['bd_sitemap']);
        return $instance;
    }
}

/**
 * Archives widget class
 *
 * @since 2.8.0
 */
class GK_Widget_Archives extends WP_Widget {

    function __construct() {
        $widget_ops = array('classname' => 'widget_archive', 'description' => __( 'A monthly archive of your site&#8217;s posts').__('，注：此小工具已被改装过了！','gkwp')  );
        parent::__construct('archives', __('Archives'), $widget_ops);
    }

    function widget( $args, $instance ) {
        extract($args);
        include get_gk_file('widgets/archives.php');
    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $new_instance = wp_parse_args( (array) $new_instance, array( 'title' => '', 'count' => 0, 'dropdown' => '') );
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['count'] = $new_instance['count'] ? 1 : 0;
        $instance['dropdown'] = $new_instance['dropdown'] ? 1 : 0;

        return $instance;
    }

    function form( $instance ) {
        $instance = wp_parse_args( (array) $instance, array( 'title' => '', 'count' => 0, 'dropdown' => '') );
        $title = strip_tags($instance['title']);
        $count = $instance['count'] ? 'checked="checked"' : '';
        $dropdown = $instance['dropdown'] ? 'checked="checked"' : '';
?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
        <p>
            <input class="checkbox" type="checkbox" <?php echo $dropdown; ?> id="<?php echo $this->get_field_id('dropdown'); ?>" name="<?php echo $this->get_field_name('dropdown'); ?>" /> <label for="<?php echo $this->get_field_id('dropdown'); ?>"><?php _e('Display as dropdown'); ?></label>
            <br/>
            <input class="checkbox" type="checkbox" <?php echo $count; ?> id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" /> <label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('Show post counts'); ?></label>
        </p>
<?php
    }
}



/**
 * Meta widget class
 *
 * Displays log in/out, RSS feed links, etc.
 *
 * @since 2.8.0
 */
class GK_Widget_Meta extends WP_Widget {

    function __construct() {
        $widget_ops = array('classname' => 'widget_meta', 'description' => __( "Log in/out, admin, feed and WordPress links").__('，注：此小工具已被改装过了！','gkwp')  );
        parent::__construct('meta', __('Meta'), $widget_ops);
    }

    function widget( $args, $instance ) {
        extract($args);
        include get_gk_file('widgets/meta.php');
    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);

        return $instance;
    }

    function form( $instance ) {
        $instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
        $title = strip_tags($instance['title']);
?>
            <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
<?php
    }
}

/**
 * Calendar widget class
 *
 * @since 2.8.0
 */
class GK_Widget_Calendar extends WP_Widget {

    function __construct() {
        $widget_ops = array('classname' => 'widget_calendar', 'description' => __( 'A calendar of your site&#8217;s posts').__('，注：此小工具已被改装过了！','gkwp')  );
        parent::__construct('calendar', __('Calendar'), $widget_ops);
    }

    function widget( $args, $instance ) {
        extract($args);
        include get_gk_file('widgets/calendar.php');
    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);

        return $instance;
    }

    function form( $instance ) {
        $instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
        $title = strip_tags($instance['title']);
?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
<?php
    }
}

/**
 * Text widget class
 *
 * @since 2.8.0
 */
class GK_Widget_Text extends WP_Widget {

    function __construct() {
        $widget_ops = array('classname' => 'widget_text', 'description' => __('Arbitrary text or HTML').__('，注：此小工具已被改装过了！','gkwp') );
        $control_ops = array('width' => 400, 'height' => 350);
        parent::__construct('text', __('Text'), $widget_ops, $control_ops);
    }

    function widget( $args, $instance ) {
        extract($args);
        include get_gk_file('widgets/text.php');
    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        if ( current_user_can('unfiltered_html') )
            $instance['text'] =  $new_instance['text'];
        else
            $instance['text'] = stripslashes( wp_filter_post_kses( addslashes($new_instance['text']) ) ); // wp_filter_post_kses() expects slashed
        $instance['filter'] = isset($new_instance['filter']);
        return $instance;
    }

    function form( $instance ) {
        $instance = wp_parse_args( (array) $instance, array( 'title' => '', 'text' => '' ) );
        $title = strip_tags($instance['title']);
        $text = esc_textarea($instance['text']);
?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

        <textarea class="widefat" rows="16" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo $text; ?></textarea>

        <p><input id="<?php echo $this->get_field_id('filter'); ?>" name="<?php echo $this->get_field_name('filter'); ?>" type="checkbox" <?php checked(isset($instance['filter']) ? $instance['filter'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id('filter'); ?>"><?php _e('Automatically add paragraphs'); ?></label></p>
<?php
    }
}

/**
 * Categories widget class
 *
 * @since 2.8.0
 */
class GK_Widget_Categories extends WP_Widget {

    function __construct() {
        $widget_ops = array( 'classname' => 'widget_categories', 'description' => __( "A list or dropdown of categories" ).__('，注：此小工具已被改装过了！','gkwp')  );
        parent::__construct('categories', __('Categories'), $widget_ops);
    }

    function widget( $args, $instance ) {
        extract( $args );
        include get_gk_file('widgets/categories.php');
    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['count'] = !empty($new_instance['count']) ? 1 : 0;
        $instance['hierarchical'] = !empty($new_instance['hierarchical']) ? 1 : 0;
        $instance['dropdown'] = !empty($new_instance['dropdown']) ? 1 : 0;

        return $instance;
    }

    function form( $instance ) {
        //Defaults
        $instance = wp_parse_args( (array) $instance, array( 'title' => '') );
        $title = esc_attr( $instance['title'] );
        $count = isset($instance['count']) ? (bool) $instance['count'] :false;
        $hierarchical = isset( $instance['hierarchical'] ) ? (bool) $instance['hierarchical'] : false;
        $dropdown = isset( $instance['dropdown'] ) ? (bool) $instance['dropdown'] : false;
?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:' ); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

        <p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('dropdown'); ?>" name="<?php echo $this->get_field_name('dropdown'); ?>"<?php checked( $dropdown ); ?> />
        <label for="<?php echo $this->get_field_id('dropdown'); ?>"><?php _e( 'Display as dropdown' ); ?></label><br />

        <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>"<?php checked( $count ); ?> />
        <label for="<?php echo $this->get_field_id('count'); ?>"><?php _e( 'Show post counts' ); ?></label><br />

        <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('hierarchical'); ?>" name="<?php echo $this->get_field_name('hierarchical'); ?>"<?php checked( $hierarchical ); ?> />
        <label for="<?php echo $this->get_field_id('hierarchical'); ?>"><?php _e( 'Show hierarchy' ); ?></label></p>
<?php
    }

}

/**
 * Recent_Posts widget class
 *
 * @since 2.8.0
 */
class GK_Widget_Recent_Posts extends WP_Widget {

    function __construct() {
        $widget_ops = array('classname' => 'widget_recent_entries', 'description' => __( "The most recent posts on your site").__('，注：此小工具已被改装过了！','gkwp')  );
        parent::__construct('recent-posts', __('Recent Posts'), $widget_ops);
        $this->alt_option_name = 'widget_recent_entries';

        add_action( 'save_post', array(&$this, 'flush_widget_cache') );
        add_action( 'deleted_post', array(&$this, 'flush_widget_cache') );
        add_action( 'switch_theme', array(&$this, 'flush_widget_cache') );
    }

    function widget($args, $instance) {
        $cache = wp_cache_get('widget_recent_posts', 'widget');

        if ( !is_array($cache) )
            $cache = array();

        if ( ! isset( $args['widget_id'] ) )
            $args['widget_id'] = $this->id;

        if ( isset( $cache[ $args['widget_id'] ] ) ) {
            echo $cache[ $args['widget_id'] ];
            return;
        }

        ob_start();
        extract($args);

        include get_gk_file('widgets/recent_posts.php');

        $cache[$args['widget_id']] = ob_get_flush();
        wp_cache_set('widget_recent_posts', $cache, 'widget');
    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['number'] = (int) $new_instance['number'];
        $this->flush_widget_cache();

        $alloptions = wp_cache_get( 'alloptions', 'options' );
        if ( isset($alloptions['widget_recent_entries']) )
            delete_option('widget_recent_entries');

        return $instance;
    }

    function flush_widget_cache() {
        wp_cache_delete('widget_recent_posts', 'widget');
    }

    function form( $instance ) {
        $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
        $number = isset($instance['number']) ? absint($instance['number']) : 5;
?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

        <p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of posts to show:'); ?></label>
        <input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>
<?php
    }
}



/**
 * RSS widget class
 *
 * @since 2.8.0
 */
class GK_Widget_RSS extends WP_Widget {

    function __construct() {
        $widget_ops = array( 'description' => __('Entries from any RSS or Atom feed').__('，注：此小工具已被改装过了！','gkwp')  );
        $control_ops = array( 'width' => 400, 'height' => 200 );
        parent::__construct( 'rss', __('RSS'), $widget_ops, $control_ops );
    }

    function widget($args, $instance) {

        if ( isset($instance['error']) && $instance['error'] )
            return;

        extract($args, EXTR_SKIP);
        include get_gk_file('widgets/rss.php');
    }

    function update($new_instance, $old_instance) {
        $testurl = ( isset( $new_instance['url'] ) && ( !isset( $old_instance['url'] ) || ( $new_instance['url'] != $old_instance['url'] ) ) );
        return wp_widget_rss_process( $new_instance, $testurl );
    }

    function form($instance) {

        if ( empty($instance) )
            $instance = array( 'title' => '', 'url' => '', 'items' => 10, 'error' => false, 'show_summary' => 0, 'show_author' => 0, 'show_date' => 0 );
        $instance['number'] = $this->number;

        wp_widget_rss_form( $instance );
    }
}



/**
 * Tag cloud widget class
 *
 * @since 2.8.0
 */
class GK_Widget_Tag_Cloud extends WP_Widget {

    function __construct() {
        $widget_ops = array( 'description' => __( "Your most used tags in cloud format").__('，注：此小工具已被改装过了！','gkwp')  );
        parent::__construct('tag_cloud', __('Tag Cloud'), $widget_ops);
    }

    function widget( $args, $instance ) {
        extract($args);
        include get_gk_file('widgets/tag_cloud.php');
    }

    function update( $new_instance, $old_instance ) {
        $instance['title'] = strip_tags(stripslashes($new_instance['title']));
        $instance['taxonomy'] = stripslashes($new_instance['taxonomy']);
        return $instance;
    }

    function form( $instance ) {
        $current_taxonomy = $this->_get_current_taxonomy($instance);
?>
    <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:') ?></label>
    <input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php if (isset ( $instance['title'])) {echo esc_attr( $instance['title'] );} ?>" /></p>
    <p><label for="<?php echo $this->get_field_id('taxonomy'); ?>"><?php _e('Taxonomy:') ?></label>
    <select class="widefat" id="<?php echo $this->get_field_id('taxonomy'); ?>" name="<?php echo $this->get_field_name('taxonomy'); ?>">
    <?php foreach ( get_taxonomies() as $taxonomy ) :
                $tax = get_taxonomy($taxonomy);
                if ( !$tax->show_tagcloud || empty($tax->labels->name) )
                    continue;
    ?>
        <option value="<?php echo esc_attr($taxonomy) ?>" <?php selected($taxonomy, $current_taxonomy) ?>><?php echo $tax->labels->name; ?></option>
    <?php endforeach; ?>
    </select></p><?php
    }

    function _get_current_taxonomy($instance) {
        if ( !empty($instance['taxonomy']) && taxonomy_exists($instance['taxonomy']) )
            return $instance['taxonomy'];

        return 'post_tag';
    }
}

/**
 * Navigation Menu widget class
 *
 * @since 3.0.0
 */
 class GK_Nav_Menu_Widget extends WP_Widget {

    function __construct() {
        $widget_ops = array( 'description' => __('Use this widget to add one of your custom menus as a widget.').__('，注：此小工具已被改装过了！','gkwp')  );
        parent::__construct( 'nav_menu', __('Custom Menu'), $widget_ops );
    }

    function widget($args, $instance) {
        include get_gk_file('widgets/nav_menu.php');
    }

    function update( $new_instance, $old_instance ) {
        $instance['title'] = strip_tags( stripslashes($new_instance['title']) );
        $instance['nav_menu'] = (int) $new_instance['nav_menu'];
        return $instance;
    }

    function form( $instance ) {
        $title = isset( $instance['title'] ) ? $instance['title'] : '';
        $nav_menu = isset( $instance['nav_menu'] ) ? $instance['nav_menu'] : '';

        // Get menus
        $menus = get_terms( 'nav_menu', array( 'hide_empty' => false ) );

        // If no menus exists, direct the user to go and create some.
        if ( !$menus ) {
            echo '<p>'. sprintf( __('No menus have been created yet. <a href="%s">Create some</a>.'), admin_url('nav-menus.php') ) .'</p>';
            return;
        }
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:') ?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('nav_menu'); ?>"><?php _e('Select Menu:'); ?></label>
            <select id="<?php echo $this->get_field_id('nav_menu'); ?>" name="<?php echo $this->get_field_name('nav_menu'); ?>">
        <?php
            foreach ( $menus as $menu ) {
                $selected = $nav_menu == $menu->term_id ? ' selected="selected"' : '';
                echo '<option'. $selected .' value="'. $menu->term_id .'">'. $menu->name .'</option>';
            }
        ?>
            </select>
        </p>
        <?php
    }
}


