<?php
/* 
Plugin Name: DareDev
Description: A helper plugin to easily create Custom Post Types, Custom Taxonomies and more.
Author: Giorgos Sarigiannidis
Version: 1.0
Author URI: http://www.gsarigiannidis.gr
*/

define ( 'PLUGIN_VERSION', '1.0' );

// Localize it
load_plugin_textdomain('daredev', false, basename( dirname( __FILE__ ) ) . '/languages' ); 

// Autoload classes
include_once dirname(__FILE__) . '/inc/autoloader.php';