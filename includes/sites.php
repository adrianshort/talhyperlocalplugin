<?php
// ini_set( 'display_errors', 1 );
// error_reporting( E_ALL ^ E_NOTICE );

define( 'WP_USE_THEMES', false );
require_once( '../../../../wp-load.php' );

$options = array(
  'post_type' => 'site',
  'posts_per_page' => -1, // -1 for all posts
  'post_status' => 'publish',
  'orderby' => 'title',
  'order' => 'ASC'
);

$query = new WP_Query( $options ) or die("WP Query failed");

$sites = array();

if ( $query->have_posts() ) : 
  while ( $query->have_posts() ) : $query->the_post() ;
    $meta = get_post_meta( get_the_ID() );

    // party_affiliation
    // hyperlocal_group_id

    $row = array(
      'title'           => get_the_title(),
      'url'             => get_permalink(),
      'feed_url'        => $meta['feed_url'][0],
      'date_created'    => get_the_date("c"),
      'date_modified'   => get_the_modified_date("c"),
      'lat'             => (float)$meta['geo_latitude'][0],
      'lon'             => (float)$meta['geo_longitude'][0],
      'radius_miles'    => (float)$meta['distance_covered_miles'][0],
      'area_covered'    => $meta['area_covered'][0],
      'body'            => get_the_content(),
      'area_covered'    => $meta['area_covered'][0],
      'country'         => get_the_terms( get_the_ID(), 'countries' ),
      'council'         => get_the_terms( get_the_ID(), 'councils' ),
      'platform'        => get_the_terms( get_the_ID(), 'platforms' ),
    );

    $sites[]= $row;
  endwhile;
else :
  echo "No posts matched the query";
endif;

header( "Content-Type: application/json" );
echo json_encode( $sites );
