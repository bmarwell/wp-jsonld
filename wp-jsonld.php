<?php 
/*
Plugin Name:    WP-JSONLD
Description:    WP-JSONLD adds valid schema.org microdata as JSON-LD-script to your blog posts, author pages and articles.
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

use bmarwell\wp_jsonld\Author;
use bmarwell\wp_jsonld\BlogPosting;
use bmarwell\wp_jsonld\ImageObject;
use bmarwell\wp_jsonld\Organization;

/**
 * @author Mikko Piippo, Tomi Lattu
 * @since 0.1
 * @license http://www.gnu.org/licenses/gpl-2.0.html GPLv2 or later
 */


/* Constants */
define('JSONLD_DIR', dirname(__FILE__));

class WP_JsonLD {
    /**
     * createBlogPosting
     *
     * @param bool|FALSE $isParent
     * @return BlogPosting
     */
    function createBlogPosting($isParent = false) {
        $blogpost = new BlogPosting($isParent);

        // Basic info
        $blogpost->headline = get_the_title();
        $blogpost->datePublished = get_the_date('Y-m-d H:i:s');
        $blogpost->url = get_permalink();
        $blogpost->setId(get_permalink());

        // Addition info
        $blogpost->articleSection = get_the_category()[0]->cat_name;
        $blogpost->dateModified = get_the_modified_date('Y-m-d H:i:s');
        $blogpost->commentCount = get_comments_number();

        // Thumbnail if exists

        return $blogpost;
    }

    /**
     * create_author_entity( - create Author Markup
     *
     * @param bool|FALSE $isParent
     */
    function create_author_entity($isParent = false) {
        $auId = get_the_author_meta( 'ID' );
        $author = new Author($isParent);
        $author->name = get_the_author_meta('display_name');
        $author->url = get_author_posts_url($auId);
        $author->setId(get_author_posts_url($auId));
        $author->email = get_the_author_meta('user_email');

        return $author;
    }

    /**
     * createOrganization
     *
     *
     * Creates an organization object for the blog.
     *
     * @param bool|FALSE $isParent set to true to insert @context
     */
    function createOrganization($isParent = false) {
        $org = new Organization($isParent);
        $org->name = get_bloginfo('name');
        $org->legalName = get_bloginfo('name');
        $org->setId(network_site_url('/'));
        $org->url = network_site_url('/');
        $org->logo = $this->createLogo();

        return $org;
    }

    /**
     * createImage
     *
     * Creates an image for the post thumbnail.
     *
     * @param bool $isParent
     */
    function createImage($isParent = false) {
        $thId = get_post_thumbnail_id();
        $img = new ImageObject($isParent);

        if (has_post_thumbnail()) {
            $img->contentUrl = wp_get_attachment_url($thId);
            $img->image = wp_get_attachment_url($thId);
            $img->setId(get_attachment_link($thId));
            $img->url = wp_get_attachment_url($thId);

            $props = wp_get_attachment_metadata($thId);
            $img->width = $props['width'];
            $img->height = $props['height'];
            $img->caption = wp_prepare_attachment_for_js($thId)['caption'];
        }

        return $img;
    }

    /**
     * createLogo
     *
     * @param bool|FALSE $isParent
     */
    function createLogo($isParent = false) {
        $logourl = "https://logo.clearbit.com/" . WP_JsonLD::stripProtocolScheme(get_site_url());
        $logo = new ImageObject($isParent);
        $logo->setId($logourl);
        $logo->url = $logourl;

        return $logo;
    }

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

    /**
     * createMainEntity
     *
     * @param String $type
     * @param String $id
     */
    function createMainEntity($type = 'Article', $id = null) {
        return array(
            "@type" => $type,
            "@id" => $id);
    }

    function create_jsonld_author() {
        $markup = $this->create_author_entity(true);
        //$markup->mainEntityOfPage = createMainEntity('WebPage', $markup->url);
        //$markup->generatedAt = date('Y-m-d H:i:s');

        $scriptcontents = json_encode($markup, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);

        return $scriptcontents;
    }

