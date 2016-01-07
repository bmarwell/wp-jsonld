<?php
/*
Plugin Name:    WP-JSONLD
Description:    WP-JSONLD adds valid schema.org microdata as JSON-LD-script to your blog posts, author pages and articles.
Version:        0.4.0
Author:         Benjamin Marwell
Original Author:         Mikko Piippo, Tomi Lattu
Donate link:    http://bit.ly/1SuWBoP
Plugin URI:     https://github.com/bmhm/wp-jsonld/


WP-JSONLD is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

WP-JSONLD for Aricle is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License along
with WP-JSONLD for Aricle; if not, write to the Free Software Foundation, Inc.,
51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
*/

namespace bmarwell\wp_jsonld;

use bmarwell\wp_jsonld\WPJsonLD;


/* Constants */
define('JSONLD_DIR', dirname(__FILE__));

/* Autoload Funktion */
function jsonld_autoload($class) {
    $path = sprintf("%s/inc/%s%s",
        JSONLD_DIR,
        substr($class, strrpos($class, '\\')+1),
        ".class.php"
    );

    if (file_exists($path)) {
        require_once($path);
    }
}

function wpjsonld_remove_yasr($content) {
    remove_filter('the_content', 'yasr_add_schema');

    return $content;
}

/* Autoload Init */
spl_autoload_register(__NAMESPACE__ . '\jsonld_autoload');

// start plugin.
$wpJsonLdTools = __NAMESPACE__ . '\WPJsonLDTools';
$wpjsonld_plugin = new WPJsonLD($wpJsonLdTools);
add_action('wp_footer', array($wpjsonld_plugin, 'addMarkup'));

// remove foreign rating.
remove_filter('the_content', 'yasr_add_schema');
add_action('the_post', __NAMESPACE__ .  '\wpjsonld_remove_yasr');


// remove transients after page changes
add_action('comment_post', array($wpJsonLdTools, 'deleteWpJsonLdTransients'));
add_action('edit_comment', array($wpJsonLdTools, 'deleteWpJsonLdTransients'));
add_action('edit_post', array($wpJsonLdTools, 'deleteWpJsonLdTransients'));
add_action('publish_post', array($wpJsonLdTools, 'deleteWpJsonLdTransients'));
add_action('publish_page', array($wpJsonLdTools, 'deleteWpJsonLdTransients'));
