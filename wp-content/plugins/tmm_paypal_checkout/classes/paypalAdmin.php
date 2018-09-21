<?php

/**
 * Our main class
 */

class paypalAdmin {

    public static function includeView($file, $params=array()) {
        $config = paypalConfig::getInstance();
        $path = $config->getItem('views_path') . $file . '.php';
        if(file_exists($path)){
            extract($params);
            require $path;
        }
    }

    /**
     * Admin interface > configuration
     */
    public static function adminConfiguration() {
        /* Save configuration */
        $config_saved = false;
        if (count($_POST)) {

            $environment_values = array('sandbox', 'live');
            if (isset($_POST['environment']) && in_array($_POST['environment'], $environment_values)) {
                update_option('paypal_environment', $_POST['environment']);
                $config_saved = TRUE;
            }

            if (isset($_POST['api_username']) && isset($_POST['api_password']) && isset($_POST['api_signature'])) {
                update_option('paypal_api_username', trim($_POST['api_username']));
                update_option('paypal_api_password', trim($_POST['api_password']));
                update_option('paypal_api_signature', trim($_POST['api_signature']));
                $config_saved = TRUE;
            }

            if (isset($_POST['success_page']) && is_numeric($_POST['success_page']) && $_POST['success_page'] > 0) {
                update_option('paypal_success_page', $_POST['success_page']);
                $config_saved = TRUE;
            }

            if (isset($_POST['cancel_page']) && is_numeric($_POST['cancel_page']) && $_POST['cancel_page'] > 0) {
                update_option('paypal_cancel_page', $_POST['cancel_page']);
                $config_saved = TRUE;
            }

	        $solution_types = array('Sole', 'Mark');
	        if (isset($_POST['paypal_solutiontype']) && in_array($_POST['paypal_solutiontype'], $solution_types)) {
		        update_option('paypal_solutiontype', $_POST['paypal_solutiontype']);
		        $config_saved = TRUE;
	        }

            if (isset($_POST['paypal_currency'])) {
                update_option('paypal_currency', $_POST['paypal_currency']);
                $config_saved = TRUE;
            }
        }

        self::includeView('adminconfiguration', array('config_saved' => $config_saved));
    }

