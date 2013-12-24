<?php

return array(
    'debug'=>false,//开启调试后，以管理员身份登录能看见调试信息
    'sidebar_debug'=>false,
    'content_width'=>630,
    //设置主题支持
    'theme_support'=>array(
        'automatic-feed-links',
        'post-thumbnails',
        'post-formats'=>array( 'aside', 'link', 'gallery', 'status', 'quote', 'image' ),
        'custom-header'=>array(
                'header-text'=>false,
                'width' => apply_filters( 'gk_header_image_width', 1000 ),
                'height' => apply_filters( 'gk_header_image_height', 288 ),
                'flex-height' => true,
                'random-default' => true
        ),
        'custom-background'=>array(
            'default-color'=>'fff'
        )
    ),

    //定义缩略图
    'thumbnails'=>array(
        //'your_name'=>array(90,90,false)
    ),
    'register_nav_menus'=>array(
        'primary'=>__('主菜单','Dchaser')
    ),
    'languages'=>array(
        //'name'=>__ROOT__.'/languages'
    ),
    'register_js'=>array(
        'jquery'=>'static/js/jquery-1.8.2.min.js'
    ),
    'register_css'=>array(
        //'bootstrap'=>'static/css/bootstrap.min.css'
    ),
    //目前只会针对前台加载，后台不加载
    'css'=>array(
        'style.css'
        ),
    'js'=>array(
        'static/js/jquery-1.7.2.min.js',
        'static/js/custom.min.js',
        'static/js/jquery.plug.min.js'
    ),
    //主题选项
    'theme_options'=>array(
        'theme-option'=>array('Dchaser主题设置','Dchaser_stat','Dchaser_links','Dchaser_keywords','Dchaser_description','Dchaser_aboutus','Dchaser_beian')
    ),
    'ext_config'=>array('sidebar','widget')
);