<?php defined('ABSPATH') or die("No direct access allowed!"); ?>

<div class="wrap columns-2 dd-wrap">
	<div style="margin-bottom: 20px;">
		<h1><?php _e('Settings', 'spirit-events'); ?></h1>
	</div>		
    
    <div id="poststuff" class="metabox-holder has-right-sidebar">
        <div id="post-body">
            <div id="post-body-content">
				<form method="post" action="options.php">	
					<?php settings_fields( 'tssev_settings_group' ); ?>
					<div class="postbox">
						<h3 class="hndle"><?php _e( 'Google API', 'spirit-events' ); ?></h3>
						<div class="inside">
							<table class="form-table" style="max-width:500px;">
								<tr valign="top">
									<th><label for="tssev_options[ApiKey]"><?php _e('API Key', 'spirit-events'); ?>:</label></th>
									<td><input type="text" name="tssev_options[ApiKey]" value="<?php echo $tssev_options['ApiKey']; ?>" size="64" class="regular-text code"></td>
								</tr>                             
                            </table> 							
						</div>
					</div>
					<div class="postbox">
						<h3 class="hndle"><?php _e( 'Color scheme', 'spirit-events' ); ?></h3>
						<div class="inside">
							<table class="form-table" style="max-width:500px;">
								<tr valign="top">
									<!-- Button -->
									<th><label for="tssev_options[ButtonColor]"><?php _e('Button', 'spirit-events'); ?>:</label></th>
									<td><input type="text" name="tssev_options[ButtonColor]" value="<?php echo $tssev_options['ButtonColor']; ?>" size="64" class="regular-text code tssev-color-picker"></td>
								</tr>
								<tr valign="top">	
									<!-- Button hover -->
									<th><label for="tssev_options[ButtonHoverColor]"><?php _e('Button hover', 'spirit-events'); ?>:</label></th>
									<td><input type="text" name="tssev_options[ButtonHoverColor]" value="<?php echo $tssev_options['ButtonHoverColor']; ?>" size="64" class="regular-text code tssev-color-picker"></td>
								</tr>
								<tr valign="top">										
									<!-- Event item -->
									<th><label for="tssev_options[EventItemColor]"><?php _e('Event item', 'spirit-events'); ?>:</label></th>
									<td><input type="text" name="tssev_options[EventItemColor]" value="<?php echo $tssev_options['EventItemColor']; ?>" size="64" class="regular-text code tssev-color-picker"></td>
								</tr>
								<tr valign="top">										
									<!-- Live stream -->
									<th><label for="tssev_options[LiveStreamColor]"><?php _e('Live stream', 'spirit-events'); ?>:</label></th>
									<td><input type="text" name="tssev_options[LiveStreamColor]" value="<?php echo $tssev_options['LiveStreamColor']; ?>" size="64" class="regular-text code tssev-color-picker"></td>
								</tr>                             
                            </table> 							
						</div>
					</div>					
					<p class="submit">
                        <input type="submit" class="button-primary" value="<?php _e( 'Save', 'spirit-events' ); ?>" />
                    </p>
				</form>
			</div>                
        </div>
    </div>
</div>