<?php

function cptui_register_my_cpts_hotel() {

	/**
	 * Post Type: Hotels.
	 */

	$labels = [
		"name" => esc_html__( "Hotels", "hello-elementor-child" ),
		"singular_name" => esc_html__( "Hotel", "hello-elementor-child" ),
	];

	$args = [
		"label" => esc_html__( "Hotels", "hello-elementor-child" ),
		"labels" => $labels,
		"description" => "",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => true,
		"rest_base" => "",
		"rest_controller_class" => "WP_REST_Posts_Controller",
		"rest_namespace" => "wp/v2",
		"has_archive" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"delete_with_user" => false,
		"exclude_from_search" => false,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => true,
		"can_export" => false,
		"rewrite" => [ "slug" => "hotel", "with_front" => true ],
		"query_var" => true,
		"supports" => [ "title", "editor", "thumbnail", "excerpt", "page-attributes" ],
		"taxonomies" => [ "recommend" ],
		"show_in_graphql" => false,
	];

	register_post_type( "hotel", $args );
}

add_action( 'init', 'cptui_register_my_cpts_hotel' );




function cptui_register_my_taxes_country() {

	/**
	 * Taxonomy: Zones.
	 */

	$labels = [
		"name" => esc_html__( "Zones", "hello-elementor-child" ),
		"singular_name" => esc_html__( "Zone", "hello-elementor-child" ),
	];

	
	$args = [
		"label" => esc_html__( "Zones", "hello-elementor-child" ),
		"labels" => $labels,
		"public" => true,
		"publicly_queryable" => true,
		"hierarchical" => true,
		"show_ui" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"query_var" => true,
		"rewrite" => [ 'slug' => 'destination', 'with_front' => true,  'hierarchical' => true, ],
		"show_admin_column" => true,
		"show_in_rest" => true,
		"show_tagcloud" => true,
		"rest_base" => "country",
		"rest_controller_class" => "WP_REST_Terms_Controller",
		"rest_namespace" => "wp/v2",
		"show_in_quick_edit" => true,
		"sort" => true,
		"show_in_graphql" => false,
	];
	register_taxonomy( "country", [ "zone" ], $args );
}
add_action( 'init', 'cptui_register_my_taxes_country' );



function cptui_register_my_taxes_recommend() {

	/**
	 * Taxonomy: Recommend.
	 */

	$labels = [
		"name" => esc_html__( "Recommend", "hello-elementor-child" ),
		"singular_name" => esc_html__( "Recommend", "hello-elementor-child" ),
	];

	
	$args = [
		"label" => esc_html__( "Recommend", "hello-elementor-child" ),
		"labels" => $labels,
		"public" => true,
		"publicly_queryable" => true,
		"hierarchical" => true,
		"show_ui" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"query_var" => true,
		"rewrite" => [ 'slug' => 'recommend', 'with_front' => true, ],
		"show_admin_column" => true,
		"show_in_rest" => true,
		"show_tagcloud" => false,
		"rest_base" => "recommend",
		"rest_controller_class" => "WP_REST_Terms_Controller",
		"rest_namespace" => "wp/v2",
		"show_in_quick_edit" => false,
		"sort" => true,
		"show_in_graphql" => false,
	];
	register_taxonomy( "recommend", [ "hotel" ], $args );
}
add_action( 'init', 'cptui_register_my_taxes_recommend' );



function cptui_register_my_taxes_country() {

	/**
	 * Taxonomy: Zones.
	 */

	$labels = [
		"name" => esc_html__( "Zones", "hello-elementor-child" ),
		"singular_name" => esc_html__( "Zone", "hello-elementor-child" ),
	];

	
	$args = [
		"label" => esc_html__( "Zones", "hello-elementor-child" ),
		"labels" => $labels,
		"public" => true,
		"publicly_queryable" => true,
		"hierarchical" => true,
		"show_ui" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"query_var" => true,
		"rewrite" => [ 'slug' => 'destination', 'with_front' => true,  'hierarchical' => true, ],
		"show_admin_column" => true,
		"show_in_rest" => true,
		"show_tagcloud" => true,
		"rest_base" => "country",
		"rest_controller_class" => "WP_REST_Terms_Controller",
		"rest_namespace" => "wp/v2",
		"show_in_quick_edit" => true,
		"sort" => true,
		"show_in_graphql" => false,
	];
	register_taxonomy( "country", [ "zone" ], $args );
}
add_action( 'init', 'cptui_register_my_taxes_country' );




