Next/Previous Navigation (Pagination) Plugin for Morfy CMS
==========================================================

Configuration
-------------

1. Put the `nextprev` folder to the `plugins` folder
2. Go to `config\site.yml` and add `nextprev` to the plugins section:
3. Save your changes.

~~~ .yml
# Site Plugins
plugins:
  nextprev
~~~

Usage
-----

Add this snippet to your `blog_post.tpl` that is placed in the `themes` folder to show the next/previous navigation:

~~~ .no-highlight
...

{Morfy::runAction('nextprev')}
~~~

Replace your posts loop in `blog.tpl` and/or `index.tpl` with this:

~~~ .no-highlight
{Morfy::runAction('nextprev')}
~~~

Done.

New Global Variable
-------------------

`$.site.offset` will return the page offset.

This is basically equal to `$.get.page`. But since the `page` parameter URL is dynamic, you cannot use the `$.get.page` variable safely. Because if you change the `param` configuration value to `foo` for example, then you have to replace `$.get.page` with `$.get.foo`.