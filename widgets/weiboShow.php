<div class="weiboshow">
    <iframe width="220" height="123" class="share_self"  frameborder="0" scrolling="no" src="http://widget.weibo.com/weiboshow/index.php?language=&width=220&height=123&fansRow=2&ptype=1&speed=0&skin=1&isTitle=1&noborder=1&isWeibo=0&isFans=0&uid=<?=$instance['uid'] ?>&verifier=<?=$instance['verifier'] ?>&colors=F5F5F5,ffffff,666666,666666,6DA336&dpc=1"></iframe>
</div>
<div class="four-widget">
    <ul id="subnavs">
        <li><a href="javascript:void(0)" class="zy_subnavs" onclick="javascript:window.external.AddFavorite('<?php bloginfo('siteurl'); ?>/','<?php bloginfo('name'); ?>')" title="<?php bloginfo('name'); ?>">
        收藏本站</a></li>
        <li><a href='javascript:void(0)' onclick="this.style.behavior='url(#default#homepage)';this.setHomePage('<?php bloginfo('siteurl'); ?>/');" class="zy_subnavs">设为首页</a></li>
        <li><a href="<?php bloginfo('siteurl'); ?>/<?=$instance['sitemap'] ?>" class="zy_subnavs" target="_blank">站点地图</a></li>
        <li><a href="<?php bloginfo('siteurl'); ?>/<?=$instance['bd_sitemap'] ?>" class="zy_subnavs" target="_blank">百度地图</a></li>
    </ul>
</div>  