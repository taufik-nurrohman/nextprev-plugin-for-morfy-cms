Next/Previous Navigation (Pagination) Plugin for Morfy CMS
==========================================================

Configuration
-------------

Place the `nextprev` folder with its contents in `plugins` folder. Then update your `config.php` file:

``` .php
<?php
    return array(

        ...
        ...
        ...

        'plugins' => array(
            'markdown',
            'sitemap',
            'nextprev' // <= Activation
        ),
        'nextprev_config' => array( // <= Configuration
            'param' => 'page', // <= Page parameter name in URL
            'limit' => 5, // <= Number of posts to display per page
            'classes' => array( // <= List of item's HTML classes
                'page_item' => 'page',
                'nav' => 'pager',
                'nav_prev' => 'previous',
                'nav_next' => 'next',
                'nav_disabled' => 'disabled'
            ),
            'labels' => array( // <= List of item's readable text or labels
                'nav_prev' => '&larr; Previous',
                'nav_next' => 'Next &rarr;',
                'not_found' => '<div class="alert alert-danger"><p>Not found.</p></div>'
            )
        )
    );
```

Usage
-----

Add this snippet to your `blog_post.html` that is placed in the `themes` folder to show the next/previous navigation:

``` .php
...

<?php Morfy::factory()->runAction('item_nextprev'); ?>
```

Replace all of your `index.html` code with this:

``` .php
<?php include 'header.html' ?>
<?php include 'navbar.html' ?>
<div class="container">
    <?php Morfy::factory()->runAction('theme_content_before'); ?>
	<?php Morfy::factory()->runAction('index_nextprev'); ?>
    <?php Morfy::factory()->runAction('theme_content_after'); ?>
</div>
<?php include 'footer.html' ?>
```

Done.
