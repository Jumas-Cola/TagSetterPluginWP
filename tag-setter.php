<?php
/**
 * @since 1.0.0
 * @package Set tags WooCommerce products
 * Plugin Name: Tag Setter
 * Description: Adding tags programmatically to WooCommerce products
 * Version: 1.0.0
 * Author: Jumas-Cola 
 * Author URI: https://github.com/jumas-cola
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */
if(!defined('ABSPATH')){die('-1');}
function QuadLayers_init(){require plugin_dir_path( __FILE__ 
).'class-tag-setter.php';
$run = new TagSetter_class;
}

QuadLayers_init();
