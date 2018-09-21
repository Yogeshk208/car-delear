<?php if (!defined('ABSPATH')) die('No direct access allowed'); ?>

<p>
	<label for="<?php echo $widget->get_field_id('title'); ?>"><?php _e('Title', 'tmm_addthis') ?>:</label>
	<input class="widefat" type="text" id="<?php echo $widget->get_field_id('title'); ?>"
		   name="<?php echo $widget->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>"/>
</p>

<h3><?php _e( 'Sharing Buttons Type', 'cardealer' ); ?></h3>

<p>
	<?php
	$checked = "";
	if ($instance['bt_type'] == $widget->get_field_name('bt_large_toolbox_above')) {
		$checked = 'checked="checked"';
	}

	?>
	<input type="radio" id="<?php echo $widget->get_field_id('bt_large_toolbox_above'); ?>"
		   name="<?php echo $widget->get_field_name('bt_type'); ?>"
		   value="<?php echo $widget->get_field_name('bt_large_toolbox_above'); ?>" <?php echo $checked; ?> />
	<label
		for="<?php echo $widget->get_field_id('bt_large_toolbox_above'); ?>"><?php _e('Large buttons', 'tmm_addthis') ?></label>
</p>

<p>
	<?php
	$checked = "";
	if ($instance['bt_type'] == $widget->get_field_name('bt_fb_tw_p1_sc_above')) {
		$checked = 'checked="checked"';
	}

	?>
	<input type="radio" id="<?php echo $widget->get_field_id('bt_fb_tw_p1_sc_above'); ?>"
		   name="<?php echo $widget->get_field_name('bt_type'); ?>"
		   value="<?php echo $widget->get_field_name('bt_fb_tw_p1_sc_above'); ?>" <?php echo $checked; ?> />
	<label
		for="<?php echo $widget->get_field_id('bt_fb_tw_p1_sc_above'); ?>"><?php _e('Middle buttons', 'tmm_addthis') ?></label>
</p>

<p>
	<?php
	$checked = "";
	if ($instance['bt_type'] == $widget->get_field_name('bt_small_toolbox_above')) {
		$checked = 'checked="checked"';
	}

	?>
	<input type="radio" id="<?php echo $widget->get_field_id('bt_small_toolbox_above'); ?>"
		   name="<?php echo $widget->get_field_name('bt_type'); ?>"
		   value="<?php echo $widget->get_field_name('bt_small_toolbox_above'); ?>" <?php echo $checked; ?> />
	<label
		for="<?php echo $widget->get_field_id('bt_small_toolbox_above'); ?>"><?php _e('Small buttons', 'tmm_addthis') ?></label>
</p>

<p>
	<?php
	$checked = "";
	if ($instance['bt_type'] == $widget->get_field_name('bt_button_above')) {
		$checked = 'checked="checked"';
	}
	?>
	<input type="radio" id="<?php echo $widget->get_field_id('bt_button_above'); ?>"
		   name="<?php echo $widget->get_field_name('bt_type'); ?>"
		   value="<?php echo $widget->get_field_name('bt_button_above'); ?>" <?php echo $checked; ?> />
	<label
		for="<?php echo $widget->get_field_id('bt_button_above'); ?>"><?php _e('One Share button', 'tmm_addthis') ?></label>
</p>

<h3><?php _e( 'Disable and Select buttons', 'cardealer' ); ?></h3>

<p>
	<?php
	$checked = "";
	if ($instance['addthis_button_facebook'] == 'true') {
		$checked = 'checked="checked"';
	}
	?>
	<input type="checkbox" id="<?php echo $widget->get_field_id('addthis_button_facebook'); ?>"
		   name="<?php echo $widget->get_field_name('addthis_button_facebook'); ?>"
		   value="true" <?php echo $checked; ?> />
	<label for="<?php echo $widget->get_field_id('addthis_button_facebook'); ?>"><?php _e('Facebook', 'tmm_addthis') ?></label>
</p>

<p>
	<?php
	$checked = "";
	if ($instance['addthis_button_tweet'] == 'true') {
		$checked = 'checked="checked"';
	}
	?>
	<input type="checkbox" id="<?php echo $widget->get_field_id('addthis_button_tweet'); ?>"
		   name="<?php echo $widget->get_field_name('addthis_button_tweet'); ?>" value="true" <?php echo $checked; ?> />
	<label for="<?php echo $widget->get_field_id('addthis_button_tweet'); ?>"><?php _e('Twitter', 'tmm_addthis') ?></label>
</p>

<p>
	<?php
	$checked = "";
	if ($instance['addthis_button_pinterest'] == 'true') {
		$checked = 'checked="checked"';
	}
	?>
	<input type="checkbox" id="<?php echo $widget->get_field_id('addthis_button_pinterest'); ?>"
		   name="<?php echo $widget->get_field_name('addthis_button_pinterest'); ?>"
		   value="true" <?php echo $checked; ?> />
	<label for="<?php echo $widget->get_field_id('addthis_button_pinterest'); ?>"><?php _e('Pinterest', 'tmm_addthis') ?></label>
</p>

<p>
	<?php
	$checked = "";
	if ($instance['addthis_button_google'] == 'true') {
		$checked = 'checked="checked"';
	}
	?>
	<input type="checkbox" id="<?php echo $widget->get_field_id('addthis_button_google'); ?>"
		   name="<?php echo $widget->get_field_name('addthis_button_google'); ?>"
		   value="true" <?php echo $checked; ?> />
	<label for="<?php echo $widget->get_field_id('addthis_button_google'); ?>"><?php _e('Google', 'tmm_addthis') ?></label>
</p>

<p>
	<?php
	$checked = "";
	if ($instance['addthis_counter'] == 'true') {
		$checked = 'checked="checked"';
	}
	?>
	<input type="checkbox" id="<?php echo $widget->get_field_id('addthis_counter'); ?>"
		   name="<?php echo $widget->get_field_name('addthis_counter'); ?>" value="true" <?php echo $checked; ?> />
	<label for="<?php echo $widget->get_field_id('addthis_counter'); ?>"><?php _e('Share', 'tmm_addthis') ?></label>
</p>