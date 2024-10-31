=== re.place ===
Contributors: dor
Donate link: http://brownian.org.ua/?page_id=61
Tags: search and replace, filter, formatting, post, page, template, regular expression, regex, search, replace
Requires at least: 2.0.2
Tested up to: 2.8.3
Stable tag: 0.2.1

re.place adds content filter wich searches in posts for specified patterns
(regular expressions) and replaces matches found with specified strings.

== Description ==

re.place is a module wich acts as a content filter. It searches for pre-defined
regular expressions and replaces matches with pre-defined replacements.

It is possible to specify different replacements based on several
"replacement restrictions" --- for authenticated users and guests,
for pages and non-pages currently (see "Screenshots").

For example, you can implement the functionality of cross-linker module, while
adding the possibility to insert "target" parameter, "rel=nofollow", style
information, etc.

As another example, you can insert in your posts custom smiles or the like.

As another example, you can add custom markup to you blog, like
`Google(something)` would insert link to `www.google.com/search?q=something`,
`TouTube(youtube_id)` will insert `<object ...>`, wich displays that video,
etc-etc. See "Other notes" for more examples.

You can replace http links with some other stuff for guests, leaving as is for
authenticated users (see http://wordpress.org/support/topic/297405).

Every entry can have custom "order" number, so you can chain your replacements.

re.place stores entries in database.

Management GUI provided to list entries, add, edit or delete entries.

See changelog for some changes tracking.

== Installation ==

1. Unzip replace-<version>.zip to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Define your search/replacements via mgmt interface
1. Use your patterns in your posts or pages

== Frequently Asked Questions ==

= How to deinstall this plugin? =

Deactivate it and drop the database (the best way is via phpMyAdmin plugin).
And remove options `'re_place_%'`.

Sorry, no deinstaller yet.

== Screenshots ==

1. List of entries
2. List of entries with HTML characters escaped
3. Entry with regular expression search and replace with different replaces for authenticated and guests

== A brief examples ==

1. As a search field, use '`_&#8212;`' (*space* in place of `_`!),
   as a replace -- '`#160;&#8212;`'. This will insert non-breaking space
   before every em-dash.

   Mmm... You may wish even use '`<span
   style="letter-spacing:0.1ex;">#160;</span>&#8212;`' as a replase to insert
   narrower non-breaking space... If you love typography too much... And you
   can make space after dot wider...

2. Search for '`:-)`' and replace with:

   '`<img src="http://www.kolobok.us/smiles/standart/smile3.gif"' .
       'alt=":-)" title=":-) (nice smile -- http://www.kolobok.us/smiles/standart/smile3.gif)"' .
       'style="vertical-align: middle;" />`'

3. Search pattern: `YouTube\(([a-zA-Z_0-9\-]*)\)`

   Replace pattern:

    '`<object width="425" height="344" data="http://www.youtube.com/v/\1" type="application/x-shockwave-flash">
      <param name="movie" value="http://www.youtube.com/v/\1"></param>
    </object>`'

   This will allow you to use markups like `YouTube(TMCf7SNUb-Q)` to insert
   Youtube video fragments easily.

4. How to Mass Remove Link (mass deactivate URL Linked text inside posts)?
   (http://wordpress.org/support/topic/288020)

   Search pattern: `<a [^>]*>([^<]*)<\/a>`

   Replace pattern: `\1`

   This will "deactivate" all links in all posts
   (e.g. replace `<a href="...">Try!</a>` with just `Try!`).
   Note, you can activate them back, deactivating this `re.place` entry.

5. Want to insert drop caps easily?.. Use '`<drop>L</drop>orem ipsum...`' in your
   posts and replace it with `<span class="dropcap">\1</span>`'. Use angle brackets,
   and if you (accidentally) deactivate re.place, these "tags" won't mess you page.

6. New --- in v0.1.3 --- [beta-] feature: you can specify different replace patterns
   for authenticated users and "guests". Sorry, i'll update screenshots and doc
   ASAP. :-)
