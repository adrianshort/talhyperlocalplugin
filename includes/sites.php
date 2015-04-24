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
      'title'           => html_entity_decode( get_the_title() ),
      'permalink'       => get_permalink(),
      'url'             => $meta['url'][0],
      'feed_url'        => $meta['feed_url'][0],
      'date_created'    => get_the_date("c"),
      'date_modified'   => get_the_modified_date("c"),
      'lat'             => (float)$meta['geo_latitude'][0],
      'lon'             => (float)$meta['geo_longitude'][0],
      'radius_miles'    => (float)$meta['distance_covered_miles'][0],
      'area_covered'    => html_entity_decode( $meta['area_covered'][0] ),
      'body'            => get_the_content(),
      'country'         => html_entity_decode( array_values(get_the_terms( get_the_ID(), 'countries' ))[0]->name ),
      'council'         => html_entity_decode( array_values(get_the_terms( get_the_ID(), 'councils' ))[0]->name ),
      'platform'        => html_entity_decode( array_values(get_the_terms( get_the_ID(), 'platforms' ))[0]->name ),
      'group'           => html_entity_decode( array_values(get_the_terms( get_the_ID(), 'groups' ))[0]->name )
    );

    $sites[]= $row;
  endwhile;
else :
  echo "No posts matched the query";
endif;

if ( $_GET['format'] == 'csv' ) {
  header( "Content-Type: text/csv" );
  header('Content-Disposition: attachment; filename="localweblist.csv"');
  $fp = fopen('php://output', 'w');
  fputcsv( $fp, array(
    'title',
    'permalink',
    'url',    
    'feed_url',
    'date_created',
    'date_modified',
    'lat',
    'lon',
    'radius_miles',
    'area_covered',
    'body',
    'country',
    'council',
    'platform',
    'group'
    )
  );
  foreach ( $sites as $row ) {
    fputcsv( $fp, $row );
  }
  fclose($fp);
} else {
  header( "Content-Type: application/json" );
  echo json_encode( $sites );
}
