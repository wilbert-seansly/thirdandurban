<?php
/**
 * The front page template file
 *
 * If the user has selected a static page for their homepage, this is what will
 * appear.
 * Learn more: https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Third_Urban
 * @since 1.0
 * @version 1.0
 */

// Load random tiles for things we like
function bsc_random_tiles() {
	$tiles = array();
	if ( have_rows('things_we_like') ) {

	 // All values that we'll load with JS
	 while ( have_rows('things_we_like') ) { the_row();
		 $image_id = get_sub_field('image');
		 $image = wp_get_attachment_image_src($image_id, 'shot');
		 $title = get_sub_field('title');
		 $link = get_sub_field('link');

		 $tiles[] = array(
			 'title' => $title,
			 'link' => esc_url($link),
			 'image' => $image[0],
		 );
	 }

	}
	wp_localize_script( 'twentyseventeen-global', 'bsc_tiles', json_encode($tiles) );
}
add_action('wp_enqueue_scripts', 'bsc_random_tiles');

get_header(); ?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">

		<?php // Show the selected frontpage content.
		if ( have_posts() ) :
			while ( have_posts() ) : the_post();
				get_template_part( 'template-parts/page/content', 'front-page' );
			endwhile;
		else : // I'm not sure it's possible to have no posts when this page is shown, but WTH.
			get_template_part( 'template-parts/post/content', 'none' );
		endif;
		?>

		<?php
		/*
		 * ACF Sections
		 */

		// Wrappers
		function bsc_wrapper_start($id, $classes = '') {

			// Custom image override
			$bgimage = get_field($id.'_bg_image');
			if ($bgimage) {
				$bgimage = 'style="background-image: url('.$bgimage.');"';
			}
			if (get_field($id.'_bg_grid') == 1) {
				$classes .= ' bg-grid';
			}

			echo '<!-- '.$id.' -->';
			echo '<div id="fp-'.$id.'" class="fp-bg '.$classes.'">';
				echo '<div id="post-'.$id.'" class="section" '.$bgimage.'>';
					echo '<div class="panel-content">';
						echo '<div class="wrap">';
		}
		function bsc_wrapper_end($id) {
						echo '</div>';
					echo '</div>';
				echo '</div>';
				echo '<span id="'.$id.'" class="scroll-to"></span>';
			echo '</div>';
			echo '<!-- / '.$id.' -->';
		}

		// #SECTION: What We Do
		if (get_field('wwd_content_columns')) {
			$id = 'about-third-and-urban';
			bsc_wrapper_start($id);
				if (get_field('section_title')) {
					echo '<h3 class="content-title">'.get_field('section_title').'</h3>';
				}
				echo '<div class="entry-tabs animate fadeInUp">';
				echo '<div class="post-tabs-nav">';
				$i = 1;
				while ( have_rows('wwd_content_columns') ) { the_row();
					if (get_sub_field('title')) {
						echo '<h3 class="tab-title slash-ani slash-title'.($i == 1 ? ' active' : '').'" data-target="#'.$id.'_'.$i.'">'.get_sub_field('title').'</h3>';
						$i++;
					}
				}
				echo '</div>';
				echo '<div class="post-tabs">';
				$i = 1;
				while ( have_rows('wwd_content_columns') ) { the_row();
					echo '<div class="post-tab'.($i == 1 ? ' active' : '').'" id="'.$id.'_'.$i.'">';
						if (get_sub_field('content')) {
							echo '<div class="subpage-content">'.get_sub_field('content').'</div>';
						}
					echo '</div>';
					$i++;
				}
				echo '</div>';
				echo '</div>';
			bsc_wrapper_end($id);
		}

		// #SECTION: How We Think
		if (get_field('hwt_content_rows')) {
			$id = 'how-we-think';
			bsc_wrapper_start($id, 'centerme');

				if (get_field('hwt_introduction')) {
					echo '<div class="entry-content">';
						echo get_field('hwt_introduction');
					echo '</div>';
				}

				echo '<div class="entry-accordian">';
				$i = 1;
				while ( have_rows('hwt_content_rows') ) { the_row();
					echo '<div class="post-accordian animate fadeInUp" data-wow-delay="'.($i*.12).'s">';
						if (get_sub_field('title')) {
							echo '<h3 data-target="#'.$id.'_'.$i.'" class="acc-title slash-ani slash-title'.($i == 1 ? ' active' : '').'">'.get_sub_field('title').'</h3>';
						}
						if (get_sub_field('content')) {
							echo '<div id="'.$id.'_'.$i.'" class="acc-content'.($i == 1 ? ' active' : '').'">'.get_sub_field('content').'</div>';
						}
					echo '</div>';
					$i++;
				}
				echo '</div>';
			bsc_wrapper_end($id);
		}

		// #SECTION: Our Work
		$id = 'our-work';
		bsc_wrapper_start($id, 'centerme');

		// If user has selected a category to display
		$project_term_query = array();
		$project_term = get_field('ow_project_category');
		if ($project_term) {
			$project_term_query = array(
				'taxonomy' => 'bsc_project_category',
				'field'    => 'id',
				'terms'    => $project_term
			);
		}

			$args = array(
				'post_type' => 'bsc_project',
				'orderby' => 'menu_order',
				'order' => 'ASC',
				'posts_per_page' => -1,
				'post_status' => 'publish',
				'tax_query' => $project_term_query
			);
		  $projects = get_posts( $args );
			$num_projects = count($projects);

			if (empty($projects)) {
		      echo '<!-- Nothing Found -->';
		  } else {
				echo '<div class="entry-subpages">';
					echo '<div id="owl-carousel-4x4-nav" class="owl-nav"></div><div id="owl-carousel-4x4-dots" class="owl-dots"></div><div class="owl-carousel owl-carousel-4x4"><div class="4x4-item">';
					$i = 1;
					foreach($projects as $page) {

						output_grid_item($page, $id, $i);

						// // Groups of four per slide
						// if ($i == $num_projects) {
						// 	echo '</div>';
						// }
						// elseif ($i % 8 == 0 && !$nav_outputted) {
						// 	$nav_outputted = true;
						// 	echo '<div class="post-subpage project animate fadeInUp project-nav" data-wow-delay="'.($i*.12).'s">';
						// 		echo '<div class="subpage-image"><div class="owl-inner owl-next-inner"><i class="fa fa-angle-right"></i></div></div>';
						// 	echo '</div>';
						// 	output_grid_item($projects[0], $id, $i, false, 'project-nav-when-hidden', 'disable popup output');
						// 	$i--;
						// 	$num_projects--;
						// 	echo '</div><div class="4x4-item">';
						// 	echo '<div class="post-subpage project animate fadeInUp" data-wow-delay="'.($i*.12).'s">';
						// 		echo '<div class="subpage-image"><div class="owl-inner owl-prev-inner"><i class="fa fa-angle-left"></i></div></div>';
						// 	echo '</div>';
						// }
						// elseif ($i % 3 == 0) {
						// 	echo '</div><div class="4x4-item">';
						// }

						if ($i % 3 == 0) {
							echo '</div><div class="4x4-item">';
						}

						$i++;
					}
					if (($i - 1) % 3 != 0) {
						echo '</div>';
					}
					echo '</div>';
				echo '</div>';
			}

		bsc_wrapper_end($id);

		// #SECTION: Who We Are
		$id = 'who-we-are';
		bsc_wrapper_start($id, 'centerme');

			$args = array(
				'post_type' => 'bsc_staff',
				'orderby' => 'menu_order',
				'order' => 'ASC',
				'posts_per_page' => -1,
				'post_status' => 'publish'
			);
		  $staff = get_posts( $args );

			if (empty($staff)) {
		      echo '<!-- Nothing Found -->';
		  } else {
				echo '<div class="entry-subpages">';
				$i = 1;
				foreach($staff as $page) {
					$content = $page->post_content;
          if (!$content) {
            $content = apply_filters( 'the_content', $content );
          }

					$thumbnail = get_the_post_thumbnail_url( $page->ID, 'staff-headshot' );
					echo '<div class="post-subpage project animate fadeInUp" data-wow-delay="'.($i*.12).'s">';
						echo '<a href="#'.$id.'_item_'.$i.'" class="lightbox"></a>';
						if ($thumbnail) {
	            echo '<div class="subpage-image" style="background-image:url('.$thumbnail.');">'.get_the_post_thumbnail( $page->ID, 'staff-headshot' ).'</div>';
	          }
						echo '<h3 class="subpage-title">'.get_the_title($page->ID).'</h3>';
						echo '<div id="'.$id.'_item_'.$i.'" class="subpage-content mfp-hide '.$id.'">';
							if ($thumbnail) {
								echo '<div class="image" style="background-image:url('.$thumbnail.');"></div>';
							}
							echo '<div class="content">';
                echo '<h3 class="subpage-title">'.get_the_title($page->ID).'</h3>';
                echo $content;
              echo '</div>';
						echo '</div>';
					echo '</div>';
					$i++;
				}
				echo '</div>';
			}

		bsc_wrapper_end($id);

		// #SECTION: Things We Like
		$id = 'things-that-inspire-us';
		bsc_wrapper_start($id);
			echo '<h1>Things That Inspire Us</h1>';
			echo '<div class="site-gallery">';
			if ( have_rows('things_we_like') ) {

				// Non loaded values
				$i = 50;
				for ($j=1;$j <= 9; $j++) {
					echo '<div class="image unloadedThing animate fadeIn grid-'.$j.$alt.'" data-wow-delay="'.$i.'ms">';
						echo '<a class="block-link" href=""></a>';
						echo '<div class="title"></div>';
					echo '</div>';
					$i = $i+50;
				}
			}
			echo '</div>';
		bsc_wrapper_end($id);


		// // #SECTION: What We Like
		// $id = 'what-we-like';
		// bsc_wrapper_start($id);
		// 	$squares = array(
		// 		'place_links' => 'Places',
		// 		'goods_links' => 'Goods',
		// 		'culture_links' => 'Culture',
		// 		'food_drink_links' => 'Food & Drink',
		// 	);
		// 	echo '<div class="grid-intro"></div><div class="grids">';
		// 	foreach ($squares as $square => $label) {
		// 		if (get_field($square)) {
		// 			$classes = '';
		//
		// 				echo '<div class="grid-item '.$square.$classes.'" '.$bgimage.'><div class="grid-wrap">';
		// 				$i = 1;
		// 				while ( have_rows($square) ) { the_row();
		// 					$link = get_sub_field('link');
		// 					echo '<a target="_blank" href="'.$link['url'].'">'.$link['title'].'</a>';
		// 				}
		// 				echo '</div></div>';
		// 		}
		// 	}
		// 	echo '</div>';
		// bsc_wrapper_end($id);


		// #SECTION: News Section
		$id = 'news-and-press';
		bsc_wrapper_start($id, 'centerme');
			echo '<div class="entry-content">';

			$args = array(
				'post_type' => 'post',
				'posts_per_page' => 3,
				'post_status' => 'publish'
			);
		  $posts = get_posts( $args );

			if (empty($posts)) {
		      echo '<!-- Nothing Found -->';
		  } else {
				echo '<div class="entry-subpages entry-posts animate fadeInUp">';
					$i = 1;
					foreach($posts as $post) {
						setup_postdata($post);
						$url = esc_url(get_field('source_url', $post->ID));
						$thumbnail = get_the_post_thumbnail_url( $post->ID, 'post-thumb' );

						echo '<div class="post-subpage post-item animate fadeInUp" data-wow-delay="'.$i.'00ms">';
							if ($thumbnail) {
		            echo '<a target="_blank" href="'.$url.'"><div class="subpage-image" style="background-image:url('.$thumbnail.');">'.get_the_post_thumbnail( $post->ID, 'post-thumb' ).'</div></a>';
		          }
							echo '<div class="entry-info">';
								echo '<p class="date">'.get_the_date().'</p>';
								echo '<h3 class="subpage-title"><a target="_blank" href="'.$url.'">'.get_the_title($post->ID).'</a></h3>';
								echo wp_trim_words(get_the_excerpt(), 45, '...');
								if (get_field('source') != '') {
									echo '<p class="source"><strong>SOURCE:</strong> <a target="_blank" href="'.$url.'">'.get_field('source').'</a></p>';
								}
							echo '</div>';
						echo '</div>';
						$i++;
					}
					wp_reset_postdata();
			echo '</div>';
			}

			echo '<a href="#" class="see-more" data-offset="3"><span class="text">SEE MORE NEWS</span><br /><i class="fa fa-angle-down animate fadeInUp animated" aria-hidden="true" data-wow-duration="2s" data-wow-iteration="100" style="visibility: visible; animation-duration: 2s; animation-iteration-count: 100; animation-name: fadeInUp;"></i></a>';

			echo '</div>';
		bsc_wrapper_end('news-and-press');

		// #SECTION: Partners Section
	if (get_field('partners_headline') || get_field('partners_introduction')) {
		$id = 'partners';
		bsc_wrapper_start($id, 'centerme');
			echo '<div class="entry-content">';
				if (get_field('partners_headline')) {
					echo '<h2>'.get_field('partners_headline').'</h2>';
				}
				if (get_field('partners_introduction')) {
					echo '<div class="partners-introduction">'.get_field('partners_introduction').'</div>';
				}
				if ( have_rows('partners') ) {
					echo '<div class="partners__grid">';
			    while ( have_rows('partners') ) { the_row();
						echo '<div class="partner__logo">';
							if (get_sub_field('link')) echo '<a href="'.get_sub_field('link').'">';
								echo wp_get_attachment_image(get_sub_field('logo'), 'full');
							if (get_sub_field('link')) echo '</a>';
						echo '</div>';
			    }
					echo '</div>';
				}
			echo '</div>';
		bsc_wrapper_end($id);
	}


		// #SECTION: Contact Us
		$id = 'contact-us';
		bsc_wrapper_start($id);
			echo '<div class="entry-content">';

			$form = get_field('cu_contact_form');
			if ($form) {
				echo do_shortcode('<div class="animate fadeInDown" data-wow-delay=".35s">[contact-form-7 id="'.$form.'" title="Contact form"]</div>');
			}

			echo '<div class="site-info animate fadeInUp" data-wow-delay=".35s">';
				echo do_shortcode('[menu name="footer"]<p></p>');
				if (get_field('cu_address')) {
					echo '<div class="footer__address">'.get_field('cu_address').'</div>';
				}
				echo '<div class="footer__legal">© Third &amp; Urban. All rights reserved.</div>';
			echo '</div>';

			echo '<div class="footer__portal"><a class="button" href="https://third-and-urban.mygroundbreaker.com/login">Investor Portal</a></div>';

			echo '</div>';
		bsc_wrapper_end($id);

		?>

	</main><!-- #main -->
</div><!-- #primary -->

<?php get_footer();
