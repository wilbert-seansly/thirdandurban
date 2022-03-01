<?php

// Register Custom Post Type "Projects"
add_action( 'init', 'bsc_add_projects', 0 );
function bsc_add_projects() {

	$labels = array(
		'name'                => _x( 'Projects', 'Post Type General Name', 'bsc' ),
		'singular_name'       => _x( 'Project', 'Post Type Singular Name', 'bsc' ),
		'menu_name'           => __( 'Projects', 'bsc' ),
		'parent_item_colon'   => __( 'Parent Project:', 'bsc' ),
		'all_items'           => __( 'All Projects', 'bsc' ),
		'view_item'           => __( 'View Project', 'bsc' ),
		'add_new_item'        => __( 'Add New Project', 'bsc' ),
		'add_new'             => __( 'Add New', 'bsc' ),
		'edit_item'           => __( 'Edit Project', 'bsc' ),
		'update_item'         => __( 'Update Project', 'bsc' ),
		'search_items'        => __( 'Search Projects', 'bsc' ),
		'not_found'           => __( 'Not found', 'bsc' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'bsc' ),
	);
	$args = array(
		'labels'              => $labels,
		'supports'            => array( 'title', 'revisions', 'editor', 'thumbnail' ),
		'hierarchical'        => false,
		'public'              => false,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => false,
		'show_in_admin_bar'   => true,
		'menu_position'       => 10,
		'can_export'          => true,
		'has_archive'         => false,
		'exclude_from_search' => true,
		'publicly_queryable'  => false,
		'capability_type'     => 'page',
		'menu_icon'   => 'dashicons-hammer'
	);

  register_taxonomy(
       'bsc_project_category',
       'bsc_project',
       array(
         'hierarchical' => true,
         'show_admin_column'=> true,
         'label' => 'Project Categories',
       )
   );

	register_post_type( 'bsc_project', $args );
}

// Register Custom Post Type "Staff"
add_action( 'init', 'bsc_add_staff', 0 );
function bsc_add_staff() {

	$labels = array(
		'name'                => _x( 'Staff', 'Post Type General Name', 'bsc' ),
		'singular_name'       => _x( 'Staff', 'Post Type Singular Name', 'bsc' ),
		'menu_name'           => __( 'T&U Team', 'bsc' ),
		'parent_item_colon'   => __( 'Parent Staff:', 'bsc' ),
		'all_items'           => __( 'All Staff', 'bsc' ),
		'view_item'           => __( 'View Staff', 'bsc' ),
		'add_new_item'        => __( 'Add New Staff', 'bsc' ),
		'add_new'             => __( 'Add New', 'bsc' ),
		'edit_item'           => __( 'Edit Staff', 'bsc' ),
		'update_item'         => __( 'Update Staff', 'bsc' ),
		'search_items'        => __( 'Search Staff', 'bsc' ),
		'not_found'           => __( 'Not found', 'bsc' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'bsc' ),
	);
	$args = array(
		'labels'              => $labels,
		'supports'            => array( 'title', 'revisions', 'editor', 'thumbnail' ),
		'hierarchical'        => false,
		'public'              => false,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => false,
		'show_in_admin_bar'   => true,
		'menu_position'       => 10,
		'can_export'          => true,
		'has_archive'         => false,
		'exclude_from_search' => true,
		'publicly_queryable'  => false,
		'capability_type'     => 'page',
		'menu_icon'   => 'dashicons-businessman'
	);

	register_post_type( 'bsc_staff', $args );
}

// Change name of featured image in different CPTs
add_filter( 'admin_post_thumbnail_html', 'change_featured_image_text' );
function change_featured_image_text( $content ) {
	if (get_post_type() == 'bsc_staff') {
    return $content = str_replace( __( 'featured image' ), __( 'staff headshot' ), $content);
	}
	if (get_post_type() == 'bsc_project') {
    return $content = str_replace( __( 'featured image' ), __( 'project image' ), $content);
	}
	return $content;
}
add_action('do_meta_boxes', 'replace_featured_image_box');
function replace_featured_image_box()   {
    remove_meta_box( 'postimagediv', 'bsc_staff', 'side' );
    add_meta_box('postimagediv', __('Staff Image'), 'post_thumbnail_meta_box', 'bsc_staff', 'side', 'low');
		remove_meta_box( 'postimagediv', 'bsc_project', 'side' );
    add_meta_box('postimagediv', __('Project Image'), 'post_thumbnail_meta_box', 'bsc_project', 'side', 'low');
}
