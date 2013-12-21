 <?php
 /**
 * Copyright (c) 2012 3g4k.com.
 * All rights reserved.
 * @package     gkwp
 * @author      luofei614 <weibo.com/luofei614>
 * @copyright   2012 3g4k.com.
 */
  $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? __( 'Recent Comments' ) : $instance['title'], $instance, $this->id_base );
    if ( empty( $instance['number'] ) || ! $number = absint( $instance['number'] ) )
        $number = 5;
    $comments = get_comments( apply_filters( 'widget_comments_args', array( 'number' => $number, 'status' => 'approve', 'post_status' => 'publish' ) ) );
    echo $before_widget;
    if ( $title )
        echo $before_title . $title . $after_title;

 echo '<ul class="unstyled">';
    Get_Recent_Comment();
 echo '</ul>';
 echo  $after_widget;