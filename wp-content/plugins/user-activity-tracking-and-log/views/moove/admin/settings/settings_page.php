<div class="moove-donation-box-wrapper">
    <div class="moove-donation-box">
        <div class="notice-dismiss">Dismiss</div>
        <h3>Donations</h3>

        <p>
            If you enjoy using this plugin and find it useful, feel free to donate a small amount to show appreciation and help us continue improving and supporting this plugin for free. It will make our development team very happy! :)
        </p>

        <p>
            Click the 'Donate' button and you will be redirected to Paypal where you can make your donation. You don't need to have a Paypal account, you can make donation using your credit card.
        </p>

        <p>
            Many thanks.
        </p>

        <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
            <input type="hidden" name="cmd" value="_s-xclick">
            <input type="hidden" name="hosted_button_id" value="VV64TVD23Z32A">
            <input type="image" src="https://www.paypalobjects.com/en_US/GB/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal – The safer, easier way to pay online!">
            <img alt="" border="0" src="https://www.paypalobjects.com/en_GB/i/scr/pixel.gif" width="1" height="1">
        </form>
        <script>
            jQuery(document).ready(function ($) {
                $('.moove-donation-box .notice-dismiss').on('click', function () {
                    $(this).closest('.moove-donation-box-wrapper').slideUp();
                });
            });
        </script>
    </div>
</div>

<div class="wrap moove-activity-plugin-wrap">
    <h1><?php _e('Global content activity tracking', 'moove'); ?></h1>
    <?php
    if (isset($_GET['tab'])) {
        $active_tab = $_GET['tab'];
    } else {
        $active_tab = "post_type_activity";
    } // end if
    ?>
    <h2 class="nav-tab-wrapper">

        <?php do_action('moove-activity-tab-extensions', $active_tab); ?>

        <a href="?page=moove-activity&tab=plugin_documentation" class="nav-tab <?php echo $active_tab == 'plugin_documentation' ? 'nav-tab-active' : ''; ?>">
            <?php _e('Documentation', 'moove'); ?>
        </a>
    </h2>
    <div class="moove-form-container <?php echo $active_tab; ?>">
        <a href="http://mooveagency.com" target="blank" title="WordPress agency"><span class="moove-logo"></span></a>

        <?php
        $content = array(
            'tab' => $active_tab,
            'data' => $data
        );
        do_action('moove-activity-tab-content', $content);
        ?>

    </div>
    <!-- moove-form-container -->
</div>
<!-- wrap -->