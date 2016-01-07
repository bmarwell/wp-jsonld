<?php
/*
Plugin Name:    WP-JSONLD
Description:    WP-JSONLD adds valid schema.org microdata as JSON-LD-script to your blog posts, author pages and articles.
Version:        0.3.1
Author:         Benjamin Marwell
Original Author:         Mikko Piippo, Tomi Lattu
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

class WPJsonLDTools {
    /**
     * stripProtocolScheme
     *
     * @param String $url
     */
    static function stripProtocolScheme($url) {
        $disallowed = array('http://', 'https://', 'spdy://', '://', '//');

        foreach($disallowed as $d) {
            if(strpos($url, $d) === 0) {
                return str_replace($d, '', $url);
            }
        }

        return $url;
    }

    static function delete_transients() {
        global $wpdb;

        $wpdb->query("DELETE FROM `wp_options` WHERE `option_name` LIKE ('_transient_wp_jsonld-%')");
        $wpdb->query( "DELETE FROM `wp_options` WHERE `option_name` LIKE ('_transient_timeout_wp_jsonld-%')" );
    }

}



