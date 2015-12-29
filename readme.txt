=== JSON-LD for Article ===
Contributors: Mikko Piippo, tlattu
Donate link:
Tags: json-ld, markup, schema, rich snippets, structured data, microdata, SEO,schema.org,schema markup,JSON
Requires at least: 4.0
Tested up to: 4.2.2
Stable tag: 0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

JSON-LD for Article is simply the easiest solution to add valid schema.org microdata as a JSON-LD script to your blog posts or articles.

== Description ==
Search engines such as Google are using structured data markup in many ways—for example, to create rich snippets in search results. Search results with rich snippets will improve your click through rates and increase the number of visitors on your website.

It has been a tedious task to add the complicated Schema.org markup to your website - sometimes it wasn’t even possible due to technical constraints. Now, thanks to **JSON-LD**, almost any website can enjoy the benefits of structured data. For WordPress users it’s even easier thanks to this plugin.

JSON-LD for Article is simply the easiest solution to create valid schema.org microdata markup on your Wordpress site automatically from your web content and its metadata. All you need to do is install and activate the plugin, and it will generate JSON-LD markup of your posts that Google and other search engines can use for rich snippets using the schema.org for articles.

Some WP themes include traditional schema.org markup embedded in the html code the theme produces. If you are using one of these themes, this plugin is probably not necessary. If the markup produced by the theme contradicts the data specified in the JSON-LD, the added information might be not be helpful at all. Thus you should check the markup provided by the theme before adding possibly unnecessary JSON-LD to your site.

On the other hand, if you use a custom theme, it is easier to use the functionality provided by the plugin than to invent the wheel again and add the markup in html. Very often, this leads to html code that is difficult to read and is not valid structural markup according to Google’s validator. 

Our plugin:

* Helps your site to earn rich snippets in Google’s SERP.
* Does not depend on other plugins or external code.
* Is simple to install: plug-and-play, no need to configure anything.


== Installation ==

The easiest way to install the plugin is to use the WP built-in menu for finding and installing new plugins directly from the WordPress repositories.

If you need to install this using FTP or SFTP, you should follow these steps:

1. Upload `json-ld-article.php` to the `/wp-content/plugins/` directory using your favorite FTP client.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. That’s it.


**Compatibility.** This version requires php 5.4 for some options of json_encode. If you encounter any problems with the plugin you should check your web hotel’s php version.

== Frequently Asked Questions ==
**Does this plugin X?** Most probably, it does not. This plugin simply adds to the footer of your single post views valid JSON-LD filled with the data from the WP database. You should not see anything new on the page - the JSON-LD can be seen only in the source code of the web page.

**Why should I install this plugin?** Installing this plugin is the easiest way to add structured data to your blog. The plugin automatically creates the JSON-LD according to Google’s specification. 

**Does this plugin improve my SEO rankings?** We cannot promise it - but installing this plugin is in any case a step in right direction.

== Screenshots ==

Screenshots should not be necessary - there is nothing to see here :)

== Changelog ==

= 0.1 =
*  This is a fully functional version based on the idea of minimum viable product. It delivers what it promises, without any bells and whistles. Some people might regard this as a beta version :)
