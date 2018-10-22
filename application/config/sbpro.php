<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// From address is used for all emails send by the script
$config['email_from_address'] = "info@example.com";

// From name is used for all emails send by the script
$config['email_from_name'] = "Builder";

// Subject for the email send to user when payment received
$config['email_confirmation_subject'] = "Builder: Confirmation email!";

// Subject for the email send to user when admin create an user from admin panel with paid plan
$config['email_activation_subject'] = "Builder: Activation email!";

// Subject for the email send to user when admin create an user from admin panel with free plan
$config['email_login_subject'] = "Builder: Account created!";

// Subject for the email send to user when password forgot
$config['email_forgot_password_subject'] = "Builder: Forgot Password!";

// Subject for the email send to user when admin send reset password email
$config['email_reset_password_subject'] = "Builder: Reset Password!";

// SentAPI email from address is used for all emails send by the script
$config['sent_email_from_address'] = "info@example.com";

// SentAPI email from name is used for all emails send by the script
$config['sent_email_from_name'] = "Builder";

// SentAPI email subject for the email send to user by the script
$config['sent_email_default_subject'] = "Builder: Mail from your site!";

$config['email_sub_cancel_subject'] = "Builder: Profile Cancelled!";

$config['sub_cancel_failed_subject'] = "Builder: Profile Cancellation Failed!";

// CoreUpdate URI
$config['autoupdate_uri'] = 'http://update.pagestead.com/updates.json';
//$config['autoupdate_uri'] = 'http://psupdate.tapan/updates.json';

// License Server API URI
$config['license_api'] = 'http://license.pagestead.com/api/';
//$config['license_api'] = 'http://pslicense.tapan/api/';

$config['license_uri'] = 'http://license.pagestead.com/api/verify_key/';

// Screenshot API Key
$config['screenshot_api_key'] = "2e94ee";
$config['screenshot_secret'] = "lksejhfefghug75765";

// Screenshot folder for site thumbs
$config['screenshot_sitethumbs_folder'] = "tmp/sitethumbs/";
// Screenshot folder for site thumbs
$config['screenshot_blockhumbs_folder'] = "tmp/blockthumbs/";

// Upload path for block thumbnails
$config['block_thumbnail_upload_config']['upload_path'] = "./images/uploads"; // Used to store the uploaded file
$config['block_thumbnail_upload_config']['allowed_types'] = 'gif|jpg|png';
$config['block_thumbnail_upload_config']['max_size'] = 5000;
$config['block_thumbnail_upload_config']['max_width'] = 2000;
$config['block_thumbnail_upload_config']['max_height'] = 1000;

// Upload path for component thumbnails
$config['component_thumbnail_upload_config']['upload_path'] = "./images/uploads"; // Used to store the uploaded file
$config['component_thumbnail_upload_config']['allowed_types'] = 'gif|jpg|png';
$config['component_thumbnail_upload_config']['max_size'] = 5000;
$config['component_thumbnail_upload_config']['max_width'] = 2000;
$config['component_thumbnail_upload_config']['max_height'] = 1000;

// Google fonts
$config['google_font_api'] = "https://fonts.googleapis.com/css?family=";

// Upload settings for the file browser
$config['browser_upload_config']['allowed_types'] = 'gif|jpg|png|css|js|html|txt|htm|xhtml';
$config['browser_upload_config']['max_size'] = 5000;
$config['browser_upload_config']['max_width'] = 20000;
$config['browser_upload_config']['max_height'] = 10000;

// Upload settings for logo uploads
$config['logo_upload_config']['upload_path'] = './images/uploads/';
$config['logo_upload_config']['allowed_types'] = 'gif|jpg|png';
$config['logo_upload_config']['max_size'] = 20000;
$config['logo_upload_config']['max_width'] = 2000;
$config['logo_upload_config']['max_height'] = 1000;

// Cloned blocks go in... (within the configured elements folder)
$config['cloned_folder'] = "clones";

// Cloudflare integration; specify these in config_custom.php
$config['cloudflare']['x_auth_email'] = '';
$config['cloudflare']['x_auth_key'] = '';