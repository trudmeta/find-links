<?php
/*
Plugin Name: Find links
Description: Добавление атрибутов rel=nofollow и target=_blank к ссылкам
Version: 1.0.0
Author: Eugene
*/

use WpLinks\WpLinks;
use WpLinks\Searcher;

defined( 'ABSPATH' ) || exit;

defined('WPLINK_URL') or define('WPLINK_URL', plugins_url() . '/wp-links/');
defined('WPLINK_DIR') or define('WPLINK_DIR', plugin_dir_path(__FILE__));

require_once WPLINK_DIR . 'vendor/autoload.php';

$searcher = new Searcher();
$main = new WpLinks($searcher);