function cptui_register_my_taxes_facilities_services() {

	/**
	 * Taxonomy: Facilities & Services.
	 */

	$labels = [
		"name" => esc_html__( "Facilities & Services", "hello-elementor-child" ),
		"singular_name" => esc_html__( "Facility & Service", "hello-elementor-child" ),
	];

	
	$args = [
		"label" => esc_html__( "Facilities & Services", "hello-elementor-child" ),
		"labels" => $labels,
		"public" => true,
		"publicly_queryable" => true,
		"hierarchical" => true,
		"show_ui" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"query_var" => true,
		"rewrite" => [ 'slug' => 'facilities-services', 'with_front' => true,  'hierarchical' => true, ],
		"show_admin_column" => true,
		"show_in_rest" => true,
		"show_tagcloud" => true,
		"rest_base" => "facilities-services",
		"rest_controller_class" => "WP_REST_Terms_Controller",
		"rest_namespace" => "wp/v2",
		"show_in_quick_edit" => true,
		"sort" => true,
		"show_in_graphql" => false,
	];
	register_taxonomy( "facilities-services", [ "hotel" ], $args );
}
add_action( 'init', 'cptui_register_my_taxes_facilities_services' );



function cptui_register_my_taxes_zones() {

	/**
	 * Taxonomy: Zones.
	 */

	$labels = [
		"name" => esc_html__( "Zones", "hello-elementor-child" ),
		"singular_name" => esc_html__( "Zone", "hello-elementor-child" ),
	];

	
	$args = [
		"label" => esc_html__( "Zones", "hello-elementor-child" ),
		"labels" => $labels,
		"public" => true,
		"publicly_queryable" => true,
		"hierarchical" => true,
		"show_ui" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"query_var" => true,
		"rewrite" => [ 'slug' => 'zones', 'with_front' => true,  'hierarchical' => true, ],
		"show_admin_column" => true,
		"show_in_rest" => true,
		"show_tagcloud" => true,
		"rest_base" => "zones",
		"rest_controller_class" => "WP_REST_Terms_Controller",
		"rest_namespace" => "wp/v2",
		"show_in_quick_edit" => true,
		"sort" => true,
		"show_in_graphql" => false,
	];
	register_taxonomy( "zones", [ "hotel" ], $args );
}
add_action( 'init', 'cptui_register_my_taxes_zones' );



function cptui_register_my_taxes_other_hotels() {

	/**
	 * Taxonomy: Other hotels.
	 */

	$labels = [
		"name" => esc_html__( "Other hotels", "hello-elementor-child" ),
		"singular_name" => esc_html__( "Other hotel", "hello-elementor-child" ),
	];

	
	$args = [
		"label" => esc_html__( "Other hotels", "hello-elementor-child" ),
		"labels" => $labels,
		"public" => true,
		"publicly_queryable" => true,
		"hierarchical" => true,
		"show_ui" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"query_var" => true,
		"rewrite" => [ 'slug' => 'other_hotels', 'with_front' => true,  'hierarchical' => true, ],
		"show_admin_column" => true,
		"show_in_rest" => true,
		"show_tagcloud" => true,
		"rest_base" => "other_hotels",
		"rest_controller_class" => "WP_REST_Terms_Controller",
		"rest_namespace" => "wp/v2",
		"show_in_quick_edit" => true,
		"sort" => true,
		"show_in_graphql" => false,
	];
	register_taxonomy( "other_hotels", [ "hotel" ], $args );
}
add_action( 'init', 'cptui_register_my_taxes_other_hotels' );
