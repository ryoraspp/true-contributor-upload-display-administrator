<?php
/*
Plugin Name: True Contributor Uploads Display Administrator
Description: Allows users with the contributor role to upload images. and only the administrator to be able to see the images uploaded by others.
Version:     1.1
Author:      Ryo Uozumi
Author URI:  https://ryo.nagoya
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

// Prevent the content of php files from being viewed even if they are directly accessed.
if ( ! defined( 'ABSPATH' ) ) exit;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Add upload capability to contributors on plugin install
function rwc_allow_contributor_uploads_install() {
	$contributor = get_role('contributor');
	$contributor->add_cap('upload_files');
}
register_activation_hook( __FILE__, 'rwc_allow_contributor_uploads_install' );

// I want only the administrator to be able to see the images uploaded by others.
function display_only_self_uploaded_medias_only_administrator( $query ) {
    if ( ( $user = wp_get_current_user() ) && ! current_user_can( 'administrator' ) ) {
        $query['author'] = $user->ID;
    }
    return $query;
}
add_action( 'ajax_query_attachments_args', 'display_only_self_uploaded_medias_only_administrator' );

// Remove upload capabilitys on plugin uninstall
function rwc_allow_contributor_uploads_deactivation() {
	$contributor = get_role('contributor');
	$contributor->remove_cap('upload_files');
}
register_deactivation_hook(__FILE__, 'rwc_allow_contributor_uploads_deactivation');

//* That's all!