<?php

/*
 * Plugin Name: WpSimpleTools Upload Limit
 * Description: Sets the maximum upload size ammitted in WordPress.
 * Author: WpSimpleTools
 * Author URI: https://profiles.wordpress.org/wpsimpletools/#content-plugins
 * Version: 1.0.4
 * Plugin Slug: wpsimpletools-upload-limit
 * Text Domain: wpsimpletools-upload-limit
 */
if (! defined('ABSPATH')) {
    die("Don't call this file directly.");
}

add_action('admin_menu', 'wpst_ul_add_config_page');

function wpst_ul_add_config_page() {

    add_submenu_page('options-general.php', 'Upload limit', 'Upload limit', 'manage_options', 'wpst_ul_manage_upload_limit', 'wpst_ul_manage_upload_limit');
}

function wpst_ul_manage_upload_limit() {

    ?>

<div class="wrap">
	<h2><?php _e('WpSimpleTools Upload Limit', 'wpsimpletools-upload-limit');?></h2>


<?php
    if (isset($_POST['upload_limit'])) {
        $upload_limit = $_POST['upload_limit'];
        
        if (ctype_digit($upload_limit)) {
            update_option('max_file_size', $upload_limit);
            $class = 'notice notice-success is-dismissible';
            $message = __('Setting saved!', 'wpsimpletools-upload-limit');
            printf('<div class="%1$s"><p>%2$s: ' . wpst_ul_human_filesize($upload_limit) . '</p></div>', esc_attr($class), esc_html($message));
        } else {
            $class = 'notice notice-error';
            $message = __('Value is not permitted: ', 'wpsimpletools-upload-limit');
            printf('<div class="%1$s"><p>%2$s: \'' . $upload_limit . '\'</p></div>', esc_attr($class), esc_html($message));
        }
    } else {
        ?>	<p><?php _e('Current limit is ', 'wpsimpletools-upload-limit');?><?php echo wpst_ul_human_filesize(get_option('max_file_size')); ?></p>
    <?php
    }
    
    ?>


	<form method="post">
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><?php _e('Max upload size', 'wpsimpletools-upload-limit');?></th>
				<td><input type="text" id="upload_limit" name="upload_limit" placeholder="Enter Numeric value" value="<?php echo get_option('max_file_size'); ?>" /> <!--  -->
					<p class="description" id="tagline-description"><?php _e('Enter upload limit in Bytes (i.e. 67108864 = 64MB)', 'wpsimpletools-upload-limit');?></p></td>
			</tr>
		</table>
			<?php submit_button(); ?>
		</form>
</div>

<?php
}

//
add_filter('upload_size_limit', 'wpst_ul_increase_upload');

function wpst_ul_increase_upload($bytes) {

    return get_option('max_file_size');
}

//
function wpst_ul_human_filesize($size, $precision = 2) {

    for ($i = 0; ($size / 1024) > 0.9; $i ++, $size /= 1024) {}
    return round($size, $precision) . ' ' . [
        'B',
        'kB',
        'MB',
        'GB',
        'TB',
        'PB',
        'EB',
        'ZB',
        'YB'
    ][$i];
}

?>