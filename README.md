Next/Previous Navigation (Pagination) Plugin for Morfy CMS
==========================================================

Configuration
-------------

1. Put the `nextprev` folder to the `plugins` folder
2. Go to `config\system.yml` and add `nextprev` to the plugins section:
3. Save your changes.

~~~ .yml
plugins:
  nextprev
~~~

Usage
-----

Add this snippet to your `blog_post.tpl` that is placed in the `themes` folder to show the next/previous navigation:

~~~ .no-highlight
...

{Action::run('nextprev')}
~~~

Replace your posts loop in `blog.tpl` and/or `index.tpl` with this:

~~~ .no-highlight
{Action::run('nextprev')}
~~~

Done.

New Global Variable
-------------------

`$config.site.offset` will return the page offset.

This is basically equal to `$.get.page` and `$_GET['page']`. But since the `page` parameter URL is dynamic, you cannot use the `$.get.page` and `$_GET['page']` variable safely. Because if you replace the `param` configuration value with `foo` for example, then you have to replace `$.get.page` with `$.get.foo` and `$_GET['page']` with `$_GET['foo']`.