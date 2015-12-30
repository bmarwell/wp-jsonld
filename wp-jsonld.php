<?php 
/*
Plugin Name:    WP-JSONLD
Description:    WP-JSONLD is the continuation of the most easy solution to add schema.org markup inside a script tag to your blog posts and author pages.
Version:        0.2
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

/**
 * @author Mikko Piippo, Tomi Lattu
 * @since 0.1
 * @license http://www.gnu.org/licenses/gpl-2.0.html GPLv2 or later
 */


/* Constants */
define('JSONLD_DIR', dirname(__FILE__));
define('JSONLD_CACHE_DIR', JSONLD_DIR . '/cache');

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
 * createAuthorMarkup - create Author Markup
 *
 * @param bool|FALSE $isParent
 */
function createAuthor($isParent = false) {
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
    $org->logo = createLogo();

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
    $logourl = "https://logo.clearbit.com/" . stripProtocolScheme(get_site_url());
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
function stripProtocolScheme($url) {
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

/**
 * check_cache_dir
 *
 * Try to create the cache directory.
 *
 */
function check_cache_dir() {
    if (!file_exists(JSONLD_CACHE_DIR)) {
        mkdir(JSONLD_CACHE_DIR, 0777, true);
    }
}

function create_jsonld_author($scriptpath) {
    $markup = createAuthor(true);
    //$markup->mainEntityOfPage = createMainEntity('WebPage', $markup->url);
    //$markup->generatedAt = date('Y-m-d H:i:s');

    $scriptcontents = json_encode($markup, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);

    $handle = fopen($scriptpath, 'c');
    fwrite($handle, $scriptcontents);
    fclose($handle);
}

function create_jsonld_blogposting($scriptpath) {
    $markup = createBlogPosting(true);
    $markup->author = createAuthor();
    $markup->publisher = createOrganization();
    $markup->image = createImage();
    // this is mean. The Page with posts can be another page
    // than home_url() or site_url().
    $blogurl = get_permalink(get_option('page_for_posts'));
    $markup->mainEntityOfPage = createMainEntity('WebPage', $blogurl);
    //$markup->generatedAt = date('Y-m-d H:i:s');

    $scriptcontents = json_encode($markup, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);

    $handle = fopen($scriptpath, 'c');
    fwrite($handle, $scriptcontents);
    fclose($handle);
}

/**
 * Creates blogposting markup if it doesnt exist yet.
 * @since 0.2
 * */
function check_create_jsonld_blogposting() {
    $postid = get_the_id();
    $scriptpath = 'cache/blogpost_' . $postid . '.json';
    $abspath = JSONLD_DIR . '/' . $scriptpath;

    if (!file_exists($abspath)) {
        create_jsonld_blogposting($abspath);
    }

    if (filemtime($abspath) < get_the_modified_date('U')) {
        create_jsonld_blogposting($abspats);
    }

    return $scriptpath;
}

function check_create_jsonld_author() {
    $authorid = get_the_id();
    $scriptpath = 'cache/author_' . $authorid . '.json';
    $abspath = JSONLD_DIR . '/' . $scriptpath;

    if (!file_exists($abspath)) {
        create_jsonld_author($abspath);
    }

    if (filemtime($abspath) < get_the_modified_date('U')) {
        create_jsonld_author($abspats);
    }

    return $scriptpath;
}


/**
 * Echoes Markup to your footer.
 * @author Mikko Piippo, Tomi Lattu
 * @since 0.1
 */
function add_markup() {
    check_cache_dir();

    // Get the data needed for building the JSON-LD
    if (is_single()) {
        $script = check_create_jsonld_blogposting();
    } elseif (is_author()) {
        $script = check_create_jsonld_author();
    }

    // TODO: Replace by wp_enqueue_script() when types can be changed.
    $scripturl = plugins_url($script, __FILE__);
    echo '<script type="application/ld+json" src="' . $scripturl . '">'
        . file_get_contents(JSONLD_DIR . '/' . $script)
        . '</script>';
} // end function

add_action('wp_footer','add_markup');

/* Autoload Funktion */
function jsonld_autoload($class) {
    if ( in_array($class, array('Author', 'BlogPosting', 'Article', 'ImageObject', 'JsonLD', 'Organization')) ) {                                                              
        require_once(
            sprintf('%s/inc/%s.class.php',
                JSONLD_DIR,
                $class));  
    }   
} 

/* Autoload Init */
spl_autoload_register('jsonld_autoload');

