<?php get_header(); ?>

	<main role="main">
		<section class="single-post wer-single-event">

			<?php if (have_posts()): while (have_posts()) : the_post(); ?>

			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

				<h1>
					<?php the_title(); ?>
				</h1>

				<?php if ( has_post_thumbnail()) {  ?>
				<div class="tssev-thumbnail">
					<?php the_post_thumbnail('large'); ?>
				</div>
				<?php } ?>

				<?php $spirit_event = new Spirit_Event(get_the_ID()); ?>

				<div class="event-content" >
					<div class="single-side-content">

					<?php if ($spirit_event->longitude != "" || $spirit_event->latitude != "") { ?>			 
					
						<div class="map-wrapper side">
							<div id="map"></div>
							<div class="map-address">
								<?php echo $spirit_event->place; ?>
							</div>
						</div>
					<?php } ?>			
				
						<div class="event-attr">
							<?php if($spirit_event->start_date){ ?><div class="date"><?php echo ($spirit_event->start_date == $spirit_event->end_date ? $spirit_event->start_date : $spirit_event->start_date . " - " . $spirit_event->end_date) ?></div><?php } ?>
							<?php if($spirit_event->start_time != ""){ ?><div class="time"><?php echo $spirit_event->start_time . " - " . $spirit_event->end_time; ?></div><?php } ?>
							<?php if($spirit_event->place != ""){ ?><div class="place"><?php echo $spirit_event->place; ?></div><?php } ?>
						</div>																																																									
					
						<a class="btn btn-submit calendar widget-button" href="http://www.google.com/calendar/event?action=TEMPLATE&text=<?php the_title();?>&dates=<?php echo $spirit_event->google_start_date; ?>T<?php echo $spirit_event->google_start_time; ?>00/<?php echo $spirit_event->google_end_date; ?>T<?php echo $spirit_event->google_end_time; ?>00&location=<?php echo $spirit_event->place; ?>" target="_blank" rel="nofollow"><?php _e( 'Add to calendar', 'spirit-events' );?></a>
					</div>
				
					<?php the_content(); ?>
				
					<?php if (strlen($spirit_event->live_stream) > 6) { ?>
				
					<div class="event-live-stream">
						<p>
							<strong><?PHP _e( 'Live stream','spirit-events'); ?>: </strong> 
							<a href="<?php echo $spirit_event->live_stream; ?>" target="_blank"><?php echo $spirit_event->live_stream; ?></a>
						</p> 
						<?php echo wp_oembed_get( $spirit_event->live_stream ); ?>
					</div>
	
					<?php }	?>
				</div>
		
				<p>
					<?php _e( 'Author: ', 'spirit-events' ); the_author(); ?>
				</p>

				<?php edit_post_link(); ?>

			</article>
			
			<?php endwhile; ?>

			<?php else: ?>

			<article>
				<h1>
					<?php _e( 'Sorry, nothing to display.', 'spirit-event' ); ?>
				</h1>
			</article>

			<?php endif; ?>

		</section>
	</main>
</div>


<?php get_footer(); ?>