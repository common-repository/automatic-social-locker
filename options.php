<div class="wrap">

    <?php screen_icon(); ?>

	<form action="options.php" method="post" id="<?php echo $plugin_id; ?>_options_form" name="<?php echo $plugin_id; ?>_options_form">

		<?php settings_fields($plugin_id.'_options'); ?>

		<h2>Automatic Social Lock Plugin Options &raquo; Settings</h2>

    <table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row" width="110px"><label for="automaticsociallockpo_title">Message: </label></th>
					<td>
						<input type="text" class="regular-text code" value="<?php echo get_option('automaticsociallockpo_title') == ''?'Share this page to reveal the content ':get_option('automaticsociallockpo_title'); ?>" id="automaticsociallockpo_title" name="automaticsociallockpo_title">							
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="automaticsociallockpo_showCloseButton">Show close button: </label></th>
					<td>
						<label for="automaticsociallockpo_showCloseButton">
						<?php 
							$checked = '';							
							if (get_option('automaticsociallockpo_showCloseButton')) $checked = 'checked';
						?>
						<input type="checkbox" id="automaticsociallockpo_showCloseButton" name="automaticsociallockpo_showCloseButton" <?=$checked?>> Enable						
						</label>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="automaticsociallockpo_countdown">Count down: </label></th>
					<td>
						<label for="automaticsociallockpo_countdown">
						<?php 
							$checked = '';							
							if (get_option('automaticsociallockpo_countdown')) $checked = 'checked';
						?>
							<input type="checkbox" id="automaticsociallockpo_countdown"  name="automaticsociallockpo_countdown" <?=$checked?>> Enable
						
						</label>
						<input type="text" name="automaticsociallockpo_countdown_duration" id="automaticsociallockpo_countdown_duration" value="<?php echo get_option('automaticsociallockpo_countdown_duration');?>" class="small-text">
						<label for="automaticsociallockpo_countdown_duration">Seconds</label>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="automaticsociallockpo_url">Url</label></th>
					<td>
						<input type="text" class="regular-text code" value="<?php echo get_option('automaticsociallockpo_url');?>" id="automaticsociallockpo_url" name="automaticsociallockpo_url">								
							<i>Empty to use current front page url</i>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="automaticsociallockpo_showCase">Appearance: </label></th>
					<td>
						<label for="automaticsociallockpo_page">
                            <?php
                            $checked = '';
                            if (get_option('automaticsociallockpo_page')) $checked = 'checked';
                            ?>
                            <input type="checkbox" id="automaticsociallockpo_page"  name="automaticsociallockpo_page" <?=$checked?>> Pages
                        </label>
						<label for="automaticsociallockpo_post">
						<?php 
							$checked = '';							
							if (get_option('automaticsociallockpo_post')) $checked = 'checked';
						?>
							<input type="checkbox" id="automaticsociallockpo_post"  name="automaticsociallockpo_post" <?=$checked?>> Posts
						</label>
						<label for="automaticsociallockpo_category">
						<?php 
							$checked = '';							
							if (get_option('automaticsociallockpo_category')) $checked = 'checked';
						?>
							<input type="checkbox" id="automaticsociallockpo_category"  name="automaticsociallockpo_category" <?=$checked?>> Categories
						</label>
						<label for="automaticsociallockpo_archive">
						<?php 
							$checked = '';							
							if (get_option('automaticsociallockpo_archive')) $checked = 'checked';
						?>
							<input type="checkbox" id="automaticsociallockpo_archive"  name="automaticsociallockpo_archive" <?=$checked?>> Archives
						</label> 
						<label for="automaticsociallockpo_tag">
						<?php 
							$checked = '';							
							if (get_option('automaticsociallockpo_tag')) $checked = 'checked';
						?>
							<input type="checkbox" id="automaticsociallockpo_tag"  name="automaticsociallockpo_tag" <?=$checked?>> Tags
						</label>                        
					</td>
				</tr>
			</tbody>
		</table>                 
        <p class="submit"><input type="submit" value="Save Changes" class="button button-primary" id="submit" name="submit"></p>
	</form>

</div>
