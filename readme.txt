=== Code Embed ===
Contributors: dartiss
Donate link: https://artiss.blog/donate
Tags: code, embed, html, javascript, script
Requires at least: 4.6
Tested up to: 5.5
Requires PHP: 5.3
Stable tag: 2.3.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Code Embed provides a very easy and efficient way to embed code (JavaScript and HTML) in your posts and pages.

== Description ==

**Note for users of WordPress 5.0 and above: please see the FAQ for details on using this plugin with the block editor**

Code Embed allows you to embed code (JavaScript and HTML - it can't be used for server-side code, such as PHP) in a post, without the content being changed by the editor. This is incredibly useful for embedding third-party scripts, etc. The plugin is used by many large sites, including Mozilla. 

Key features include...

* Add HTML or JavaScript to posts or pages - particularly useful for embedding videos!
* Embed in widgets using the [Widget Logic](http://wordpress.org/extend/plugins/widget-logic/ "Widget Logic") plugin
* Global embedding allows you set up some code in one post or page and then access it from another
* Modify the keywords or identifiers used for embedding the code to your own choice
* Search for embedding code via a simple search option
* Add a simple suffix to the embed code to convert videos to responsive output
* Embed an external script directly using just the URL
* And much, much more!

Iconography is courtesy of the very talented [Janki Rathod](https://www.linkedin.com/in/jankirathore/) ♥️

👉 Please visit the [Github page](https://github.com/dartiss/code-embed "Github") for the latest code development, planned enhancements and known issues 👈

== Getting Started ==

Here's how easy it is to use…

1. Once you have the plugin installed start a new post or page.
2. In the `Custom Fields` meta box enter a name of CODE1 and your embed code as the value. Save this.
3. In your post add `{{CODE1}}` where you wish the embed code to appear.

And that's it - when the post or page is viewed `{{CODE1}}` will be replaced with the code that you asked to be embedded.

**If you cannot find the location of the `Custom Fields` meta box on your post editor screen, please see the FAQ section for more information**

This should get you started - for more information and advanced options please see below.. Alternatively, there's a fantastic guide at [Elftronix](http://www.elftronix.com/free-easy-plugin-add-javascript-to-wordpress-posts-pages/ "Free Easy Plugin! Add Javascript to WordPress Posts & Pages") which I would recommend.

Although this plugin works for both posts and pages for simplicity I will simply refer to posts - bear in mind that pages work in the same way.

== Options Screen ==

In the administration menu there is a settings option named "Code Embed".

Code embedding is performed via a special keyword that you must use to uniquely identify where you wish the code to appear. This consist of an opening identifier (some that that goes at the beginning), a keyword and then a closing identifier. You may also add a suffix to the end of the keyword if you wish to embed multiple pieces of code within the same post.

From this options screen you can specify the above identifier that you wish to use. By default the opening and closing identifiers are percentage signs and the keyword is `CODE`. During these instructions these will be used in all examples.

The options screen is only available to those that with a capability of able to manage options or greater. All the other Code Embed menu options are available to users with a capability to edit posts or greater.

== Embedding ==

To embed in a post you need to find the meta box under the post named "Custom Fields". If this is missing you may need to add it by clicking on the "Screen Options" tab at the top of the new post screen.

Now create a new custom field with the name of your keyword - e.g. `CODE`. The value of this field will be the code that you wish to embed. Save this custom field.

Now, wherever you wish the code to appear in your post, simply put the full identifier (opening, keyword and closing characters). For example, `{{CODE}}`.

If you wish to embed multiple pieces of code within a post you can add a suffix to the keyword. So we may set up 2 custom fields named `CODE1` and `CODE2`. Then in our post we would specify either `{{CODE1}}` or `{{CODE2}}` depending on which you wish to display.

Don't forget - via the options screen you can change any part of this identifier to your own taste.

== URL Embedding ==

If you specify a URL within your post, surrounded by your choice of identifiers, then the contents of the URL will be embedded within your post.

Obviously, be careful when embedding a URL that you have no control over, as this may be used to hijack your post by injecting, for example, dangerous JavaScript.

For example, using the default options you could embed the contents of a URL using the following method...

`{{http://www.example.com/code.php}}`

or

`{{https://www.example.com/code.html}}`

== Global Embedding ==

You can also create global embeds - that is creating one piece of embed code and using it in multiple posts or pages.

To do this simply make reference to an already defined (but unique) piece of embed code from another post or page.

So, let's say in one post you define a custom field named `CODE1`. You can, if you wish, place `{{CODE1}}` not just in that post but also in another and it will work.

However, bear in mind that the embed code name must be unique - you can't have defined it in multiple posts otherwise the plugin won't know which one you're referring to (although it will report this and list the posts that it has been used in).

In the administration menu there is a sidebar menu named "Tools". Under this is a sub-menu named "Code Search". Use this to search for specific embed names and it will list all the posts/pages that they're used on, along with the code for each.

== Embedding in Widgets ==

Natively you cannot use the embed facilities within sidebar widgets. However, if you install the plugin [Widget Logic](http://wordpress.org/extend/plugins/widget-logic/ "Widget Logic") then Code Embed has been set up to make use of this and add the ability.

* Install [Widget Logic](http://wordpress.org/extend/plugins/widget-logic/ "Widget Logic") and activate.
* In Administration, select the Widgets page from the Appearance menu. At the bottom there will be a set of Widget Logic options.
* Ensure Use 'widget_content' filter is ticked and press Save.

Although you cannot set up embed code within a widget you can make reference to it, for example by writing `{{CODE1}}` in the widget.

== Responsive Output Conversion ==

Responsive output is where an element on a web page dynamically resizes depending upon the current available size. Most video embeds, for instance, will be a fixed size. This is fine if your website is also of a fixed size, however if you have a responsive site then this is not suitable.

Code Embed provides a simple suffix that can be added to an embed code and will convert the output to being responsive. This works best with videos.

To use, when adding the embed code onto the page, simply add `_RES` to the end, before the final identifier. For example, `{{CODE1_RES}}`. The `_RES` should not be added to the custom fields definition.

This will now output the embedded code full width, but a width that is dynamic and will resize when required.

If you don't wish the output to be full width you can specify a maximum width by adding an additional `_x` on the end, where `x` is the required width in pixels. For example, `{{CODE1_RES_500}}` this will output `CODE1` as responsive but with a maximum width of 500 pixels.

**It should be noted that this is an experimental addition and will not work in all circumstances.**

== Embedding in excerpts ==

By default embed code will not appear in excerpts. However, you can switch this ability on via the Code Embed options screen. If you do this then the standard rules of excerpts will still apply, but now once the code embed has applied - for example, excerpts are just text, a specific length, etc.

== Reviews & Mentions ==

"Works like a dream. Fantastic!" - Anita.

"Thank you for this plugin. I tried numerous other iframe plugins and none of them would work for me! This plugin worked like a charm the FIRST time." - KerryAnn May.

[Embedding content](http://wsdblog.westbrook.k12.me.us/blog/2009/12/24/embedding-content/ "Embedding content") - WSD Blogging Server.

[Animating images with PhotoPeach](http://comohago.conectandonos.gov.ar/2009/08/05/animando-imagenes-con-photopeach/ "Animando imágenes con PhotoPeach") - Cómo hago.

== Installation ==

Code Embed can be found and installed via the Plugin menu within WordPress administration (Plugins -> Add New). Alternatively, it can be downloaded from WordPress.org and installed manually...

1. Upload the entire `simple-embed-code` folder to your `wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress administration.

Voila! It's ready to go.

== Frequently Asked Questions ==

== Can I use this plugin with the new block editor (aka Gutenberg) / WordPress 5.0 and above? ==

Yes, you can. By default, custom fields are hidden inside the block editor but can be revealed.

1. Edit a post
2. Click the settings button (three dots) in the top, right-hand corner
3. Go to Options
4. You will find a tick box to display the 'Custom Fields' meta box

= My code doesn't work =

If your code contains the characters `]]>` then you'll find that it doesn't - WordPress modifies this itself.

Otherwise, it's likely to be your code and not this plugin. The best way to confirm this is to look at the source of the page and compare the code output with what you embedded. Does it match? If it does, then your code is at fault.

= I can't find the Custom Fields meta box =

This 'meta box' is not added by this plugin but is built into WordPress. If you cannot find it on the post or page editor screen then click the 'Screen Options' tab in the top right-hand corner. Ensure 'Custom Fields' is ticked. Please see the screenshots section for an image on how this appears on a 'box fresh' installation of WordPress.

If you still can't find it then you may have a theme or plugin that removes this or may have a problem with your WordPress installation - you will need to try normal diagnostics to try and resolve this, including requesting help on [the WordPress support forum](https://wordpress.org/support/forum/how-to-and-troubleshooting/ "Fixing WordPress Forum"). It would be greatly appreciated if you don't give me poor reviews in this situation as, I say, this component is not part of this plugin but, by using it, keeps this plugin simple to use and bloat-free :)

= What's the maximum size of the embed code that I can save in a custom field? =

WordPress stores the custom field contents in a MySQL table using the `longtext` format. This can hold over 4 billion characters.

== Is this GDPR compliant? ==

It is, in that it doesn't save any data that could be odds with GDPR compliance (i.e. it's compliant by design). However, if you use this to embed third-party scripts, then those scripts may not be and you will need to speak to the providers for further details.

== Screenshots ==

1. The custom field meta box with a Code Embed field set up to show some YouTube embed code
2. Example embed code in a post
3. The resultant video from the previous example code
4. The options screen
5. The search screen, showing search results
6. The Custom Fields meta-box and the screen switch - both highlighted in red

== Changelog ==

I use symantic versioning.

= 2.3.4 =
* Bug: Fixed minor error that occurred due to the removal of the debug code in the last release. Sorry about that.

= 2.3.3 =
* Enhancement: I've removed the debug code. I allowed to be switched off but I've never used it and it may not have switched off properly anyway. So it's gone
* Enhancement: Added some additional plugin meta

= 2.3.2 =
* Bug: Fixed another pesky bug that was affecting embedded URLs. My code to do this was years old and I couldn't understand why I'd written it the way I had. So I've re-written it from scratch

= 2.3.1 =
* Bug: Fixed a variable naming issue that I may, or may not (I did), have created in the latest release

= 2.3 =
* Enhancement: All the code is now compliant with the full-fat VIP coding standards. It was no mean feat but, as a result, the plugin is more secure than ever before
* Enhancement: The default is to now use double braces around your embed name, which is kind-of the universal default for template tags such as this. If you're an existing user, your current configuration won't change, though - this only affects new users
* Enhancement: Improved translation output, including where I'd accidentally added an extra character to the text domain
* Enhancement: Using the `checked` function on fields, rather than the form parameter
* Enhancement: Added a useful links sidebar to the Help for both screens
* Maintenance: Throughout, use Yoda conditions I now do
* Maintenance: Added links to the sparkly new Github repo
* Bug: When updating the options you sometimes didn't get a confirmation message. You do now!
* Bug: Fixed a weird one where I was referencing a variable that I was never using ¯\_(ツ)_/¯

= 2.2.2 =
* Maintenance: Updated README to work better with new plugin directory format. Also, now converting all text to US English, which is the WordPress standard. Snazzy.
* Maintenance: Updated all links to artiss.blog and removed donation links. Clickable.
* Maintenance: Minimum WordPress level is now 4.6 for this plugin, meaning I could remove various pieces of code. Strong and stable.
* Maintenance: Lots of language updates, many of which are a consequence of the move to WordPress 4.6 (including removal of language files and links, etc). Verbacious.

= 2.2.1 =
* Maintenance: Updated branding, inc. adding donation links

= 2.2 =
* Enhancement: Added support for embedding code in excerpts
* Enhancement: Validated, sanitized and escaped the admin screen data
* Maintenance: Overhauled the way default options are fetched and/or generated. Now a lot more efficient
* Maintenance: Updated the admin screens so they are formatted in a similar way to the default WordPress screens
* Maintenance: Removed hardcoding of plugin folder
* Maintenance: Updated author and removed donation links
* Maintenance: Renamed files and file functions - removed prefix from files and update it on functions
* Maintenance: Added a domain path for translations

= 2.1.2 =
* Maintenance: Added missing text domain, ready for automatic translation.

= 2.1.1 =
* Maintenance: Updated help text
* Maintenance: Modified admin screen headings so they're compatible with WP4.3
* Enhancement: Added options to suppress debug output
* Enhancement: Added donation link to plugin meta. Go on, you know you want to...

= 2.1 =
* Maintenance: Updated plugin branding
* Maintenance: Removed feature pointer - no longer required
* Enhancement: Removed support screen and moved remaining admin screens
* Bug: Fixed issues with translations

= 2.0.2 =
* Enhancement: Fixed [minor XSS vulnerability](https://bugzilla.mozilla.org/show_bug.cgi?id=771315 "Bug 771315 - WP Plugin Simple-embed-Code - Fix XSS Before Adding to Hacks Blog") (kindly reported by Mozilla)
* Enhancement: Shows README appropriate to the current installed version, instead of the latest

= 2.0.1 =
* Enhancement: Removed restriction on embed code length

= 2.0 =
* Maintenance: Removed dashboard widget
* Maintenance: Further code tidying
* Maintenance: Added new code for contextual help to use new WP 3.3 elements
* Enhancement: New admin menu option, under which existing option screens now exist along with a support screen. If you have the [README Parser plugin](http://wordpress.org/extend/plugins/wp-readme-parser/ "README Parser") installed then it will also add a sub-menu displaying README instructions
* Enhancement: Added internationalization to code
* Enhancement: Will now work with widgets if you install the plugin [Widget Logic](http://wordpress.org/extend/plugins/widget-logic/ "Widget Logic")
* Enhancement: Added experimental ability to convert to responsive output
* Enhancement: Added option to specify a URL instead of an embed code
* Enhancement: Added feature pointer for when plugin is activated

= 1.6.1 =
* Bug: Fixed bug where name of plugin folder was incorrect

= 1.6 =
* Maintenance: Improved code further from 1.5, including separating code into separate includes
* Enhancement: Added global embeds option
* Enhancement: New tools option in the administration menu which allows you to search for code embeds

= 1.5.1 =
* Enhancement: Added form security

= 1.5 =
* Maintenance: Renamed plugin to bring in line with new plugin conventions
* Maintenance: Plugin re-write to create more efficient code - can now also completely personalize the embed code used in the post
* Maintenance: PHPDoc used throughout for documentation purposes, plus new coding standards
* Maintenance: Instructions completely re-written
* Enhancement: Support information improved, including contextual help on the settings screen (if supported)
versions of this plugin

= 1.4.1 =
* Bug: Version details as HTML comments were being output whether an embed existed or not - corrected

= 1.4 =
* Enhancement: Option screen which allows you to specify the maximum number of possible embeds per post and the embed word

= 1.3 =
* Enhancement: Increased limit of number of code embeds from 5 to 20

= 1.2 =
* Maintenance: Simplification of code

= 1.1 =
* Maintenance: The instructions have been corrected (thanks to John J. Camilleri for pointing it out!)
* Maintenance: Plugin has been tested with version 2.8 of WordPress. No code changes have been made

= 1.0 =
* Initial release

== Upgrade Notice ==

= 2.3.4 =
* Minor bug fix