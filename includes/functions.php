<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/*
* Create spirit events table on plugin activation
*/
function tssev_db_install() { 
    global $wpdb;
	global $tssev_db_version;
	global $tssev_table_name;

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE {$wpdb->prefix}{$tssev_table_name} (
        post_id mediumint(11) NOT NULL AUTO_INCREMENT,
        start_time int(10),
        end_time int(10),
		place  varchar(100), 
		live_stream varchar(200),
		latitude varchar(20),
        longitude varchar(20),
        PRIMARY KEY  (post_id)
     ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );

    update_option('tssev_db_version', $tssev_db_version );
}

/*
* Check if event table update is required
*/
function tssev_update_db_check() { 
    global $tssev_db_version;

    if ( get_site_option( 'tssev_db_version' ) != $tssev_db_version ) {
        tssev_db_install();
    }
}

/*
* Include events im main loop if checkbock include_in_main_loop is checked
*/
function tssev_posts_where( $where )
{
	global $wpdb;

	if (is_main_query() && is_home() &&  0 == get_query_var('paged')) {
        $where .= " OR ( {$wpdb->posts}.post_type = 'spirit-events' AND {$wpdb->posts}.post_status != 'trash' AND {$wpdb->posts}.id IN ( 
			SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = 'include_in_main_loop' AND meta_value = '1'
		)) ";
	}

	return $where;
}
add_filter( 'posts_where', 'tssev_posts_where' );

/*
* Put events with live stream, one hour prior to their beginning to the first place in the loop
*/
function tssev_top_current_live_stream($posts) {
	
	global $wpdb;
	global $tssev_table_name;

	if (is_main_query() && is_home() &&  0 == get_query_var('paged')) {

		$spirit_ls_events = $wpdb->get_results("SELECT se.post_id, se.start_time, se.live_stream FROM " . $wpdb->prefix . $tssev_table_name . " se
					JOIN " . $wpdb->prefix . "posts p ON p.ID = se.post_id 
					WHERE LENGTH(se.live_stream) > 6 AND se.start_time < (UNIX_TIMESTAMP() + (" . get_option('gmt_offset') . " * 3600) + 3600) AND se.end_time > (UNIX_TIMESTAMP() + (" . get_option('gmt_offset') . " * 3600)) AND (p.post_status = 'publish' OR p.post_status = 'inherit')
					ORDER BY se.start_time DESC");

		// Create labels displayed before and after post title
		foreach($spirit_ls_events as $event) {
			$post_event = get_post($event->post_id);

			$time_to_start = (int)(($event->start_time - current_time('timestamp')) / 60); 
			$label_before_title = "<div class='tssev-video-object'><object><a href='" . esc_url($event->live_stream) . "' target='_blank'><div class='tssev-video-label'>" . __( 'Live stream','spirit-events') . "</div></a></object></div> ";
			$label_after_title = "";

			if ($time_to_start >= 0) {
				$label_after_title = "<span class='tssev-ls-text'> (" . sprintf( _n('in %d minute', 'in %d minutes',$time_to_start,'spirit-events'), $time_to_start) . ")</span>";
				
			} 
			else {
				$label_after_title = "<span class='tssev-ls-text'> (" .  __('Live','spirit-events') . ")</span>";
			}

			$post_event->post_title = $label_before_title . $post_event->post_title . $label_after_title;
			
			//If event is already present in the main loop, remove it - to prevent duplicate
			for ($i=0; $i < count($posts); $i++) {
				if ($posts[$i]->ID == $event->post_id) unset($posts[$i]);
			}

			array_unshift($posts, $post_event);
		}	  
	}

	return $posts;
}
add_filter('the_posts', 'tssev_top_current_live_stream');


/*
* Add Google API support for events with google map 
*/
function tssev_wp_footer() {
    global $post;
    global $tssev_options;
    
    $tssev_options= get_option( 'tssev_options');    

	$spirit_event = new Spirit_Event($post->ID);	

	if (is_singular() && $spirit_event->latitude != "" && $spirit_event->longitude != "") {
?>
	   <script>
		   console.log('InitMap');  
		   function initMap() {
		   var uluru = {lat: <?PHP echo esc_attr($spirit_event->latitude); ?>, lng: <?PHP echo esc_attr($spirit_event->longitude); ?>};
		   var map = new google.maps.Map(document.getElementById('map'), {
			   zoom: 18,
			   center: uluru
		   });
		   var marker = new google.maps.Marker({
			   position: uluru,   
			   map: map
		   });
		   }
	   </script>
	   <script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo esc_attr($tssev_options['ApiKey']); ?>&callback=initMap"></script>	
<?PHP
	}
}

/*
* Set plugin colors dynamically based on user selection in the settings
*/
function tssev_load_dynamic_style() {

	global $tssev_options;
    
	$tssev_options = get_option( 'tssev_options');    
	
	$dynamic_style = "
		.single-spirit-events .btn-submit, 
		.tssev-widget .widget-button,
		#calendar .btn {
			background-color: " . esc_attr($tssev_options['ButtonColor']) .";
		}
		.single-spirit-events .btn-submit:hover,
		.tssev-widget .widget-button:hover,
		#calendar .btn:hover  {
			background-color: " . esc_attr($tssev_options['ButtonHoverColor']) .";
		}
		.tssev-widget .widget-event-box .widget-event-date,
		div#calendar ul.dates li .event-item {
			background-color: " . esc_attr($tssev_options['EventItemColor']) .";
		}
		.tssev-video-label {
			background-color: " . esc_attr($tssev_options['LiveStreamColor']) .";
		}
		.tssev-ls-text {
			color: " . esc_attr($tssev_options['LiveStreamColor']) .";
		}
	";

	wp_add_inline_style( 'spirit-events-css', $dynamic_style);
}