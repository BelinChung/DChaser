<?php
/**
 * Copyright (c) 2012 3g4k.com.
 * All rights reserved.
 * @package     gkwp
 * @author      luofei614 <weibo.com/luofei614>
 * @copyright   2012 3g4k.com.
 */
define('__PROOT__',get_template_directory());
define('__PURL__',get_template_directory_uri());
define('__ROOT__',get_stylesheet_directory());
define('__URL__',get_stylesheet_directory_uri());
define('IS_CHILD',is_child_theme());
$gkwp_version='1.1';
function gk_config($name=null,$value=null){
    static $config=array();
    if(is_array($name)){
        $config=array_merge($config,$name);
        return ;
    }elseif(!is_null($value)){
        $config[$name]=$value;
        return ;
    }
    if(is_null($value)) return is_null($name)?$config:$config[$name];
}

function get_gk_file($path){
    $path=ltrim($path,'/');
    if(IS_CHILD && file_exists(__ROOT__.'/'.$path)){
        return __ROOT__.'/'.$path;
    }else{
        return __PROOT__.'/'.$path;
    }
}

function get_gk_url($path){
    $path=ltrim($path,'/');
    if(IS_CHILD && file_exists(__ROOT__.'/'.$path)){
        return __URL__.'/'.$path;
    }else{
        return __PURL__.'/'.$path;
    }
}
function gk_url($path){
   echo get_gk_url();
}
//加载配置
gk_config(include __PROOT__.'/config/config.php');
if(IS_CHILD && file_exists(__ROOT__.'/config/config.php')) gk_config(include __ROOT__.'/config/config.php');
//加载扩展配置
$ext_config=gk_config('ext_config');
foreach($ext_config as $config_name){
    $config_value=include get_gk_file('config/'.$config_name.'.php');
    gk_config($config_name,$config_value);
}

/*if(is_admin()){
//升级检查
$gkwp_check_upgrade=get_option('gkwp_check_upgrade');
if(!$gkwp_check_upgrade || $gkwp_check_upgrade['last_time']<strtotime('-1 day')){
    //每天连接网络检查一次
    $text=@file_get_contents('http://sinaclouds.sinaapp.com/thinkapi/gkwp_upgrade.php?version='.$gkwp_version,false,stream_context_create(array('http'=>array('timeout'=>10))));
    if($text && ($upgrade_info=json_decode($text,true))){
        $gkwp_check_upgrade=array(
            'latest_version'=>$upgrade_info['version'],
            'latest_msg'=>$upgrade_info['msg'],
            'last_time'=>time()
        );
        update_option('gkwp_check_upgrade',$gkwp_check_upgrade);
    }
}

if(isset($gkwp_check_upgrade['latest_version']) && $gkwp_version!=$gkwp_check_upgrade['latest_version']){
    $GLOBALS['gk_upgrade_msg'] = $gkwp_check_upgrade['latest_msg'];
    add_action('admin_notices','gk_upgrade_notices');
}

function gk_upgrade_notices(){
    echo '<div class="update-nag">'.$GLOBALS['gk_upgrade_msg'].'</div>';
}

}*/

//调试模式
if(gk_config('debug') && current_user_can('level_10')){
    //结果错误
    set_error_handler('gk_error_handler');
    add_action('gk_show_debug','gk_show_debug');
    add_filter('template_include','gk_get_template');
}

