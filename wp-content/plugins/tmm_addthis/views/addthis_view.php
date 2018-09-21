<?php
if (!defined('ABSPATH')) exit();

$buttons_type = (!empty($instance['bt_type'])) ? $instance['bt_type'] : $widget->get_field_name('bt_small_toolbox_above');
?>

<div class="widget widget_addthis">
	<?php if ($instance['title'] != '') { ?>
        <h3 class="widget-title"><?php echo $instance['title']; ?></h3>
    <?php } ?>

	<?php
	if (!empty($buttons_type)) {

        switch ($buttons_type) {
            case $widget->get_field_name('bt_large_toolbox_above') :
                ?>
                <div class="addthis_toolbox addthis_default_style addthis_32x32_style">
                    <?php if ($instance['addthis_button_facebook']==true){ ?><a class="addthis_button_facebook"></a><?php } ?>
                    <?php if ($instance['addthis_button_tweet']==true){ ?><a class="addthis_button_twitter"></a><?php } ?>
                    <?php if ($instance['addthis_button_pinterest']==true){ ?><a class="addthis_button_pinterest_share"></a><?php } ?>
                    <?php if ($instance['addthis_button_google']==true){ ?><a class="addthis_button_google"></a><?php } ?>
                    <?php if ($instance['addthis_counter']==true){ ?><a class="addthis_button_compact"></a>
                    <a class="addthis_counter addthis_bubble_style"></a> <?php } ?>
                </div>
                <?php
                break;
            case $widget->get_field_name('bt_fb_tw_p1_sc_above') :
                ?>

                <div class="addthis_toolbox addthis_default_style ">
                    <?php if ($instance['addthis_button_facebook']==true){ ?><a class="addthis_button_facebook_like " fb:like:layout="button_count"></a><?php } ?>
                    <?php if ($instance['addthis_button_tweet']==true){ ?><a class="addthis_button_tweet" style="width: 84px"></a><?php } ?>
                    <?php if ($instance['addthis_button_pinterest']==true){ ?><a class="addthis_button_pinterest_pinit"></a><?php } ?>
                    <?php if ($instance['addthis_button_google']==true){ ?><a class="addthis_button_google_plusone" g:plusone:size="medium" style="width: 65px"></a><?php } ?>
                    <?php if ($instance['addthis_counter']==true){ ?><a class="addthis_counter addthis_pill_style" style="display: block;" href="#"></a><?php } ?>
                </div>

                <?php
                break;
            case $widget->get_field_name('bt_small_toolbox_above') :
                ?>

                <div class="addthis_toolbox addthis_default_style">
                    <?php if ($instance['addthis_button_facebook']==true){ ?><a class="addthis_button_facebook at300b" title="Facebook" href="#"></a><?php } ?>
                    <?php if ($instance['addthis_button_tweet']==true){ ?><a class="addthis_button_twitter at300b" title="Tweet" href="#"></a><?php } ?>
                    <?php if ($instance['addthis_button_google']==true){ ?><a class="addthis_button_google at300b" target="_blank" href="#"></a><?php } ?>
                    <?php if ($instance['addthis_button_pinterest']==true){ ?><a class="addthis_button_pinterest_share at300b" target="_blank" title="Pinterest" href="#"></a><?php } ?>
                    <?php if ($instance['addthis_counter']==true){ ?><a class="addthis_button_compact at300m" href="#"></a>
                    <a class="addthis_counter addthis_bubble_style" style="display: block;" href="#" tabindex="1000"></a><?php } ?>
                </div>

                <?php
                break;
            case $widget->get_field_name('bt_button_above') :
                ?>
                <a class="addthis_button"></a>
                <?php
                break;
        }

	}

	wp_enqueue_script('tmm_addthis_widget');
	?>

</div>
