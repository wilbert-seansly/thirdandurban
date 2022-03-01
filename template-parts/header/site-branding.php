<?php
/**
 * Displays header site branding
 *
 * @package WordPress
 * @subpackage Third_Urban
 * @since 1.0
 * @version 1.0
 */

?>
<div class="site-branding">
	<div class="wrap">

		<?php // the_custom_logo(); ?>

    <div id="svg-logo-white" class="hide--md animate fadeInLeft" data-wow-delay=".2s"><a href="<?php echo home_url(); ?>"><?php echo sm_get_svg( 'logo-long' ); ?></a></div>
    <div id="svg-logo-white--lg" class="hide show-block--md animate fadeInLeft" data-wow-delay=".2s"><a href="<?php echo home_url(); ?>"><?php echo sm_get_svg( 'logo' ); ?></a></div>
<div class="wwd_portal"><a class="button" href="https://third-and-urban.mygroundbreaker.com/login">Investor Portal</a></div>
		<div class="site-header__menu toggle-menu toggle-menu--open animate fadeInRight"  data-wow-delay=".4s"><i class="navicon"></i><span class="screen-reader-text">Toggle Mobile Menu</span></div>


		<div class="site-branding-text">
			<?php if ( is_front_page() ) : ?>
				<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
			<?php else : ?>
				<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
			<?php endif; ?>

			<?php $description = get_bloginfo( 'description', 'display' );
				if ( $description || is_customize_preview() ) : ?>
					<p class="site-description"><?php echo $description; ?></p>
				<?php endif; ?>
		</div><!-- .site-branding-text -->

		<?php if ( ( twentyseventeen_is_frontpage() || ( is_home() && is_front_page() ) ) && ! has_nav_menu( 'top' ) ) : ?>
		<a href="#content" class="menu-scroll-down screen-reader-text"><span class="screen-reader-text"><?php _e( 'Scroll down to content', 'twentyseventeen' ); ?></span></a>
	<?php endif; ?>

	</div><!-- .wrap -->
</div><!-- .site-branding -->
