<?php
/*
* Spirit event metabox Init (Is displayed in the single event page under content in admin)
*/
function tssev_meta_box_init() {
	add_meta_box( 'tssev_settings_meta_box',__( 'Event settings','spirit-events' ),'tssev_settings_meta_box', 'spirit-events', 'normal', 'default' );
}
add_action( 'add_meta_boxes', 'tssev_meta_box_init' );


/*
* Metabox UI
*/
function tssev_settings_meta_box( $post, $box ) {
	wp_nonce_field( basename( __FILE__ ), 'tssev_nonce' );

	// Load Spirit_Event object 
	$spirit_event = new Spirit_Event($post->ID);	

	$include_in_main_loop = get_post_meta( $post->ID, 'include_in_main_loop', true );

	if (!isset($include_in_main_loop)) $include_in_main_loop = 0;
?>
		<table class="form-table">
		  <tbody>
		  <tr>
				<td><label for="displayFrontPage"><?PHP _e( 'Display event also in front page?','spirit-events' ); ?></label></td>
				<td>
					<input type='hidden' value='0' id="include_in_main_loop" name="include_in_main_loop">
					<input type="checkbox" id="include_in_main_loop" name="include_in_main_loop" value="1" <?php checked( 1, $include_in_main_loop); ?>><br>
				</td>
				<td></td>
				<td></td>
			</tr>		  
			<tr id="datePair">
				<td><label for="tssev_start_date"><?PHP _e( 'Event date & time','spirit-events' ); ?>:</label></td>
				<td>
					<label for="tssev_start_date"><?php _e( 'From', 'spirit-events' ); ?>: </label>
					<input class="date start" type="text" id="tssev_start_date" name="tssev_start_date" value="<?PHP echo esc_attr($spirit_event->start_date); ?>">
					<input class="time start tssev_time" id="tssev_start_time" name ="tssev_start_time"  type="text" autocomplete="off" data-time-format="H:i" value="<?PHP echo esc_attr($spirit_event->start_time); ?>" >
				</td>
				<td colspan="2">
					<label for="tssev_end_date"><?php _e( 'To', 'spirit-events' ); ?>: </label>
					<input class="date end" type="text" id="tssev_end_date" name="tssev_end_date" value="<?PHP echo esc_attr($spirit_event->end_date); ?>">
					<input class="time end tssev_time" id="tssev_end_time" name ="tssev_end_time" type="text" autocomplete="off" data-time-format="H:i" value="<?PHP echo esc_attr($spirit_event->end_time); ?>" > 
				</td>
			</tr>
			<tr>
				<td><label for="tssev_place"><?PHP _e( 'Event place','spirit-events' ); ?>:</label></td>
				<td><input type="text" id="tssev_place" name="tssev_place" value="<?PHP echo esc_attr($spirit_event->place); ?>" style="width:300px;"></td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td><label for="tssev_live_stream"><?PHP  _e( 'Event live stream','spirit-events' ); ?>:</label></td>
				<td><input type="text" id="tssev_live_stream" name="tssev_live_stream" value="<?PHP echo esc_url($spirit_event->live_stream); ?>" style="width:500px;"></td>
				<td></td>
				<td></td>
			</tr>			
			<tr>
				<td colspan="4"><hr><?PHP echo _e('Fields Latitude & Longitude are optional. When filled in, Google map is displayed.','spirit-events' ); ?></td>
			</tr>				
			<tr>
				<td><label for="tssev_latitude"><?PHP _e( 'Latitude','spirit-events' ); ?>:</label></td>
				<td><input type="text" id="tssev_latitude" name="tssev_latitude" value="<?PHP echo esc_attr($spirit_event->latitude); ?>" style="width:300px;"></td>
				<td><label for="tssev_longitude"><?PHP  _e( 'Longitude','spirit-events' ); ?>:</label></td>
				<td><input type="text" id="tssev_longitude" name="tssev_longitude" value="<?PHP echo esc_attr($spirit_event->longitude); ?>" style="width:300px;"></td>
			</tr>						
		  </tbody>
		</table>	
<?PHP
}

/**
 * Save metabox input 
 */
function tssev_meta_box_save( $post_id ) {
	global $wpdb;
	global $tssev_table_name;
	
    // Checks save status
    $is_autosave = wp_is_post_autosave( $post_id );
	$is_revision = wp_is_post_revision( $post_id );
	
    $is_valid_nonce =  (isset( $_POST[ 'tssev_nonce' ])  && wp_verify_nonce( $_POST[ 'tssev_nonce' ], basename( __FILE__ ) )  ? true : false);
	// Exits script depending on save status

    if ( $is_autosave || $is_revision || !$is_valid_nonce || (isset($post->post_status) && 'auto-draft' == $post->post_status) ) { 
		return;
	}

	//Validate and sanitize input data
	$start_date_time = absint(strtotime($_POST[ 'tssev_start_date' ] . " " . $_POST[ 'tssev_start_time' ]));
	$end_date_time = absint(strtotime($_POST[ 'tssev_end_date' ] . " " . $_POST[ 'tssev_end_time' ]));
	$tssev_place = sanitize_text_field($_POST[ 'tssev_place' ]);
	$tssev_live_stream = esc_url_raw($_POST[ 'tssev_live_stream' ]);
	$tssev_latitude = preg_match('/^[+\-]?(?:\d+(?:\.\d*)?|\.\d+)$/', trim($_POST[ 'tssev_latitude' ])) ? trim($_POST[ 'tssev_latitude' ]) : '';
	$tssev_longitude = preg_match('/^[+\-]?(?:\d+(?:\.\d*)?|\.\d+)$/', trim($_POST[ 'tssev_longitude' ])) ? trim($_POST[ 'tssev_longitude' ]) : '';
	
	$sql = "INSERT INTO " . $wpdb->prefix . $tssev_table_name . "(post_id,start_time,end_time,place,live_stream,latitude,longitude) VALUES(" . $post_id . "," . $start_date_time . "," . $end_date_time . ",'" . $tssev_place . "','" . $tssev_live_stream . "','" . $tssev_latitude . "','" . $tssev_longitude . "')
			ON DUPLICATE KEY UPDATE start_time=" . $start_date_time . " , end_time=" . $end_date_time . " ,place='" . $tssev_place . "',live_stream='" . $tssev_live_stream . "',latitude='" . $tssev_latitude . "',longitude='" . $tssev_longitude . "'";
	$wpdb->query($sql);	

	if ( get_post_meta( $post_id, 'include_in_main_loop', false ) ) {
        // If the custom field already has a value, update it.
        update_post_meta( $post_id, 'include_in_main_loop', $_POST['include_in_main_loop'] );
    } else {
        // If the custom field doesn't have a value, add it.
        add_post_meta( $post_id, 'include_in_main_loop', $_POST['include_in_main_loop'] );
    }
}
add_action( 'save_post', 'tssev_meta_box_save',1,2 ); 