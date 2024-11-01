<?php

/*
 * Init spirit event widget
*/
function tssev_register_widget() {
	register_widget( 'tssev_cal_widget' );
}
add_action( 'widgets_init', 'tssev_register_widget' );

/*
 * Spirit event widget class
*/
class tssev_cal_widget extends WP_Widget {
	function __construct() {
		$widget_ops = array(
			'classname' => 'spirit_events',
			'description' =>  __( 'Spirit Events','spirit-events') );
			parent::__construct( 'spirit_events', __( 'Spirit Events','spirit-events'),$widget_ops );
	}

	function form($instance) {
	// widget form in admin dashboard - empty, no controls required
	}
	function update($new_instance, $old_instance) {
	// save widget options
	}
	function widget($args, $instance) {
		global $wpdb, $tssev_table_name;

		//Load top 5 forthcoming events from now
		$results = $wpdb->get_results("SELECT se.post_id,se.start_time,se.end_time, se.place, se.live_stream FROM " . $wpdb->prefix . $tssev_table_name . " se
					JOIN " . $wpdb->prefix . "posts p ON p.ID = se.post_id 
					WHERE se.start_time >= (UNIX_TIMESTAMP() + (" . get_option('gmt_offset') . " * 3600)) AND (p.post_status = 'publish' OR p.post_status = 'inherit') 
					ORDER BY se.start_time LIMIT 5");
?>
		<section class="widget tssev-widget">	
			<header class="widget-header">
				<h2 class="widget-title"><?php _e( 'Events','spirit-events') ?></h2>	
			</header>

			<?PHP for ($i=0; $i<count($results); $i++) { ?>

			<div class="widget-event-box">	
				<?PHP
					if (isset($results[$i]))
					{
						$tssev_place=$results[$i]->place;
						$tssev_live_stream=$results[$i]->live_stream;
						$tssev_start_day=date('d',$results[$i]->start_time);
						$tssev_start_month=date('M',$results[$i]->start_time);
						$tssev_event_post = get_post($results[$i]->post_id);
						$tssev_event_link = get_post_permalink($results[$i]->post_id);  
						$tssev_start_time=date('H:i',$results[$i]->start_time);	
						$tssev_title = $tssev_event_post->post_title;		
					}
				?>	
				  <a href="<?PHP echo $tssev_event_link; ?>">		
					<div class="widget-event-date">
						<div class="widget-event-date-day"><?PHP echo esc_attr($tssev_start_day); ?></div>
						<div class="widget-event-date-month"><?PHP _e( esc_attr($tssev_start_month),'spirit-events'); ?></div>
					</div>
				  </a>
					<div class="widget-event-text">
						<a href="<?PHP echo $tssev_event_link; ?>"><div class="widget-event-text-title"><strong><?PHP echo esc_attr($tssev_start_time); ?> </strong><span class="widget-event-name"><?PHP echo esc_attr($tssev_title); ?></span><?php if(strlen($tssev_live_stream) > 10) { ?><div class="tssev-video-object"><a href="<?php echo esc_url($tssev_live_stream); ?>" target="_blank"><div class="tssev-video-label"><?PHP _e( 'Live stream','spirit-events'); ?></div></a></div><?php } ?></div></a>			
					</div>
				<?PHP
				?>	
			</div>
			
			<?PHP } ?>
			
			<a class="widget-button calendar" href="<?PHP echo get_post_type_archive_link('spirit-events');?>"><?PHP _e( 'Calendar','spirit-events'); ?></a>		
		</section>
<?PHP
	}
}
