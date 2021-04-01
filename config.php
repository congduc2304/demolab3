<?php
require_once 'system/mysql_site_data.php';

//MYSQL
$mysql_host = "localhost";
$mysql_user = "root";
$mysql_pass = "";
$mysql_dbname = "webphim";

//MYSQL TABLES
$table_prefix = "";
$table_members = $table_prefix."members";
$table_attempts = $table_prefix."loginattempts";
$table_pages = $table_prefix."pages";
$table_settings = $table_prefix."settings";
$table_suggest = $table_prefix."media_suggestions";
$table_contacts = $table_prefix."contact_messages";
$table_reports = $table_prefix."report_messages";
$table_movies = $table_prefix."movies";
$table_reviews = $table_prefix."reviews";

//GET SETTINGS FROM MYSQL
$site_data = new MysqlSiteData;
$site_data_array = $site_data->getSiteSettings($mysql_host,$mysql_dbname,$mysql_user,$mysql_pass,$table_settings);

//SITE
$conf_site_name = $site_data_array[0];
$conf_site_title = $site_data_array[1];
$conf_meta_description = $site_data_array[2];
$conf_meta_keywords = $site_data_array[3];
$conf_movie_suggest_logged_only = false;
$conf_movie_report_logged_only = true;
$conf_movie_always_show_series_bar = false;
$conf_max_review_chars = 1250;
$conf_language = "VN";
$conf_registration_verify = false;
$conf_use_custom_poster = false;
$video_poster_image_url = "https://picsum.photos/1920/1080/?random";

//PLAYER ADS
$conf_watch_media_ads = true;
$conf_watch_trailer_ads = false;
// Set vi.ai publisher id here, leave empty to disable
$conf_vi_ai_publisher_id = "";

//SECURITY
// Changing secret key and iv after install is recommended for security. Use http://www.unit-conversion.info/texttools/random-string-generator/
$secret_key = "tHjqOd4tICeQvmDOSAizvMsfMxxrhUJW";
$secret_iv = "P9u9YeYt1mln88EABDHlFSY9Y6wU0JQg";
// Maximum Login Attempts
$conf_max_login_attempts = 5;
// Timeout (in seconds) after max attempts are reached
$conf_login_timeout = 300;

//SYSTEM
// Set default timezone
date_default_timezone_set("Europe/Vilnius");
header('Content-Type: text/html; charset=utf-8');
$date_format = "Y-m-d";

//EMAIL AND VERIFICATION
$conf_admin_email = ""; //ONLY set this if you want a moderator to verify users and not the users themselves, otherwise leave blank
$conf_from_email = "example@mail.com";
$conf_from_name = "Example Email";
// Find specific server settings at https://www.arclab.com/en/kb/email/list-of-smtp-and-pop3-servers-mailserver-list.html
$conf_mail_server_type = "smtp";
$conf_smtp_server = "smtp.example.com";
$conf_smtp_user = "example@mail.com";
$conf_smtp_pass = "password";
$conf_smtp_port = 465; //465 for ssl, 587 for tls, 25 for other
$conf_smtp_security = "ssl"; //ssl, tls or ''

//include autoload file from vendor folder
require 'Facebook/autoload.php';
$fb = new Facebook\Facebook([
    'app_id' => '819522671945892', // replace your app_id
    'app_secret' => 'aee1c30df301529cb3b88ae445a5ae93',   // replace your app_scsret
    'default_graph_version' => 'v2.7'
        ]);


$helper = $fb->getRedirectLoginHelper();
$login_url = $helper->getLoginUrl("http://localhost:8080/PhimHay/");

try {

    $accessToken = $helper->getAccessToken();
    if (isset($accessToken)) {
        $_SESSION['access_token'] = (string) $accessToken;  //conver to string
        //if session is set we can redirect to the user to any page 
        header("Location:index.php");
    }
} catch (Exception $exc) {
    echo $exc->getTraceAsString();
}


//now we will get users first name , email , last name
if (isset($_SESSION['access_token'])) {

    try {

        $fb->setDefaultAccessToken($_SESSION['access_token']);
        $res = $fb->get('/me?locale=en_US&fields=name,email');
        $user = $res->getGraphUser();
        //echo 'Hello, ',$user->getField('name');
        
    } catch (Exception $exc) {
        echo $exc->getTraceAsString();
    }
}
?>