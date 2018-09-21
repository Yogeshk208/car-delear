<?php
  do_action( 'moove-activity-top-filters' );
?>
<?php
  $logs_imported = Moove_Activity_Controller::moove_importer_check_database();
  if ( ! $logs_imported ) :
    Moove_Activity_Controller::import_log_to_database();
  endif;

  $screen_options   = get_user_meta( get_current_user_id(), 'moove_activity_screen_options', true );
  $selected_val = isset( $screen_options['moove-activity-dtf'] ) ? $screen_options['moove-activity-dtf'] : 'a';

?>
<div class="wrap moove-activity-log-report">
  <h1><?php _e( 'Activity Logs' , 'moove' ); ?></h1>
  <h3><?php _e( 'Select a post type to view your activity logs' , 'moove' ); ?></h3>
  <div id="moove-activity-message-cnt"></div>
  <!-- #moove-activity-message-cnt  -->
  <?php
    $page_url = htmlspecialchars( "//{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}", ENT_QUOTES, 'UTF-8' );
    $selected_date = 0;
    $selected_post_type = 0;
    $selected_user = -1;
    $search_term = '';
    $no_results = true;
    $user_options = get_user_meta( get_current_user_id(), 'moove_activity_screen_options', true );
    $custom_order = false;

    if ( isset( $_GET['tab'] ) ) :
      $active_tab = $_GET['tab'];
    else :
      $active_tab = "all_logs";
    endif;

    if ( isset( $_GET['clear-all-logs'] ) ) :
      Moove_Activity_Controller::moove_clear_logs();
    endif;
    $orderby = 'date';
    $order   = 'asc';
    if ( isset( $_GET['orderby'] ) && isset( $_GET['order'] ) ) :
      $custom_order = true;
      $enabled_values = array( 'time', 'title', 'posttype', 'display_name', 'response_status', 'ip_address', 'city', 'referer' );
      $orderby = sanitize_text_field( $_GET['orderby'] );
      $orderby = in_array( $orderby , $enabled_values ) ? $orderby : 'date';
      $order   = sanitize_text_field( $_GET['order'] ) === 'asc' ? 'desc' : 'asc';
    endif;

    if( isset( $_GET['clear-log'] ) ) :
      Moove_Activity_Controller::moove_clear_logs( intval( $_GET['clear-log'] ) );
    endif;

    if ( isset( $_GET['m'] ) && sanitize_text_field( $_GET['m'] ) ) :
      $page_url = remove_query_arg( 'm' );
      if ( sanitize_text_field( $_GET['m'] ) != '0' ) :
        $page_url = add_query_arg( 'm', sanitize_text_field( $_GET['m'] ), $page_url );
        $selected_date = sanitize_text_field( $_GET['m'] );
      endif;
    endif;

    if ( isset( $_GET['cat'] ) && sanitize_text_field( $_GET['cat'] ) ) :
      $page_url = remove_query_arg( 'cat' );
      if ( ! intval( $_GET['cat'] ) ) :
        $page_url = add_query_arg( 'cat', sanitize_text_field( $_GET['cat'] ), $page_url );
        $selected_post_type = sanitize_text_field( $_GET['cat'] );
      endif;
    endif;

    if ( isset( $_GET['uid'] ) ) :

      $page_url = remove_query_arg( 'uid' );
      $page_url = add_query_arg( 'uid', sanitize_text_field( $_GET['uid'] ), $page_url );
      $selected_user = sanitize_text_field( $_GET['uid'] );

    endif;

    if ( isset( $_GET['s'] ) && sanitize_text_field( $_GET['s'] ) ) :
      $page_url = remove_query_arg( 's' );
      if ( sanitize_text_field( $_GET['s'] !== '' ) ) :
        $page_url = add_query_arg( 's', sanitize_text_field( $_GET['s'] ), $page_url );
        $search_term = sanitize_text_field( $_GET['s'] );
      endif;
    endif;

  ?>
  <h2 class="nav-tab-wrapper">
    <a href="?page=moove-activity-log&tab=all_logs" class="nav-tab <?php echo $active_tab == 'all_logs' ? 'nav-tab-active' : ''; ?>">
        <?php _e( 'All logs' , 'moove' ); ?>
    </a>
    <?php
      $post_types = get_post_types( array( 'public' => true ) );
      unset( $post_types['attachment'] );
      foreach ( $post_types as $post_type ) :
        ?>
        <a href="?page=moove-activity-log&tab=<?php echo $post_type ?>" class="nav-tab <?php echo $active_tab == $post_type ? 'nav-tab-active' : ''; ?>">
          <?php echo ucfirst( $post_type ) ?>
        </a>
      <?php endforeach;?>
    <a href="?page=moove-activity-log&tab=settings" class="nav-tab <?php echo $active_tab == 'settings' ? 'nav-tab-active' : ''; ?>">
        <?php _e( 'Settings' , 'moove' ); ?>
    </a>
    <?php if ( $active_tab === 'all_logs' ) : ?>
      <p class="search-box">
        <label class="screen-reader-text" for="post-search-input"><?php _e('Search Posts','moove'); ?>:</label>
        <input type="search" id="post-search-input" name="s" value="<?php echo $search_term; ?>">
        <input type="submit" id="search-submit" class="button" value="<?php _e('Search Posts','moove'); ?>" data-pageurl="<?php echo $page_url; ?>">
      </p>
    <?php endif; ?>
  </h2>
  <div class="moove-form-container <?php echo $active_tab; ?>">
    <span class="moove-logo"></span>
    <?php
      if ( $active_tab === 'all_logs' ) :
        $query = array(
          'post_type'       =>  $post_types,
          'post_status'     =>  'publish',
          'posts_per_page'  => -1,
          'meta_query'    => array(
            'relation' => 'OR',
            array(
              'key'     => 'ma_data',
              'value'   => null,
              'compare' => '!='
            )
          )
        );
        query_posts( $query );
        if ( have_posts() ) :
          while ( have_posts() ) : the_post();
            global $post;
            Moove_Activity_Controller::moove_remove_old_logs( $post->ID );
            $activity = Moove_Activity_Database_Model::get_log( '`post_id`', $post->ID );

            if ( $activity && is_array( $activity ) ) :
              foreach ( $activity as $log ) :
                $log_array[] = array(
                  'post_id'         =>  $log->post_id,
                  'time'            =>  $log->visit_date,
                  'uid'             =>  $log->user_id,
                  'display_name'    =>  $log->display_name,
                  'ip_address'      =>  $log->user_ip,
                  'response_status' =>  $log->status,
                  'referer'         =>  $log->referer,
                  'city'            =>  $log->city
                );
              endforeach;
            endif;

          endwhile;
        endif;
        wp_reset_query();
        wp_reset_postdata();

        $log_array = isset( $log_array ) ? $log_array : array();

        $log_array = Moove_Activity_Controller::moove_get_filtered_array( $log_array, $selected_date, $selected_user, $selected_post_type, $search_term );


        $selected_date = $selected_date ? $selected_date : '';
        $date_filter_content = Moove_Activity_Controller::moove_get_activity_dates( $log_array, $selected_date );


        function moove_desc_sort( $item1 , $item2 ) {
          if ( strtotime( $item1['time'] ) == strtotime( $item2['time'] ) ) return 0;
          return ( $item1['time'] < $item2['time'] ) ? 1 : -1;
        }

        if ( $custom_order ) :
          if ( is_array( $log_array ) ) :
            usort( $log_array, array(new Moove_Activity_Array_Order( $orderby ), "custom_order" ) );
            if ( $order === 'asc' ) :
              $log_array = array_reverse( $log_array );
            endif;
          endif;
        else :
          if ( is_array( $log_array ) ) :
            usort( $log_array, 'moove_desc_sort' );
          endif;
        endif;

        function moove_activity_current_order( $type, $custom_order, $order, $orderby ) {
          $_order = 'desc';
          if ( $custom_order ) :
            switch ( $type ) {
              case 'time':
                if ( $orderby === 'time' ) :
                  if ( $order === 'asc' ) :
                    $_order = 'desc';
                  else :
                    $_order = 'asc';
                  endif;

                else :
                   $_order = 'asc';
                endif;

                break;
              case 'title':

                if ( $orderby === 'title' ) :
                  if ( $order === 'asc' ) :
                    $_order = 'desc';
                  else :
                    $_order = 'asc';
                  endif;
                else :
                   $_order = 'asc';
                endif;
                break;
              case 'posttype':
                if ( $orderby === 'posttype' ) :
                  if ( $order === 'asc' ) :
                    $_order = 'desc';
                  else :
                    $_order = 'asc';
                  endif;
                else :
                   $_order = 'asc';
                endif;
                break;

              case 'display_name':
                if ( $orderby === 'display_name' ) :
                  if ( $order === 'asc' ) :
                    $_order = 'desc';
                  else :
                    $_order = 'asc';
                  endif;
                else :
                   $_order = 'asc';
                endif;
                break;

              case 'response_status':
                if ( $orderby === 'response_status' ) :
                  if ( $order === 'asc' ) :
                    $_order = 'desc';
                  else :
                    $_order = 'asc';
                  endif;
                else :
                   $_order = 'asc';
                endif;
                break;

              case 'ip_address':
                if ( $orderby === 'ip_address' ) :
                  if ( $order === 'asc' ) :
                    $_order = 'desc';
                  else :
                    $_order = 'asc';
                  endif;
                else :
                   $_order = 'asc';
                endif;
                break;

              case 'city':
                if ( $orderby === 'city' ) :
                  if ( $order === 'asc' ) :
                    $_order = 'desc';
                  else :
                    $_order = 'asc';
                  endif;
                else :
                   $_order = 'asc';
                endif;
                break;

              case 'referer':
                if ( $orderby === 'referer' ) :
                  if ( $order === 'asc' ) :
                    $_order = 'desc';
                  else :
                    $_order = 'asc';
                  endif;
                else :
                   $_order = 'asc';
                endif;
                break;

              default:
                $_order = 'desc';
            }
          endif;
          return $_order;
        }

        $log_array_count = count( $log_array );
        $original_array = $log_array;

        $post_per_page = isset( $user_options['wp_screen_options']['value'] ) && intval( $user_options['wp_screen_options']['value'] ) ? intval( $user_options['wp_screen_options']['value'] ) : 50;
        $max_num_pages = ceil( count( $log_array ) / $post_per_page );
        if( isset( $_GET['offset'] ) ) :
          $offset = intval($_GET['offset']);
        else :
          $offset = 1;
        endif;


        $log_array = Moove_Activity_Controller::moove_pagination( $log_array, $offset, $post_per_page );
        ?>
        <div class="tablenav top">
          <div class="alignleft actions">
            <?php ob_start(); ?>
              <label for="filter-by-date" class="screen-reader-text"><?php _e('Filter by date','moove'); ?></label>

              <?php echo $date_filter_content; ?>

              <label class="screen-reader-text" for="cat"><?php _e('Filter by post type','moove'); ?></label>
              <select name="post_types" id="post_types" class="postform">
                <option value="0"><?php _e('All Post Types','moove'); ?></option>
                <?php

                $post_types = get_post_types( array( 'public' => true ) );
                unset( $post_types['attachment'] );
                foreach ( $post_types as $post_type ) :
                  $selected = '';
                  if ( $selected_post_type == $post_type && $selected_post_type !== 0 ) :
                    $selected = 'selected="selected"';
                  endif;
                  ?>
                  <option class="level-0" value="<?php echo $post_type; ?>" <?php echo $selected; ?>><?php echo ucfirst( $post_type ) ?></option>
                <?php endforeach;?>
              </select>
              <?php
                $filters = ob_get_clean();
                do_action('moove-activity-filters', $filters, $date_filter_content );
              ?>
              <input type="submit" name="filter_action" id="post-query-submit" class="button" data-pageurl="<?php echo $page_url; ?>" value="Filter">
            </div>
            <div class="tablenav-pages one-page">
              <span class="displaying-num"><?php echo $log_array_count; ?> items</span>
            </div>
            <br class="clear">
          </div>
          <!-- tablenav -->
        <table class="moove-activity-log-table wp-list-table widefat fixed striped" id="moove-activity-log-table-global">
          <?php if ( count( $log_array ) ) : $no_results = false; ?>
            <thead>
              <tr>
                <?php $page_url       = remove_query_arg( array( 'orderby', 'order' ) , false ); ?>
                <?php
                  $current_order  = moove_activity_current_order( 'time', $custom_order, $order, $orderby );
                  $reversed_order = $current_order === 'asc' ? 'desc' : 'asc';
                ?>
                <th class="manage-column column-date column-primary sortable <?php echo $current_order; ?>">
                  <a href="<?php echo add_query_arg( array('orderby' => 'time','order' => $reversed_order), $page_url ); ?>">
                    <span><?php _e('Date / Time','moove'); ?></span>
                    <span class="sorting-indicator"></span>
                  </a>
                </th>

                <?php
                  $current_order  = moove_activity_current_order( 'title', $custom_order, $order, $orderby );
                  $reversed_order = $current_order === 'asc' ? 'desc' : 'asc';
                ?>
                <th class="column-title manage-column sortable <?php echo $current_order; ?>">
                  <a href="<?php echo add_query_arg( array('orderby' => 'title','order' => $reversed_order ), $page_url ); ?>">
                    <span><?php _e('Title','moove'); ?></span>
                    <span class="sorting-indicator"></span>
                  </a>
                </th>

                <?php
                  $current_order  = moove_activity_current_order( 'posttype', $custom_order, $order, $orderby );
                  $reversed_order = $current_order === 'asc' ? 'desc' : 'asc';
                ?>
                <th class="column-posttype manage-column sortable <?php echo $current_order; ?> <?php echo ( isset( $user_options['posttype-hide'] ) || !is_array( $user_options ) ) ? '' : 'hidden'; ?>">
                  <a href="<?php echo add_query_arg( array('orderby' => 'posttype','order' => $reversed_order ), $page_url ); ?>">
                    <span><?php _e('Post type','moove'); ?></span>
                    <span class="sorting-indicator"></span>
                  </a>
                </th>

                <?php
                  $current_order  = moove_activity_current_order( 'display_name', $custom_order, $order, $orderby );
                  $reversed_order = $current_order === 'asc' ? 'desc' : 'asc';
                ?>
                <th class="column-user manage-column sortable <?php echo $current_order; ?> <?php echo ( isset( $user_options['user-hide'] ) || !is_array( $user_options ) ) ? '' : 'hidden'; ?>">
                  <a href="<?php echo add_query_arg( array('orderby' => 'display_name','order' => $reversed_order ), $page_url ); ?>">
                    <span><?php _e('User','moove'); ?></span>
                    <span class="sorting-indicator"></span>
                  </a>
                </th>

                <?php
                  $current_order  = moove_activity_current_order( 'response_status', $custom_order, $order, $orderby );
                  $reversed_order = $current_order === 'asc' ? 'desc' : 'asc';
                ?>
                <th class="column-activity manage-column sortable <?php echo $current_order; ?> <?php echo ( isset( $user_options['activity-hide'] ) || !is_array( $user_options ) ) ? '' : 'hidden'; ?>">
                  <a href="<?php echo add_query_arg( array('orderby' => 'response_status','order' => $reversed_order ), $page_url ); ?>">
                    <span><?php _e('Activity','moove'); ?></span>
                    <span class="sorting-indicator"></span>
                  </a>
                </th>

                <?php
                  $current_order  = moove_activity_current_order( 'ip_address', $custom_order, $order, $orderby );
                  $reversed_order = $current_order === 'asc' ? 'desc' : 'asc';
                ?>
                <th class="column-ip manage-column sortable <?php echo $current_order; ?> <?php echo ( isset( $user_options['ip-hide'] ) || !is_array( $user_options ) ) ? '' : 'hidden'; ?>">
                  <a href="<?php echo add_query_arg( array('orderby' => 'ip_address','order' => $reversed_order ), $page_url ); ?>">
                    <span><?php _e('Client IP','moove'); ?></span>
                    <span class="sorting-indicator"></span>
                  </a>
                </th>

                <?php
                  $current_order  = moove_activity_current_order( 'city', $custom_order, $order, $orderby );
                  $reversed_order = $current_order === 'asc' ? 'desc' : 'asc';
                ?>
                <th class="column-city manage-column sortable <?php echo $current_order; ?> <?php echo ( isset( $user_options['city-hide'] ) || !is_array( $user_options ) ) ? '' : 'hidden'; ?>">
                  <a href="<?php echo add_query_arg( array('orderby' => 'city','order' => $reversed_order ), $page_url ); ?>">
                    <span><?php _e('Client Location','moove'); ?></span>
                    <span class="sorting-indicator"></span>
                  </a>
                </th>

                <?php
                  $current_order  = moove_activity_current_order( 'referer', $custom_order, $order, $orderby );
                  $reversed_order = $current_order === 'asc' ? 'desc' : 'asc';
                ?>
                <th class="column-referrer manage-column sortable <?php echo $current_order; ?> <?php echo ( isset( $user_options['referrer-hide'] ) || !is_array( $user_options ) ) ? '' : 'hidden'; ?>">
                  <a href="<?php echo add_query_arg( array('orderby' => 'referer','order' => $reversed_order ), $page_url ); ?>">
                    <span><?php _e('Referrer','moove'); ?></span>
                    <span class="sorting-indicator"></span>
                  </a>
                </th>
              </tr>
            </thead>
          <?php else: ?>
          <thead>
            <tr>
              <th class="column-date"><?php _e('Date / Time','moove'); ?></th>
              <th class="column-title"><?php _e('Title','moove'); ?></th>
              <th class="column-posttype <?php echo ( isset( $user_options['posttype-hide'] ) || !is_array( $user_options ) ) ? '' : 'hidden'; ?>"><?php _e('Post type','moove'); ?></th>
              <th class="column-user <?php echo ( isset( $user_options['user-hide'] ) || !is_array( $user_options ) ) ? '' : 'hidden'; ?>"><?php _e('User','moove'); ?></th>
              <th class="column-activity <?php echo ( isset( $user_options['activity-hide'] ) || !is_array( $user_options ) ) ? '' : 'hidden'; ?>"><?php _e('Activity','moove'); ?></th>
              <th class="column-ip <?php echo ( isset( $user_options['ip-hide'] ) || !is_array( $user_options ) ) ? '' : 'hidden'; ?>"><?php _e('Client IP','moove'); ?></th>
              <th class="column-city <?php echo ( isset( $user_options['city-hide'] ) || !is_array( $user_options ) ) ? '' : 'hidden'; ?>"><?php _e('Client Location','moove'); ?></th>
              <th class="column-referrer <?php echo ( isset( $user_options['referrer-hide'] ) || !is_array( $user_options ) ) ? '' : 'hidden'; ?>"><?php _e('Referrer','moove'); ?></th>
            </tr>
          </thead>
          <tbody id="the-list">
            <tr class="no-items"><td class="colspanchange" colspan="7"><?php _e('No posts found.','moove'); ?></td></tr>
          </tbody>
          <?php endif; ?>
          <tbody>
          <?php
          if ( is_array( $log_array ) ) :
            foreach ( $log_array as $log_entry ) : ?>
               <tr>
                  <td class="column-date">
                    <?php echo moove_activity_convert_date( $selected_val, $log_entry['time'], $screen_options ); ?>
                  </td>
                  <td class="column-title">
                    <a href="<?php the_permalink( $log_entry['post_id'] ); ?>" target="_blank">
                      <?php echo get_the_title( $log_entry['post_id'] ); ?>
                    </a>
                  </td>
                  <td class="column-posttype <?php echo ( isset( $user_options['posttype-hide'] ) || !is_array( $user_options ) ) ? '' : 'hidden'; ?>"><?php echo get_post_type( $log_entry['post_id'] ); ?></td>
                  <td class="column-user <?php echo ( isset( $user_options['user-hide'] ) || !is_array( $user_options ) ) ? '' : 'hidden'; ?>"><?php echo $log_entry['display_name']; ?></td>
                  <td class="column-activity <?php echo ( isset( $user_options['activity-hide'] ) || !is_array( $user_options ) ) ? '' : 'hidden'; ?>">
                    <span style="color:green;"><?php echo $log_entry['response_status']; ?></span>
                  </td>
                  <td class="column-ip <?php echo ( isset( $user_options['ip-hide'] ) || !is_array( $user_options ) ) ? '' : 'hidden'; ?>"><?php echo $log_entry['ip_address']; ?></td>
                  <td class="column-city <?php echo ( isset( $user_options['city-hide'] ) || !is_array( $user_options ) ) ? '' : 'hidden'; ?>"><?php echo $log_entry['city']; ?></td>
                  <td class="column-referrer <?php echo ( isset( $user_options['referrer-hide'] ) || !is_array( $user_options ) ) ? '' : 'hidden'; ?>"><?php echo moove_activity_get_referrer_link_by_url( $log_entry['referer'] ); ?></td>
               </tr>

            <?php
            endforeach;
          endif;
          ?>
          </tbody>
          <tfoot>
            <tr>
              <th class="column-date"><?php _e('Date / Time','moove'); ?></th>
              <th class="column-title"><?php _e('Title','moove'); ?></th>
              <th class="column-posttype <?php echo ( isset( $user_options['posttype-hide'] ) || !is_array( $user_options ) ) ? '' : 'hidden'; ?>"><?php _e('Post type','moove'); ?></th>
              <th class="column-user <?php echo ( isset( $user_options['user-hide'] ) || !is_array( $user_options ) ) ? '' : 'hidden'; ?>"><?php _e('User','moove'); ?></th>
              <th class="column-activity <?php echo ( isset( $user_options['activity-hide'] ) || !is_array( $user_options ) ) ? '' : 'hidden'; ?>"><?php _e('Activity','moove'); ?></th>
              <th class="column-ip <?php echo ( isset( $user_options['ip-hide'] ) || !is_array( $user_options ) ) ? '' : 'hidden'; ?>"><?php _e('Client IP','moove'); ?></th>
              <th class="column-city <?php echo ( isset( $user_options['city-hide'] ) || !is_array( $user_options ) ) ? '' : 'hidden'; ?>"><?php _e('Client Location','moove'); ?></th>
              <th class="column-referrer <?php echo ( isset( $user_options['referrer-hide'] ) || !is_array( $user_options ) ) ? '' : 'hidden'; ?>"><?php _e('Referrer','moove'); ?></th>
            </tr>
          </tfoot>
        </table>

        <?php if ( ! $no_results ) : ?>
          <div id="moove-activity-buttons-container">
            <br>
            <?php if ( $max_num_pages != $offset ) : ?>
            <a href="<?php echo $page_url; ?>" class="button button-primary load-more" data-max="<?php echo $max_num_pages; ?>" data-offset="<?php echo $offset; ?>">
                <?php _e('Load more','moove'); ?>
              </a>
            <?php endif; ?>

            <a href="<?php echo $page_url; ?>" class="button button pullright clear-all-logs">
              <?php _e( 'Clear my logs' , 'moove' ); ?>
            </a>

            <?php do_action( 'moove-activity-extra-buttons', $page_url ); ?>
          </div>
          <!-- #moove-activity-buttons-container  -->
      <?php endif; ?>
      <?php
      elseif ( $active_tab === 'settings' ) : ?>
        <h3><?php _e( 'Settings' , 'moove' ); ?></h3>
        <p><?php _e( 'Please check the plugin settings under "<strong>Settings / Activity log</strong>" or click to the button below!' , 'moove' ); ?></p>
        <br>
        <a href="/wp-admin/options-general.php?page=moove-activity" class="button button-secondary"><?php _e( 'Settings page' , 'moove' ); ?></a>
      <?php else :
        $args = array(
          'post_type'       =>  $active_tab,
          'post_status'     =>  'publish',
          'posts_per_page'  =>  -1,
        );
        query_posts( $args );
        $log_enabled = 0;
        $page_url = htmlspecialchars( "//{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}", ENT_QUOTES, 'UTF-8' );
        ?>
        <div class="moove-accordion-cnt">
            <div class="moove-accordion">
              <div class="moove-accordion-section">
              <?php
              if ( count( $active_tab ) > 1 ):
                echo "<h2>" . ucfirst( $ptlog ) . "</h2>";
              endif;
              ?>
              <?php if ( have_posts() ) : ?>
                <?php while ( have_posts() ) : the_post();
                  global $post;
                  Moove_Activity_Controller::moove_remove_old_logs( $post->ID) ;
                  $activity = Moove_Activity_Database_Model::get_log( '`post_id`', $post->ID );

                  if ( $activity && is_array( $activity ) ) :
                    foreach ( $activity as $log ) :
                      $log_array[] = array(
                        'post_id'         =>  $log->post_id,
                        'time'            =>  $log->visit_date,
                        'uid'             =>  $log->user_id,
                        'display_name'    =>  $log->display_name,
                        'ip_address'      =>  $log->user_ip,
                        'response_status' =>  $log->status,
                        'referer'         =>  $log->referer,
                        'city'            =>  $log->city
                      );
                    endforeach;

                    $log_enabled++;
                    ?>
                    <a class="moove-accordion-section-title" href="#moove-accordion-<?php echo $post->ID; ?>">
                      <?php the_title(); ?>
                    </a>
                    <div id="moove-accordion-<?php echo $post->ID; ?>" class="moove-accordion-section-content">
                      <div class="view_post">
                        <strong>Permalink:</strong>
                        <span><a href="<?php the_permalink(); ?>"><strong><?php the_permalink(); ?></strong></a></span>
                        <br /><br />
                      </div>
                      <!--  .view_post -->
                      <table class="moove-activity-log-table-<?php echo $post->ID; ?> wp-list-table widefat fixed striped" id="moove-activity-log-table-<?php echo $post->ID; ?>">
                          <?php
                          if ( count( $log_array ) ) : ?>
                            <thead>
                              <tr>
                                <th class="column-date"><?php _e('Date / Time','moove'); ?></th>
                                <th class="column-user <?php echo ( isset( $user_options['user-hide'] ) || !is_array( $user_options ) ) ? '' : 'hidden'; ?>"><?php _e('User','moove'); ?></th>
                                <th class="column-activity <?php echo ( isset( $user_options['activity-hide'] ) || !is_array( $user_options ) ) ? '' : 'hidden'; ?>"><?php _e('Activity','moove'); ?></th>
                                <th class="column-ip <?php echo ( isset( $user_options['ip-hide'] ) || !is_array( $user_options ) ) ? '' : 'hidden'; ?>"><?php _e('Client IP','moove'); ?></th>
                                <th class="column-city <?php echo ( isset( $user_options['city-hide'] ) || !is_array( $user_options ) ) ? '' : 'hidden'; ?>"><?php _e('Client Location','moove'); ?></th>
                                <th class="column-referrer <?php echo ( isset( $user_options['referrer-hide'] ) || !is_array( $user_options ) ) ? '' : 'hidden'; ?>"><?php _e('Referrer','moove'); ?></th>
                              </tr>
                            </thead>
                            <?php
                            $post_per_page = isset( $user_options['wp_screen_options']['value'] ) && intval( $user_options['wp_screen_options']['value'] ) ? intval( $user_options['wp_screen_options']['value'] ) : 50;
                            $max_num_pages = ceil( count( $log_array ) / $post_per_page );
                            if( isset( $_GET['offset'] ) ) :
                              $offset = intval($_GET['offset']);
                            else :
                              $offset = 1;
                            endif;
                            $log_array = Moove_Activity_Controller::moove_pagination( $log_array, $offset, $post_per_page );
                            ?>
                            <tbody>
                            <?php

                            foreach ( $log_array as $key => $log_entry ) :
                            ?>
                              <tr>
                                <td class="column-date">
                                  <?php echo moove_activity_convert_date( $selected_val, $log_entry['time'], $screen_options ); ?>
                                </td>

                                <td class="column-user <?php echo ( isset( $user_options['user-hide'] ) || !is_array( $user_options ) ) ? '' : 'hidden'; ?>"><?php echo $log_entry['display_name']; ?></td>
                                <td class="column-activity <?php echo ( isset( $user_options['activity-hide'] ) || !is_array( $user_options ) ) ? '' : 'hidden'; ?>">
                                  <span style="color:green;"><?php echo $log_entry['response_status']; ?></span>
                                </td>
                                <td class="column-ip <?php echo ( isset( $user_options['ip-hide'] ) || !is_array( $user_options ) ) ? '' : 'hidden'; ?>"><?php echo $log_entry['ip_address']; ?></td>
                                <td class="column-city <?php echo ( isset( $user_options['city-hide'] ) || !is_array( $user_options ) ) ? '' : 'hidden'; ?>"><?php echo $log_entry['city']; ?></td>
                                <td class="column-referrer <?php echo ( isset( $user_options['referrer-hide'] ) || !is_array( $user_options ) ) ? '' : 'hidden'; ?>"><?php echo moove_activity_get_referrer_link_by_url( $log_entry['referer'] ); ?></td>
                             </tr>

                            <?php
                            endforeach; ?>
                            </tbody>
                          <?php else: //log is empty?>
                            <tbody id="the-list">
                              <tr class="no-items"><td class="colspanchange" colspan="7"><?php _e('No posts found.','moove'); ?></td></tr>
                            </tbody>
                          <?php endif; //log is not empty?>
                          <tfoot>
                            <tr>
                              <th class="column-date"><?php _e('Date / Time','moove'); ?></th>
                              <th class="column-user <?php echo ( isset( $user_options['user-hide'] ) || !is_array( $user_options ) ) ? '' : 'hidden'; ?>"><?php _e('User','moove'); ?></th>
                              <th class="column-activity <?php echo ( isset( $user_options['activity-hide'] ) || !is_array( $user_options ) ) ? '' : 'hidden'; ?>"><?php _e('Activity','moove'); ?></th>
                              <th class="column-ip <?php echo ( isset( $user_options['ip-hide'] ) || !is_array( $user_options ) ) ? '' : 'hidden'; ?>"><?php _e('Client IP','moove'); ?></th>
                              <th class="column-city <?php echo ( isset( $user_options['city-hide'] ) || !is_array( $user_options ) ) ? '' : 'hidden'; ?>"><?php _e('Client Location','moove'); ?></th>
                              <th class="column-referrer <?php echo ( isset( $user_options['referrer-hide'] ) || !is_array( $user_options ) ) ? '' : 'hidden'; ?>"><?php _e('Referrer','moove'); ?></th>
                            </tr>
                          </tfoot>
                        </table>
                        <br>
                        <?php if ( $max_num_pages != $offset ) : ?>
                          <a href="<?php echo $page_url; ?>" class="button button-primary load-more" data-max="<?php echo $max_num_pages; ?>" data-offset="<?php echo $offset; ?>"><?php _e( 'Load more' , 'moove' ); ?></a>
                        <?php endif; ?>
                        <a href="<?php echo $page_url; ?>" class="button clear-log" data-pid="<?php echo $post->ID; ?>"><?php _e( 'Clear log' , ',moove' ); ?></a>
                    </div>
                    <!-- accordion-section-content-->
                  <?php endif; // $ma_data isn't empty
                endwhile; ?>
              <?php else: // no post found
                echo "No results were found";
              endif; // have_posts();
              wp_reset_query();
              wp_reset_postdata();
              // Check if there is no posts found with logging enabled
              if ( ! $log_enabled ) : ?>
              <table class="wp-list-table widefat fixed striped">
                <thead>
                  <tr>
                    <th class="column-date"><?php _e('Date / Time','moove'); ?></th>
                    <th class="column-user <?php echo ( isset( $user_options['user-hide'] ) || !is_array( $user_options ) ) ? '' : 'hidden'; ?>"><?php _e('User','moove'); ?></th>
                    <th class="column-activity <?php echo ( isset( $user_options['activity-hide'] ) || !is_array( $user_options ) ) ? '' : 'hidden'; ?>"><?php _e('Activity','moove'); ?></th>
                    <th class="column-ip <?php echo ( isset( $user_options['ip-hide'] ) || !is_array( $user_options ) ) ? '' : 'hidden'; ?>"><?php _e('Client IP','moove'); ?></th>
                    <th class="column-city <?php echo ( isset( $user_options['city-hide'] ) || !is_array( $user_options ) ) ? '' : 'hidden'; ?>"><?php _e('Client Location','moove'); ?></th>
                    <th class="column-referrer <?php echo ( isset( $user_options['referrer-hide'] ) || !is_array( $user_options ) ) ? '' : 'hidden'; ?>"><?php _e('Referrer','moove'); ?></th>
                  </tr>
                </thead>
                <tbody id="the-list">
                  <tr class="no-items"><td class="colspanchange" colspan="6"><?php _e('No posts found','moove'); ?></td></tr>
                </tbody>

                <tfoot>
                  <tr>
                    <th class="column-date"><?php _e('Date / Time','moove'); ?></th>
                    <th class="column-user <?php echo ( isset( $user_options['user-hide'] ) || !is_array( $user_options ) ) ? '' : 'hidden'; ?>"><?php _e('User','moove'); ?></th>
                    <th class="column-activity <?php echo ( isset( $user_options['activity-hide'] ) || !is_array( $user_options ) ) ? '' : 'hidden'; ?>"><?php _e('Activity','moove'); ?></th>
                    <th class="column-ip <?php echo ( isset( $user_options['ip-hide'] ) || !is_array( $user_options ) ) ? '' : 'hidden'; ?>"><?php _e('Client IP','moove'); ?></th>
                    <th class="column-city <?php echo ( isset( $user_options['city-hide'] ) || !is_array( $user_options ) ) ? '' : 'hidden'; ?>"><?php _e('Client Location','moove'); ?></th>
                    <th class="column-referrer <?php echo ( isset( $user_options['referrer-hide'] ) || !is_array( $user_options ) ) ? '' : 'hidden'; ?>"><?php _e('Referrer','moove'); ?></th>
                  </tr>
                </tfoot>
              </table>
              <?php endif;?>
          </div>
          <!-- accordion-section-->
        </div>
        <!-- accordion-->
      </div>
      <!-- moove-accordion-cnt -->
      <?php endif; //post types ?>
      <div class="load-more-container"></div>
  </div>
  <!-- moove-form-container -->
</div>
<!-- wrap -->
