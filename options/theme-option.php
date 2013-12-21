<?php
$themename = "Dchaser";
if('true' === $_REQUEST['settings-updated'] ){
      echo '<div id="message" class="updated fade">保存成功!</div>';
}
?>

<!-- Options Form begin -->
<div class="wrap">
	<div id="icon-options-general" class="icon32"><br/></div>
	<h2><?php echo $themename; ?>主题设置</h2>
    <ul class="subsubsub" style="margin-top:15px; ">
    	<li><a href="#Dchaser_base"><strong>基本设置</strong></a> |</li>
        <li><a href="#Dchaser_seo"><strong>SEO设置</strong></a> </li>
		
     </ul>
	<form method="post" action="options.php">
		<?php 
        settings_fields($option_name);
        ?>
		<table class="form-table">
			<tr valign="top">
            	<td><h3 id="Dchaser_base">基本设置</h3></td>
        	</tr>
			<tr valign="top">
                <th scope="row"><label>首页布局</label></th>
                <td>
				    <input name="Dchaser-homeType" type="radio" value="1"  checked  disabled />企业主题&nbsp;&nbsp;
                    <input name="Dchaser-homeType" type="radio" value="2"  disabled />CMS&nbsp;&nbsp;
                    <input name="Dchaser-homeType" type="radio" value="3"  disabled />个人博客&nbsp;&nbsp;
                  <br />
                    <span class="description">选择首页布局样式</span>
              </td>
        	</tr>
            
            <tr valign="top">
                <th scope="row"><label>网站底部更多链接地址</label></th>
                <td>
                    <textarea style="width:35em; height:2em;" name="Dchaser_links"><?php echo get_option("Dchaser_links"); ?></textarea>
                    <br />
                    <span class="description">输入Footer"更多友情链接"地址</span>
                </td>
        	</tr>
            
            <tr valign="top">
                <th scope="row"><label>网站底部“关于我们”</label></th>
                <td>
                    <textarea style="width:35em; height:5em;" name="Dchaser_aboutus"><?php echo get_option("Dchaser_aboutus"); ?></textarea>
                    <br />
                    <span class="description">支持写入HTML代码</span>
                </td>
        	</tr>
            
			<tr valign="top">
                <th scope="row"><label>网站底部统计代码</label></th>
                <td>
                    <textarea style="width:35em; height:5em;" name="Dchaser_stat"><?php echo get_option("Dchaser_stat"); ?></textarea>
                    <br />
                    <span class="description">可以写入统计代码</span>
                </td>
        	</tr>
            
            <tr valign="top">
                <th scope="row"><label>网站底部备案信息</label></th>
                <td>
                    <textarea style="width:35em; height:2em;" name="Dchaser_beian"><?php echo get_option("Dchaser_beian"); ?></textarea>
                    <br />
                    <span class="description">输入网站备案号</span>
                </td>
        	</tr>
			
            <tr valign="top">
            	<td><h3 id="Dchaser_seo">SEO设置</h3></td>
        	</tr>
            <tr valign="top">
                <th scope="row"><label>网站关键词</label></th>
                <td>
                    <textarea style="width:35em; height:5em;" name="Dchaser_keywords"><?php echo get_option("Dchaser_keywords"); ?></textarea>
                    <br />
                    <span class="description">输入关键词请使用英文逗号","符号分隔</span>
                </td>
        	</tr>
            <tr valign="top">
                <th scope="row"><label>网站描述</label></th>
                <td>
                    <textarea style="width:35em; height:5em;" name="Dchaser_description"><?php echo get_option("Dchaser_description"); ?></textarea>
                    <br />
                    <span class="description">输入网站描述信息</span>
                </td>
        	</tr>
            
			
		</table>

		<p class="submit">
		<input type="submit" name="save" id="button-primary" class="button-primary" value="<?php _e('Save Changes') ?>" />
		</p>
	</form>
    <style type="text/css"> span.description{ font-style:normal;} .form-table h3{ padding:5px 10px 4px; color:#FFF; background-color:#21759b} a{
		color:#21759b}</style>
</div>

<!-- Options Form begin -->