function gk_error_handler($errno, $errstr, $errfile, $errline){
    $GLOBALS['gk_error'][] = array($errno,$errstr,$errfile,$errline);
}
function gk_get_template($template){
    $GLOBALS['gk_template'] = $template;
    return $template;
}
function gk_show_debug(){
    global $wp_query;
    $can_create_tpl=array();
    if     ( is_404() ) $can_create_tpl[]='404.php';
    if ( is_search())   $can_create_tpl[]='search.php';
    if ( is_tax()){
        $term = get_queried_object();
        $taxonomy = $term->taxonomy;
        $can_create_tpl[]="taxonomy-$taxonomy-{$term->slug}.php";
        $can_create_tpl[]="taxonomy-$taxonomy.php";
        $can_create_tpl[]="taxonomy.php";
    }
    if ( is_front_page()) $can_create_tpl[]='front-page.php';
    if(is_home())  $can_create_tpl[]='home.php';
    if ( is_attachment() ) {
        global $posts;
        $type = explode('/', $posts[0]->post_mime_type);
        $can_create_tpl[]="$type[0]}.php";
        $can_create_tpl[]="{$type[1]}.php";
        $can_create_tpl[]="{$type[0]}_{$type[1]}.php";
        $can_create_tpl[]="attachment.php";
    }
    if ( is_single()){ 
        $object = get_queried_object();
        $can_create_tpl[]="single-{$object->post_type}.php";
        $can_create_tpl[]="single.php";
    }
    if ( is_page()) { 
        $id = get_queried_object_id();
        $post = get_post( $post_id );
        $template = get_post_meta( $post->ID, '_wp_page_template', true );
        if($template && 'default' != $template ){
            $can_create_tpl[]=basename($template);
        }
        $pagename = get_query_var('pagename');
        if($pagename)   $can_create_tpl[]="page-{$pagename}.php";
        $can_create_tpl[]=" page-{$id}.php";
        $can_create_tpl[]="page.php";
    }
    if ( is_category()){ 
        $category = get_queried_object();
        $can_create_tpl[]="category-{$category->slug}.php";
        $can_create_tpl[]="category-{$category->term_id}.php";
        $can_create_tpl[]="category.php";
    }
    if ( is_tag()){
        $tag = get_queried_object();
        $can_create_tpl[]="tag-{$tag->slug}.php";
        $can_create_tpl[]="tag-{$tag->term_id}.php";
        $can_create_tpl[]="tag.php";
    }
    if ( is_author()){ 
        $author = get_queried_object();
        $can_create_tpl[]="author-{$author->user_nicename}.php";
        $can_create_tpl[]="author-{$author->ID}.php";
        $can_create_tpl[]="author.php";
    }
    elseif ( is_date() )  $can_create_tpl[]='date.php';
    elseif ( is_archive() ) {
        $post_type = get_query_var( 'post_type' );
         if ( $post_type )
             $can_create_tpl[]= "archive-{$post_type}.php or ";
        $can_create_tpl[] = 'archive.php';
    }
    if (is_comments_popup()) $can_create_tpl[]='comments-popup.php';
    if ( is_paged() ) $can_create_tpl[]='paged.php';
    $can_create_tpl[]="index.php";
    ?>
<div id="think_page_trace" style="background:white;margin:6px auto;font-size:14px;border:1px dashed silver;padding:8px; width: 940px;">
<fieldset id="querybox" style="margin:5px;">
<legend style="color:gray;font-weight:bold">调试信息</legend>
<div>
   当前使用模板：<?php echo $GLOBALS['gk_template'] ;?><br />
        可建立模版： <?php echo  implode(',', $can_create_tpl)?>
        <?php 
        if(isset($GLOBALS['gk_error'])){
            echo '<br />';
            //显示错误
            foreach($GLOBALS['gk_error'] as $error){
                switch ($error[0]) {
                    case E_USER_ERROR:
                        echo "<b>ERROR</b> [{$error[0]}] $error[1]<br />\n";
                        echo "  Fatal error on line {$error[3]} in file {$error[2]}";
                        echo ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
                        echo "Aborting...<br />\n";
                        exit(1);
                        break;

                    case E_USER_WARNING:
                        echo "<b>USER WARNING</b> [{$error[0]}] {$error[1]} Fatal error on line {$error[3]} in file {$error[2]}<br />\n";
                        break;

                    case E_USER_NOTICE:
                        echo "<b>USER NOTICE</b> [{$error[0]}] {$error[1]} Fatal error on line {$error[3]} in file {$error[2]}<br />\n";
                        break;

                    default:
                        echo "<b>NOTICE</b>  [{$error[0]}] {$error[1]} Fatal error on line {$error[3]} in file {$error[2]}<br />\n";
                        break;
                    }
            }
        }
        ?>
</div>
</fieldset>
</div>
    <?php
}