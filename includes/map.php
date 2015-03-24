<?php

function talhyperlocal_map_shortcode( $id ) {
    $options = array(
      'post_type' => 'site',
      'posts_per_page' => -1, // -1 for all posts
      'post_status' => 'publish',
      'orderby' => 'title',
      'order' => 'ASC'
    );

    if ( $id ) {
      $options['p'] = $id;
    }


  $query = new WP_Query($options);

  ?>
  <div id="talmap" style="height: 400px; width: 100%; margin: 0 0 50px 0;"></div><!-- leaflet.js map -->

  <script>

<?php
  $i = 0;
  while ( $query->have_posts() ) : $query->the_post() ;
    
    $i++;

    if ( $i == 1 ) {
      if ( $id ) {
        $meta = get_post_meta( get_the_ID() );
        $centre_lat = $meta['geo_latitude'][0];
        $centre_lon = $meta['geo_longitude'][0];
        $zoom = 11;
      } else {
        $centre_lat = 54.0;
        $centre_lon = 0;
        $zoom = 5;
      }
      ?>
        var map = L.map('talmap').setView([<?php echo $centre_lat ?>, <?php echo $centre_lon ?>],<?php echo $zoom ?> );

        L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>',
      maxZoom: 18
  }).addTo(map);
  <?php

    }

    $meta = get_post_meta( get_the_ID() );
    $link = sprintf( "<a href=\'%s\'>%s</a>", get_permalink(), get_the_title() );

    echo sprintf(
      "var marker = L.marker([%f, %f]).addTo(map);\nmarker.bindPopup('%s');",
      $meta['geo_latitude'][0],
      $meta['geo_longitude'][0],
      $link
    );
    endwhile;

    if ( $id ):

echo sprintf("var circle = L.circle([%f, %f], %d, { color: 'red', fillColor: '#f03', fillOpacity: 0.2  }).addTo(map);", $meta['geo_latitude'][0], $meta['geo_longitude'][0], $meta['distance_covered_miles'][0] * 1609.344 );
endif;
?>
</script>

  <table>
  <?php

}
add_shortcode( 'talmap', 'talhyperlocal_map_shortcode' );


function talhyperlocal_enqueue()
{
    wp_register_script( 'leaflet-js', 'http://cdn.leafletjs.com/leaflet-0.7.3/leaflet.js' );
    wp_register_style( 'leaflet-css', 'http://cdn.leafletjs.com/leaflet-0.7.3/leaflet.css' );

    wp_enqueue_style( 'leaflet-css' );
    wp_enqueue_script( 'leaflet-js' );
}
add_action( 'wp_enqueue_scripts', 'talhyperlocal_enqueue' );

?>