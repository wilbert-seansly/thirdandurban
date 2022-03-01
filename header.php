<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage Third_Urban
 * @since 1.0
 * @version 1.0
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js no-svg">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link href="https://fonts.googleapis.com/css?family=Raleway:300,400,500,500i,600,700" rel="stylesheet">
	<script src="https://use.fontawesome.com/65f54c6c6d.js"></script>
	<?php wp_head(); ?>

	<meta itemprop="name" content="Third & Urban" />
	<meta itemprop="image" content="https://thirdandurban.com/wp-content/themes/thirdurban/assets/images/fb.jpg" />
	<meta itemprop="description" content="We are developers building community— modern urban infill that anchors people not just city blocks; that retains context, history and experience, not just tenants; and that creates culture and connection, not just ROI." />

	<meta name="description" content="We are developers building community— modern urban infill that anchors people not just city blocks; that retains context, history and experience, not just tenants; and that creates culture and connection, not just ROI." />
	<meta name="author" content="Third & Urban LLC" />

	<meta property="og:title" content="Third & Urban" />
	<meta property="og:type" content="website" />
	<meta property="og:description" content="We are developers building community— modern urban infill that anchors people not just city blocks; that retains context, history and experience, not just tenants; and that creates culture and connection, not just ROI." />
	<meta property="og:image" content="https://thirdandurban.com/wp-content/themes/thirdurban/assets/images/fb.jpg" />
	<meta property="og:url" content="<?php echo home_url(); ?>" />
	<meta property="og:site_name" content="Third & Urban" />

	<meta name="twitter:card" content="summary">
	<meta name="twitter:title" content="Third & Urban">
	<meta name="twitter:description" content="We are developers building community— modern urban infill that anchors people not just city blocks; that retains context, history and experience, not just tenants; and that creates culture and connection, not just ROI.">
	<meta name="twitter:image" content="https://thirdandurban.com/wp-content/themes/thirdurban/assets/images/fb.jpg">
	<meta name="twitter:url" content="<?php echo home_url(); ?>">
</head>

<body <?php body_class(); ?>>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'thirdurban' ); ?></a>

	<header id="masthead" class="site-header" role="banner">
    <div class="site-header__cover"></div>
    <?php
      $siteHeaderImage = get_header_image();
      if ($siteHeaderImage) {
        $siteHeaderImage = 'url(' . $siteHeaderImage . ')';
      }
    ?>

  	<?php get_template_part( 'template-parts/header/site', 'branding' ); ?>


		<?php if ( has_nav_menu( 'top' ) ) : ?>
			<div class="navigation-top">
				<div class="wrap">
					<?php get_template_part( 'template-parts/navigation/navigation', 'top' ); ?>
				</div><!-- .wrap -->
			</div><!-- .navigation-top -->
		<?php endif; ?>

	</header><!-- #masthead -->

  <div class="site-header__stalker">
    <div class="wrap">
      <div class="site-header__stalker__logo"><a href="<?php echo home_url(); ?>"><?php echo sm_get_svg( 'logo-long' ); ?></a></div>
		<div class="site-header__stalker__menu toggle-menu toggle-menu--open"><i class="navicon"></i><span class="screen-reader-text">Toggle Mobile Menu</span></div>
    </div>
  </div>

	<?php
	// If a regular post or page, and not the front page, show the featured image.
	if ( has_post_thumbnail() && ( is_single() || ( is_page() && ! twentyseventeen_is_frontpage() ) ) ) {
		echo '<div class="single-featured-image-header">';
		the_post_thumbnail( 'twentyseventeen-featured-image' );
		echo '</div><!-- .single-featured-image-header -->';
	}
	?>

	<div class="site-content-contain">
		<div id="content" class="site-content">

<?php if (!is_front_page()) { ?>
	<div class="page-header"></div>
<?php } ?>
