<?php
/*
Plugin Name:    WP-JSONLD
Description:    WP-JSONLD adds valid schema.org microdata as JSON-LD-script to your blog.
Version:        0.3.2
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

/**
 * various static tool functions.
 * */
class WPJsonLDTools {
    /**
     * stripProtocolScheme
     *
     * @param String $url
     */
    public static function stripProtocolScheme($url) {
        $disallowed = array('http://', 'https://', 'spdy://', '://', '//');

        foreach ($disallowed as $d) {
            if (strpos($url, $d) === 0) {
                return str_replace($d, '', $url);
            }
        }

        return $url;
    }

    /**
     * Removes all transients with wpjonld namespace.
     * */
    public static function deleteWpJsonLdTransients() {
        // remove current transients.
        $GLOBALS['wpdb']->query("DELETE FROM `wp_options` WHERE `option_name` LIKE ('_transient_wpjsonld-%')");
        $GLOBALS['wpdb']->query( "DELETE FROM `wp_options` WHERE `option_name` LIKE ('_transient_timeout_wpjsonld-%')" );

        // old naming.
        $GLOBALS['wpdb']->query("DELETE FROM `wp_options` WHERE `option_name` LIKE ('_transient_wp_jsonld-%')");
        $GLOBALS['wpdb']->query( "DELETE FROM `wp_options` WHERE `option_name` LIKE ('_transient_timeout_wp_jsonld-%')" );
    }

    /** Returns the url where blog posts are being shown. */
    public static function getBlogUrl() {
        // this is mean. The Page with posts can be another page
        // than home_url() or site_url().
        if (get_option('show_on_front') == 'page') {
            return get_permalink(get_option('page_for_posts'));
        }

        return home_url('/');
    }

    public static function findPublisherLogo() {
        $logopaths = array(
            get_site_url(). '/publisher-600x60.png',
            'https://logo.clearbit.com/' . self::stripProtocolScheme(get_site_url()) . '?size=60',
            JSONLD_DIR . '/img/publisher-600x60.png'
        );

        foreach ($logopaths as $logopath) {
            if (file_exists($logopath)) {
                return self::createLogo($logopath);
            }
        }
    }

    public static function createLogo($url) {
        if (!file_exists($url)) {
            return;
        }

        list($imgWidth, $imgHeight) = getimagesize($url);

        $logo = new ImageObject();
        $logo->url = $url;
        $logo->id = $logo->url;
        $logo->height = $imgWidth;
        $logo->width = $imgHeight;
        $logo->contentSize = self::humanFilesize(filesize($url));
    }


    public static function humanFilesize($bytes, $decimals = 2) {
        $sizenames = 'BKMGTP';
        $factor = floor((strlen($bytes) - 1) / 3);

        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sizenames[$factor];
    }
}



