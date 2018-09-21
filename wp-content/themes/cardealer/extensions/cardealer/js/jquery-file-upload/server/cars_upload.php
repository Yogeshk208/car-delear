<?php if ( !defined('ABSPATH') ) exit;

$user_ID = get_current_user_id();
if (!$user_ID) {
    exit;
}

if (!empty($_FILES)) {

    $message = '';
    $error_code = is_array($_FILES['files']['error']) ? $_FILES['files']['error'][0] : $_FILES['files']['error'];

    if ( !empty($error_code) ) {

        switch ($error_code) {
            case UPLOAD_ERR_INI_SIZE:
                $message = "The uploaded file exceeds the upload_max_filesize directive in php.ini. upload_max_filesize = " . ini_get('upload_max_filesize');
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $message = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
                break;
            case UPLOAD_ERR_PARTIAL:
                $message = "The uploaded file was only partially uploaded";
                break;
            case UPLOAD_ERR_NO_FILE:
                $message = "No file was uploaded";
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $message = "Missing a temporary folder";
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $message = "Failed to write file to disk";
                break;
            case UPLOAD_ERR_EXTENSION:
                $message = "File upload stopped by extension";
                break;
            default:
                $message = "Unknown upload error";
                break;
        }

    }

    if ($message) {
        echo $message;
    }

    $user_file_space = TMM_Ext_PostType_Car::get_user_file_space($user_ID);

    //***** checking user permissions
    if (!user_can($user_ID, 'manage_options')) {
        //*** check for max user space

        if (($user_file_space['user_file_space'] > $user_file_space['user_file_max_space'])) {
            exit;
        }
        //*** check for max user post count
        $options = TMM_Cardealer_User::get_default_user_role_options($user_ID);
        $user_post_count = TMM_Cardealer_User::count_users_cars($user_ID);
        if ($user_post_count > $options['max_cars']) {
            exit;
        }
    }

    if (user_can($user_ID, 'manage_options') AND isset($_GET['photo_set_hash']) AND $_GET['is_new_car'] == 0) {
        //check is user upload cars for other users, and him is admin
        $user_ID = get_post_field('post_author', $_GET['photo_set_hash']);
    }

    $targetFolder = TMM_Ext_PostType_Car::get_image_upload_folder() . $user_ID;
    if(!is_dir($targetFolder)){
        mkdir($targetFolder, 0755);
    }

    //uploading photos from front by user
    if (isset($_GET['is_new_car']) && $_GET['is_new_car'] == 1) {
        $targetFolder = TMM_Ext_PostType_Car::get_image_upload_folder() . $user_ID . '/tmp';
        @mkdir($targetFolder, 0755);
    }

    $targetFolder.='/' . $_GET['photo_set_hash'];
    if(!is_dir($targetFolder)){
        mkdir($targetFolder, 0755);
    }

    $tempFile = $_FILES['files']['tmp_name'][0];
    $fileParts = pathinfo($_FILES['files']['name'][0]);

    // Validate the file type
    $allowed_file_types = array('jpg', 'jpeg', 'png'); // File extensions

    $error =false;

    if (in_array(strtolower($fileParts['extension']), $allowed_file_types)) {

        $watermark = new TMM_Cardealer_Watermark();
        $tmpImg = wp_get_image_editor( $tempFile );

        if ('jpeg' == $fileParts['extension'] && $tmpImg->supports_mime_type("image/png") ) {
            $new_file_name = uniqid() . ".png";
            $targetFile = $targetFolder . '/' . $new_file_name;
            $imageObject = imagecreatefromjpeg($tempFile);

            imagepng($imageObject, $targetFile);
            @unlink($tempFile);

        } else {
            $new_file_name = uniqid() . "." . strtolower($fileParts['extension']);
            $targetFile = $targetFolder . '/' . $new_file_name;

            @move_uploaded_file($tempFile, $targetFile);
        }

        //check for file size
        if (!getimagesize($targetFile)) {
            $error = true;
        }

        //if (!user_can($user_ID, 'manage_options') && filesize($targetFile) > (int) TMM::get_option('car_photo_size_limit', TMM_APP_CARDEALER_PREFIX) * 1000000) {
        //$error = true;
        //}

        if (!user_can($user_ID, 'manage_options') && filesize($targetFile) > (int) $user_file_space['size_left']) {
            $error = true;
        }

        if(!$error){
            $folders = TMM_Ext_PostType_Car::$image_sizes;
            //check for already photo count
            /*
              $file_count = @count(glob($targetFolder . '/main/' . "*.*"));
              if ($file_count >= (int) TMM::get_option('count_of_car_photos_for_free_upload', TMM_APP_CARDEALER_PREFIX)) {
              unlink($targetFile);
              return false;
              }
             *
             */
            //***

            foreach ($folders as $folder_key => $folder) {
                if(!is_dir($targetFolder . '/' . $folder_key)){
                    mkdir($targetFolder . '/' . $folder_key, 0755);
                }
                $folder_file = $targetFolder . '/' . $folder_key . '/' . $new_file_name;

                if (@copy($targetFile, $folder_file)) {

                    TMM_Helper::tmm_resize_image($folder_file, $folder['width'], $folder['height'], true, null, null, 90, true);
                    //*****
                    if (TMM::get_option('watermark_on_image', TMM_APP_CARDEALER_PREFIX)) {
                        $alpha_level = TMM::get_option('alpha_level', TMM_APP_CARDEALER_PREFIX);
                        if (!$alpha_level) {
                            $alpha_level = 70;
                        }
                        $watermark->place_watermark($folder_file, $alpha_level);
                    }
                }
            }
        }

        @unlink($targetFile);
    }
}

$post_id = $_GET['photo_set_hash'];
$post_author_id = get_post_field('post_author', $post_id);
if ($_GET['is_new_car']) {
    $post_author_id = $user_ID;
}
$photos = TMM_Ext_PostType_Car::get_post_photos($post_id, $post_author_id, 'thumb', $_GET['is_new_car']);

$response = array();

if (isset($_GET['delete'])) {
    TMM_Ext_PostType_Car::delete_post_photo($post_author_id, $post_id, $_GET['delete'], (boolean) (isset($_GET['is_new_car']) AND $_GET['is_new_car'] == 1));
    exit;
}

if (isset($_GET['get_user_info']) && $_GET['get_user_info'] === 'filespace') {
    $response['user_file_space'] = TMM_Ext_PostType_Car::get_user_file_space($user_ID);
    echo json_encode($response['user_file_space']);
    exit;
}

if (!empty($photos)) {
    $script_url = $_SERVER['PHP_SELF']; //for IIS servers
    if (isset($_SERVER['SCRIPT_URL'])) {
        $script_url = $_SERVER['SCRIPT_URL'];
    }
    foreach ($photos as $key => $url) {
        $response['files'][] = array(
            'url' => $url,
            'thumbnailUrl' => $url . '?tmp=' . uniqid(),
            'name' => basename($url),
            'type' => '',
            'size' => '',
            'deleteUrl' => $script_url . '?action=app_cardealer_upload_car_image&delete=' . basename($url) . '&photo_set_hash=' . $post_id . '&is_new_car=' . $_GET['is_new_car'],
            'deleteType' => 'GET',
        );
    }
    $response['user_file_space'] = TMM_Ext_PostType_Car::get_user_file_space($user_ID);
}

echo json_encode($response);
exit;
