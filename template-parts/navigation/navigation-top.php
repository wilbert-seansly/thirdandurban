<?php
/**
 * Displays top navigation
 *
 * @package WordPress
 * @subpackage Third_Urban
 * @since 1.0
 * @version 1.2
 */

?>
<nav id="site-navigation" class="main-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Top Menu', 'twentyseventeen' ); ?>"><div class="wrap">
  <span role="button" tab-index="0" class="toggle-menu toggle-menu--close"><i class="ui-el" aria-hidden="true"></i></span>
	<?php wp_nav_menu( array(
		'theme_location' => 'top',
		'menu_id'        => 'top-menu',
	) ); ?>

	<?php if ( ( twentyseventeen_is_frontpage() || ( is_home() && is_front_page() ) ) && has_custom_header() ) : ?>
		<a href="#content" class="menu-scroll-down screen-reader-text"><span class="screen-reader-text"><?php _e( 'Scroll down to content', 'twentyseventeen' ); ?></span></a>
	<?php endif; ?>
</div></nav><!-- #site-navigation -->
