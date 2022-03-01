<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Third_Urban
 * @since 1.0
 * @version 1.0
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		<?php twentyseventeen_edit_link( get_the_ID() ); ?>
	</header><!-- .entry-header -->
	<div class="entry-content">
		<?php
			the_content();

			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'twentyseventeen' ),
				'after'  => '</div>',
			) );
		?>
    <?php 	
      $mypages = get_pages( 
                    array( 
                      'child_of' => $post->ID, 
                      'sort_column' => 'post_date', 
                      'sort_order' => 'desc' 
                    ) 
                  );  	
      foreach( $mypages as $page ) {		 		
        $content = $page->post_content; 		
        if (!$content) {
          // Check for empty page continue; 
          $content = apply_filters( 'the_content', $content );
        }
        else {
          ?><strong> No subpages </strong><?php
        }
    ?> 		
        <h2>
          <a href="<?php echo get_page_link( $page->ID ); ?>">
            <?php echo $page->post_title; ?>
          </a>
        </h2>
     		<div class="entry"><?php echo $content; ?></div> 	
    <?php }	?>

	</div><!-- .entry-content -->
</article><!-- #post-## -->
