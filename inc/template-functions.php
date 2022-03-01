<?php
/**
 * Additional features to allow styling of the templates
 *
 * @package WordPress
 * @subpackage Third_Urban
 * @since 1.0
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function twentyseventeen_body_classes( $classes ) {
	// Add class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	// Add class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Add class if we're viewing the Customizer for easier styling of theme options.
	if ( is_customize_preview() ) {
		$classes[] = 'twentyseventeen-customizer';
	}

	// Add class on front page.
	if ( is_front_page() && 'posts' !== get_option( 'show_on_front' ) ) {
		$classes[] = 'twentyseventeen-front-page';
	}

	// Add a class if there is a custom header.
	if ( has_header_image() ) {
		$classes[] = 'has-header-image';
	}

	// Add class if sidebar is used.
	if ( is_active_sidebar( 'sidebar-1' ) && ! is_page() ) {
		$classes[] = 'has-sidebar';
	}

	// Add class for one or two column page layouts.
	if ( is_page() || is_archive() ) {
		if ( 'one-column' === get_theme_mod( 'page_layout' ) ) {
			$classes[] = 'page-one-column';
		} else {
			$classes[] = 'page-two-column';
		}
	}

	// Add class if the site title and tagline is hidden.
	if ( 'blank' === get_header_textcolor() ) {
		$classes[] = 'title-tagline-hidden';
	}

	// Get the colorscheme or the default if there isn't one.
	$colors = twentyseventeen_sanitize_colorscheme( get_theme_mod( 'colorscheme', 'light' ) );
	$classes[] = 'colors-' . $colors;

	return $classes;
}
add_filter( 'body_class', 'twentyseventeen_body_classes' );

/**
 * Count our number of active panels.
 *
 * Primarily used to see if we have any panels active, duh.
 */
function twentyseventeen_panel_count() {

	$panel_count = 0;

	/**
	 * Filter number of front page sections in Twenty Seventeen.
	 *
	 * @since Twenty Seventeen 1.0
	 *
	 * @param $num_sections integer
	 */
	$num_sections = apply_filters( 'twentyseventeen_front_page_sections', 4 );

	// Create a setting and control for each of the sections available in the theme.
	for ( $i = 1; $i < ( 1 + $num_sections ); $i++ ) {
		if ( get_theme_mod( 'panel_' . $i ) ) {
			$panel_count++;
		}
	}

	return $panel_count;
}

/**
 * Checks to see if we're on the homepage or not.
 */
function twentyseventeen_is_frontpage() {
	return ( is_front_page() && ! is_home() );
}


function output_grid_item($page, $id, $i, $animated = true, $extra_classes = '', $href_disable_output = '') {
	$content = $page->post_content;
	if (!$content) {
		$content = apply_filters( 'the_content', $content );
	}

	$href = '#'.$id.'_item_'.sanitize_title(get_the_title($page->ID));

	$thumbnail = get_the_post_thumbnail_url( $page->ID, 'shot' );
	echo '<div class="post-subpage project '.($animated ? 'animate' : '').' fadeInUp '.$extra_classes.'" data-wow-delay="'.($i*.12).'s">';
		echo '<a href="'.$href.'" class="lightbox-w-slider"></a>';
		if ($thumbnail) {
			echo '<div class="subpage-image" style="background-image:url('.$thumbnail.');">'.get_the_post_thumbnail( $page->ID, 'shot' ).'</div>';
		}
		echo '<h3 class="subpage-title">'.get_the_title($page->ID).(get_field('location', $page->ID) ? '<br /><small>'.get_field('location', $page->ID).'</small>' : '').'</h3>';

		if (!$href_disable_output) {
			echo '<div id="'.$id.'_item_'.sanitize_title(get_the_title($page->ID)).'" class="subpage-content mfp-hide '.$id.'">';
				$gallery = get_field('gallery', $page->ID);
				if ($thumbnail || $gallery) {
					echo '<div class="lightbox-slider owl-carousel">';
						echo '<div class="image" style="background-image:url('.$thumbnail.');"></div>';
						if ($gallery) {
							foreach ($gallery as $gimage) {
								echo '<div class="image" style="background-image:url('.$gimage['sizes']['shot'].');"></div>';
							}
						}
					echo '</div>';
				}
				echo '<div class="content">';
					echo '<h3 class="subpage-title">'.get_the_title($page->ID).'</h3>';
					echo $content;
					if (get_field('project_link', $page->ID)) {
						echo '<a href="'.esc_url(get_field('project_link', $page->ID)).'">Vist '.get_the_title($page->ID).' site</a>';
					}
				echo '</div>';
			echo '</div>';
		}

	echo '</div>';
}
