<div class="moove-activity-plugin-documentation" style="max-width: 50%;">
    <br>
    <h1>Moove Activity Plugin</h1>

    <p>This plugin adds the ability to track the content visits / updates for any kind of custom post type or page.</p>
    <p>You can enable or disable the tracking for each post type registered on your site.</p>
    <h3>The following data will be logged:</h3>
    <ol>
        <li>Date/time</li>
        <li>User name</li>
        <li>Activity (visited/updated)</li>
        <li>Client IP</li>
        <li>Client Location (by IP Address)</li>
        <li>Referrer url</li>
    </ol>
    <h3>Global Settings</h3>
    <p>Under the Global settings page found under Settings -> Moove Activity Log you can set up activity logging globally per all the defined post types in your WordPress installation. Also, you can define the time frame/period to keep the logs in the database. This feature is really handy when you want to log activity for smaller or larger periods of time, but be careful when you set a large period it can affect your server performance and database size.</p>
    <p>When you DISABLE logging for a custom post type, all your logs will be deleted from the database. You have to confirm this before it deletes everything, but be sure you want to do this before disabling logging, or export your data in CSV beforehand.</p>

    <h3>Overriding the global settings</h3>
    <p>You can override the global post type tracking settings for each post by using the Moove Activity meta box when editing a post.</p>

    <h3>Activity log</h3>
    <p>On the left admin menu, below the Dashboard menu item there is an "<a href="<?php echo home_url('/wp-admin/admin.php?page=moove-activity-log'); ?>">Activity log</a>" page, this is where you can see the log entries.</p>
    <p><strong>Features of the Activity log page include the following:</strong></p>
    <ol>
        <li>PAGINATION - load more pagination for loading log entries via Ajax.</li>
        <li>CLEARING LOGS - You have the possibility to clear log entries per post type or you can clear all log entries at once.</li>
        <li>GROUPING - Activity log entries are grouped by post type and subsequently the logs are grouped by post.</li>
    </ol>

</div>
<!-- moove-activity-plugin-documentation -->