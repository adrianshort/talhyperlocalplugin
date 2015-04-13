<?php
/*
Plugin Name: Talk About Local Hyperlocal Sites Directory
Plugin URI: 
Description:
Version: 0.1.2.1
Author: Adrian Short
Author URI: https://adrianshort.org/
License: GPL v3
*/

require_once ( dirname( __FILE__ ) . '/includes/map.php' );

/**
 * Create custom post types and taxonomies
 * @return void
 */
function talhyperlocal_init() {
	// Create 'site' custom post type
	$args = array(
		'labels'              =>  array(
																'name'          =>  'Sites',
																'singular_name' =>  'Site',
																'add_new_item'	=>	'Add a New Site',
															),
		'description'         =>  'Hyperlocal website',
		'public'              =>  true,
		'show_ui'             =>  true,
		'has_archive'         =>  true,
		'show_in_menu'        =>  true,
		'exclude_from_search' =>  false,
		'capability_type'     =>  'post',
		'map_meta_cap'        =>  true,
		'hierarchical'        =>  false,
		'rewrite'             =>  array( 
																'slug'        => 'site',
																'with_front'  => true,
															),
		'query_var'           =>  true,
		'supports'            =>  array(
																'title',
																'editor',
																'custom-fields',
															),
	);
	register_post_type( 'site', $args );

	// Create taxonomy: Councils
	$args = array(
		'labels'            =>  array(
															'name'      =>  'Councils',
														),
		'hierarchical'      =>  false,
		'show_ui'           =>  true,
		'query_var'         =>  true,
		'rewrite'           =>  array(
															'slug'        =>  'councils',
															'with_front'  =>  true,
														),
		'show_admin_column' =>  true,
	);
	register_taxonomy( 'councils', array( 'site' ), $args );

	// Create taxonomy: Countries
	$args = array(
		'labels' =>             array(
															'name'      =>  'Countries',
														),
		'hierarchical'      =>  false,
		'show_ui'           =>  true,
		'query_var'         =>  true,
		'rewrite'           =>  array(
															'slug'        =>  'countries',
															'with_front'  =>  true,
														),
		'show_admin_column' =>  true,
	);
	register_taxonomy( 'countries', array( 'site' ), $args );

	// Create taxonomy: Platforms
	$args = array(
		'labels'            =>  array(
															'name'      =>  'Platforms',
														),
		'hierarchical'      =>  false,
		'show_ui'           =>  true,
		'query_var'         =>  true,
		'rewrite'           =>  array(
															'slug' =>				'platforms',
															'with_front' =>	true,
														),
		'show_admin_column' =>  true,
	);
	register_taxonomy( 'platforms', array( 'site' ), $args );
}
add_action( 'init', 'talhyperlocal_init' );