    /**
     * Create a rating object for AggregateRating.
     *
     * TODO: Convert to JsonLD Object instead of using array.
     * @since 0.3
     * */
    function createRating() {
        $visitor_votes = yasr_get_visitor_votes();

        if ($visitor_votes) {
            foreach ($visitor_votes as $rating) {
                $visitor_rating['votes_number']=$rating->number_of_votes;
                $visitor_rating['sum']=$rating->sum_votes;
            }
        }

        if ($visitor_rating['sum'] != 0 && $visitor_rating['votes_number'] != 0) {
            $average_rating = $visitor_rating['sum'] / $visitor_rating['votes_number'];
            $average_rating = round($average_rating, 1);

            $ratingMarkup = array(
                "@type" => "AggregateRating",
                "ratingValue" => "$average_rating",
                "ratingCount" => $visitor_rating['votes_number'],
             );
        }

        return $ratingMarkup;
    }

    function create_jsonld_blogposting() {
        $markup = $this->createBlogPosting(true);
        $markup->author = $this->create_author_entity();
        $markup->publisher = $this->createOrganization();
        $markup->image = $this->createImage();
        // this is mean. The Page with posts can be another page
        // than home_url() or site_url().
        if (get_option('show_on_front') == 'page') {
            $blogurl = get_permalink(get_option('page_for_posts'));
        } else {
            $blogurl = home_url('/');
        }
        $markup->mainEntityOfPage = $this->createMainEntity('WebPage', $blogurl);
        //$markup->generatedAt = date('Y-m-d H:i:s');

        // create rating if yasr is installed.
        if (function_exists("yasr_get_visitor_votes")) {
            $visitor_votes = yasr_get_visitor_votes();

            if ($visitor_votes) {
                $markup->aggregateRating = $this->createRating();
            }

        }

        $scriptcontents = json_encode($markup, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);

        return $scriptcontents;
    }

    function delete_transients() {
        global $wpdb;

        $wpdb->query("DELETE FROM `wp_options` WHERE `option_name` LIKE ('_transient_wp_jsonld-%')");
        $wpdb->query( "DELETE FROM `wp_options` WHERE `option_name` LIKE ('_transient_timeout_wp_jsonld-%')" );
    }

    /**
     * Echoes Markup to your footer.
     * @author Mikko Piippo, Tomi Lattu
     * @since 0.1
     */
    function add_markup() {
        // the text markup to be inserted.
        $markup = null;

        // Get the data needed for building the JSON-LD
        if (is_single()) {
            $postid = get_the_id();

            if ( false === ( $markup = get_transient( 'wp_jsonld-article_' . $postid ) ) ) {
                $markup = $this->create_jsonld_blogposting();
                set_transient('wp_jsonld-article_' . $postid, $markup, 0);
            }
        } elseif (is_author()) {
            $auId = get_the_author_meta( 'ID' );

            if ( false === ( $markup = get_transient( 'wp_jsonld-author_' . $auId ) ) ) {
                $markup = $this->create_jsonld_author();
                set_transient('wp_jsonld-author_' . $auId, $markup, 0);
            }
        }

        // if markup found, insert.
        if (null !== $markup) {
            echo '<script type="application/ld+json">'
                . $markup
                . '</script>';
        }
    } // end function


    /* Autoload Funktion */
    public static function jsonld_autoload($class) {
        $path = sprintf("%s/inc/%s%s",
            JSONLD_DIR,
            substr($class, strrpos($class, '\\')+1),
            ".class.php"
        );

            //require_once($path);  
        if (file_exists($path)) {
            require_once($path);  
        }
    } 

    public static function wpjsonld_remove_yasr($content) {
        remove_filter('the_content', 'yasr_add_schema');

        return $content;
    }
}


/* Autoload Init */
spl_autoload_register(__NAMESPACE__ . '\WP_JsonLD::jsonld_autoload');

// start plugin.
$wpjsonld_plugin = new WP_JsonLD;
add_action('wp_footer', array($wpjsonld_plugin, 'add_markup'));

// remove foreign rating.
remove_filter('the_content', 'yasr_add_schema');
add_action('the_post',  array($wpjsonld_plugin, 'wpjsonld_remove_yasr'));


// remove transients after page changes
add_action('comment_post', array($wpjsonld_plugin, 'delete_transients'));
add_action('edit_comment', array($wpjsonld_plugin, 'delete_transients'));
add_action('edit_post',  array($wpjsonld_plugin, 'delete_transients'));
add_action('publish_post', array($wpjsonld_plugin, 'delete_transients'));
add_action('publish_page',  array($wpjsonld_plugin, 'delete_transients'));