     /**
     * Admin interface > payments history
     */
    public static function adminHistory() {
        global $wpdb;
		$config = paypalConfig::getInstance();
        $params = array();
        $config_saved = false;
        $allowed_statuses = array('success', 'pending', 'failed');
        if (count($_POST) && isset($_POST['status']) && in_array($_POST['status'], $allowed_statuses) && isset($_POST['id']) && is_numeric($_POST['id']) && $_POST['id'] > 0) {
            $config_saved = TRUE;

            $update_data = array('status' => $_POST['status']);
            $where = array('id' => $_POST['id']);

            $update_format = array('%s');

            $wpdb->update('tmm_cars_hccoder_paypal', $update_data, $where, $update_format);
        }

        if (isset($_GET['action']) && $_GET['action'] == 'details' && is_numeric($_GET['id']) && $_GET['id'] > 0) {
            $details = $wpdb->get_row('SELECT tmm_cars_hccoder_paypal.id,
                                tmm_cars_hccoder_paypal.amount,
                                tmm_cars_hccoder_paypal.currency,
                                tmm_cars_hccoder_paypal.packet_id,
                                tmm_cars_hccoder_paypal.status,
                                tmm_cars_hccoder_paypal.firstname,
                                tmm_cars_hccoder_paypal.lastname,
                                tmm_cars_hccoder_paypal.email,
                                tmm_cars_hccoder_paypal.description,
                                tmm_cars_hccoder_paypal.summary,
                                tmm_cars_hccoder_paypal.created
                              FROM
                                tmm_cars_hccoder_paypal
                              WHERE
                                tmm_cars_hccoder_paypal.id = ' . (int) $_GET['id']);

            $path = 'adminhistorydetails';
            $params['details'] = $details;
        } elseif (isset($_GET['action']) && $_GET['action'] == 'edit' && is_numeric($_GET['id']) && $_GET['id'] > 0) {
            $details = $wpdb->get_row('SELECT
                                tmm_cars_hccoder_paypal.status
                              FROM
                                tmm_cars_hccoder_paypal
                              WHERE
                                tmm_cars_hccoder_paypal.id = ' . (int) $_GET['id']);

            $path = 'adminhistoryedit';
            $params['details'] = $details;
        } else {
            $limit = $config->getItem('history_page_pagination_limit');
            $pagenum = 0;
            if (isset($_REQUEST['paged'])) {
                $pagenum = (int) $_REQUEST['paged'] - 1;
				if($pagenum < 0){
					$pagenum = 0;
				}
            }
            $order = 'DESC';
            if (isset($_REQUEST['order'])) {
                $order = $_REQUEST['order'];
            }
            $orderby = 'created';
            if (isset($_REQUEST['orderby'])) {
                $orderby = $_REQUEST['orderby'];
            }
            $user_email = '';
            if (isset($_REQUEST['user_email'])) {
                $user_email = $_REQUEST['user_email'];
                $_GET['user_email'] = $user_email;
            }
            $year = -1;
            if (isset($_REQUEST['y'])) {
                $year = $_REQUEST['y'];
                $_GET['y'] = $year;
            }
            $month = -1;
            if (isset($_REQUEST['m'])) {
                $month = $_REQUEST['m'];
                $_GET['m'] = $month;
            }

            //***
            $time_from = 0;
            $time_to = 0;
            if ($year > -1 OR $month > -1) {
                if ($month > -1 AND $year == -1) {
                    $year = intval(date('Y'));
                }
            }

            if ($month == -1) {
                //see for full year
                $time_from = mktime(0, 0, 0, 1, 1, $year);
                $time_to = mktime(0, 0, 0, 12, 31, $year);
            }

            if ($month != -1) {
                //see for full year
                $time_from = mktime(0, 0, 0, $month + 1, 1, $year);
                $time_to = mktime(0, 0, 0, $month + 1, 31, $year);
            }

            $rows_count = $wpdb->get_var(
				'SELECT COUNT(*)
                   FROM tmm_cars_hccoder_paypal
				   WHERE 1=1 ' . ($time_from > 0 ? ' ' . 'AND created>=' . $time_from . ' ' . 'AND created<=' . $time_to : '') . ' ' . (!empty($user_email) ? 'AND email LIKE "%' . $user_email . '%"' : '')
				);

            $rows = $wpdb->get_results('SELECT tmm_cars_hccoder_paypal.id,
                                tmm_cars_hccoder_paypal.amount,
                                tmm_cars_hccoder_paypal.currency,
                                tmm_cars_hccoder_paypal.packet_id,
                                tmm_cars_hccoder_paypal.status,
                                tmm_cars_hccoder_paypal.firstname,
                                tmm_cars_hccoder_paypal.lastname,
                                tmm_cars_hccoder_paypal.email,
                                tmm_cars_hccoder_paypal.description,
                                tmm_cars_hccoder_paypal.summary,
                                tmm_cars_hccoder_paypal.created
                              FROM
                                tmm_cars_hccoder_paypal WHERE 1=1 ' . ($time_from > 0 ? ' ' . 'AND created>=' . $time_from . ' ' . 'AND created<=' . $time_to : '') . ' ' . (!empty($user_email) ? 'AND email LIKE "%' . $user_email . '%"' : '') . '
                              ORDER BY
                                tmm_cars_hccoder_paypal.' . $orderby . ' ' . $order . ' LIMIT ' . $pagenum * $limit . ',' . $limit);
            
            $path = 'adminhistory';
            
            if(isset($details)){
                $params['details'] = $details;
            }
            
            $params['limit'] = $limit;
            $params['pagenum'] = $pagenum + 1;
            $params['order'] = $order;
            $params['rows_count'] = $rows_count;
            $params['rows'] = $rows;
            $params['user_email'] = $user_email;
            $params['year'] = $year;
            $params['month'] = $month;
        }
        $params['config_saved'] = $config_saved;
        if(isset($path)){
            self::includeView($path, $params);
        }
    }

    /**
     * Create table for payment history
     */
    public static function pluginInstall() {
        global $wpdb;
        $wpdb->query('
			CREATE TABLE IF NOT EXISTS `tmm_cars_hccoder_paypal` (
			  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			  `transaction_id` text NOT NULL,
			  `token` text NOT NULL,
			  `amount` float unsigned NOT NULL,
			  `currency` varchar(3) NOT NULL,
			  `packet_id` varchar(24) NOT NULL,
			  `status` text NOT NULL,
			  `firstname` text NOT NULL,
			  `lastname` text NOT NULL,
			  `email` text NOT NULL,
			  `description` text NOT NULL,
			  `summary` text NOT NULL,
			  `created` int(4) unsigned NOT NULL,
			  PRIMARY KEY (`id`)
			);
        ');
    }

}