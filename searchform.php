<?php
/** searchform.php
 *
 * The template for displaying search forms
 *
 * @author      Konstantin Obenland
 * @package     The Bootstrap
 * @since       1.0.0 - 07.02.2012
 */
?>
<form method="get" id="searchform" class="form-search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <h3><?php _e( '搜索', 'gkwp' ); ?></h3>
    <div class="input-append">
        <input  id="s" class="search" value="Search" default-value="Search" type="text" name="s" placeholder="<?php esc_attr_e( 'Search', 'gkwp' ); ?>" />
        <input class="red search-btn" name="submit" id="searchsubmit" type="submit" value="<?php _e( 'Go', 'gkwp' ); ?>"/>
    </div>
</form>
<?php


/* End of file searchform.php */
/* Location: ./wp-content/themes/the-bootstrap/searchform.php */