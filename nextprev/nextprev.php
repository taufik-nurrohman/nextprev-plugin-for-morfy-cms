<?php

/**
 * Next & Previous Navigation for Morfy CMS
 *
 * @package Morfy
 * @subpackage Plugins
 * @author Taufik Nurrohman <http://latitudu.com>
 * @copyright 2015 Romanenko Sergey / Awilum
 * @version 2.0.0
 *
 */

// Configuration data
$nextprev_config = Morfy::$plugins['nextprev'];

// Initialize Fenom
$nextprev_template = Fenom::factory(
    PLUGINS_PATH . '/' . basename(__DIR__) . '/templates',
    CACHE_PATH . '/fenom',
    Morfy::$fenom
);

// Include `shell.css` in header
// Uncomment the hook function below if you are not using Bootstrap.
// Morfy::addAction('theme_header', function() {
//     echo '<link href="' . Morfy::$site['url'] . '/plugins/' . basename(__DIR__) . '/assets/css/shell.css" rel="stylesheet">' . "\n";
// });

// For posts listing page
// Usage => Morfy::runAction('index_nextprev');
Morfy::addAction('index_nextprev', function() use($nextprev_config, $nextprev_template) {

    // Get current URI segments
    $path = trim(Url::getUriString(), '/');
    // Number of posts to display per page request
    $per_page = isset($nextprev_config['limit']) ? $nextprev_config['limit'] : 5;
    // Get all posts
    $all_pages = Morfy::getPages($path, 'date', 'DESC', array('404', 'index'));
    // Calculate total pages
    $total_pages = ceil(count($all_pages) / $per_page);
    // Get current page offset
    $current_page = isset($_GET[$nextprev_config['param']]) ? (int) $_GET[$nextprev_config['param']] : 1;
    // Split all posts into chunks
    $pages = is_array($all_pages) ? array_chunk($all_pages, $per_page) : array();

    if(isset($pages[$current_page - 1]) && ! empty($pages[$current_page - 1])) {
        // Posts loop
        foreach($pages[$current_page - 1] as $page) {
            $nextprev_template->display('post.tpl', array(
                'config' => $nextprev_config,
                'page' => $page
            ));
        }
        // Pagination
        $nextprev_template->display('nav.tpl', array(
            'config' => $nextprev_config,
            'current' => $current_page,
            'total' => $total_pages,
            'prev' => $current_page > 1 ? '?' . $nextprev_config['param'] . '=' . ($current_page - 1) : false,
            'next' => $current_page < $total_pages ? '?' . $nextprev_config['param'] . '=' . ($current_page + 1) : false
        ));
    } else {
        $nextprev_template->display('error.tpl', array('config' => $nextprev_config));
    }

    unset($all_pages);

});

// For single article
// Usage => Morfy::runAction('item_nextprev');
Morfy::addAction('item_nextprev', function() use($nextprev_config, $nextprev_template) {

    // Get current URI segments
    $path = Url::getUriSegments();
    array_pop($path);
    $path = implode('/', $path);
    // Get all posts
    $all_pages = Morfy::getPages($path, 'date', 'DESC', array('404', 'index'));
    // Count total posts
    $total_pages = count($all_pages);
    // Get current page path
    $current_path = Url::getUriString();
    // Get current page data
    $current_page = Morfy::getPage($current_path);
    // Testing...
    // echo $current_page['date'];

    // Find next and previous link from current page
    $prev_page = $next_page = false;
    for($i = 0; $i < $total_pages; $i++) {
        if($current_page['date'] == $all_pages[$i]['date']) {
            $prev_page = isset($all_pages[$i - 1]['url']) && ! empty($all_pages[$i - 1]['url']) ? $all_pages[$i - 1]['url'] : false;
            $next_page = isset($all_pages[$i + 1]['url']) && ! empty($all_pages[$i + 1]['url']) ? $all_pages[$i + 1]['url'] : false;
        }
    }

    unset($all_pages);

    // Pagination
    $nextprev_template->display('nav.tpl', array(
        'config' => $nextprev_config,
        'current' => 1,
        'total' => $total_pages,
        'prev' => $prev_page,
        'next' => $next_page
    ));

});

// Conditional action between `index_nextprev` and `item_nextprev`
Morfy::addAction('nextprev', function() {
    $path = trim(Url::getUriString(), '/');
    Morfy::runAction((file_exists(PAGES_PATH . '/' . $path . '/index.md') ? 'index' : 'item') . '_nextprev');
});
