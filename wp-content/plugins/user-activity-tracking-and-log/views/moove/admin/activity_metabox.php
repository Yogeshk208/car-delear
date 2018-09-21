<?php
  $ma_data = $data['activity'];
?>
<div class="ma-metabox-wrapper">
    <span class="ma-global-protection">
      <strong><?php _e('Global settings','moove'); ?>:</strong>
      <i><?php echo ( intval( $data['global_setup'] ) !== 0 ) ? 'Enabled' : 'Disabled'; ?></i>
    </span>

    <div class="ma-trigger-campaign">
        <?php if (isset($ma_data['campaign_id']) && $ma_data['campaign_id'] != ''): ?>
            <?php _e('Activity Session ID','moove'); ?>: <b><?php echo $ma_data['campaign_id'] ?></b>
            <div class="uncheck" style="float: right;">
                <label for="ma-delete-campaign">
                  <?php _e('Disable logging and delete log data','moove'); ?>
                  <input type="checkbox" id="ma-delete-campaign" name="ma-delete-campaign" class="ma-checkbox" value="1">
                </label>
            </div>
        <?php else : ?>
            <label for="ma-trigger-campaign">
              <input type="checkbox" id="ma-trigger-campaign" name="ma-trigger-campaign" value="1">
              <?php _e('Create activity tracking session for this post','moove'); ?>
            </label>

        <?php endif; ?>
    </div>
    <div class="ma-log">
        <?php if (isset($ma_data['log']) && count($ma_data['log']) > 0): ?>
            <table class="ma-table wp-list-table widefat fixed striped">
               <thead>
               <tr>
                   <td><?php _e('Time','moove'); ?></td>
                   <td><?php _e('User','moove'); ?></td>
                   <td><?php _e('Activity','moove'); ?></td>
                   <td><?php _e('Client IP','moove'); ?></td>
                   <td><?php _e('Client Location','moove'); ?></td>
                   <td><?php _e('Referrer','moove'); ?></td>
               </tr>
               </thead>
               <tbody>
               <?php foreach ($ma_data['log'] as $log_entry): ?>
                   <tr>
                       <td><?php echo date('F d - Y H:i:s', $log_entry['time']) ?></td>
                       <td><?php echo $log_entry['display_name']; ?></td>
                       <td>
                         <span style="color:green;"><?php echo $log_entry['response_status']; ?></span>
                       </td>
                      <td><?php echo $log_entry['show_ip']; ?></td>
                      <td><?php echo $log_entry['city']; ?></td>
                      <td><?php echo moove_activity_get_referrer_link_by_url( $log_entry['referer'] ); ?></td>
                   </tr>
               <?php endforeach; ?>
               </tbody>
            </table>
        <?php else : ?>
            <?php _e("You don't have any entries in this log yet.","moove"); ?>
        <?php endif; ?>
    </div>
</div>
