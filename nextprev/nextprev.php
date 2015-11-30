<?php

/**
 * Next & Previous Navigation for Morfy CMS
 *
 * @package Morfy
 * @subpackage Plugins
 * @author Taufik Nurrohman <http://latitudu.com>
 * @copyright 2015 Romanenko Sergey / Awilum
 * @version 2.1.0
 *
 */

// Configuration data
$nextprev_c = Config::get('plugins.nextprev');

// Add global `$config.site.offset` variable
Config::set('site.offset', (int) Arr::get($_GET, $nextprev_c['param'], 1));

// Initialize template
$nextprev_t = Template::factory(__DIR__ . '/templates');

// Include `shell.css` in header
// Uncomment the hook function below if you are not using Bootstrap.
/*
Action::add('theme_header', function() {
    $url = str_replace(array(ROOT_DIR, DIRECTORY_SEPARATOR), array(Url::getBase(), '/'), PLUGINS_PATH);
    $url = $url . '/' . basename(__DIR__) . '/assets/css/shell.css';
    echo '<link href="' . $url . '" rel="stylesheet">' . "\n";
});
*/

// For posts listing page
// Usage => Action::run('nextprev.index');
Action::add('nextprev.index', function() use($nextprev_c, $nextprev_t) {

    // Get current URI segments
    $path = Url::getUriString();
    // Number of posts to display per page request
    $per_page = Arr::get($nextprev_c, 'limit', 5);
    // Get all posts
    $all_pages = Pages::getPages($path, 'date', 'DESC', array('404', 'index'));
    // Calculate total pages
    $total_pages = ceil(count($all_pages) / $per_page);
    // Get current page offset
    $current_page = Config::get('site.offset');
    // Split all posts into chunks
    $pages = is_array($all_pages) ? array_chunk($all_pages, $per_page) : array();

    if(isset($pages[$current_page - 1]) && ! empty($pages[$current_page - 1])) {
        // Posts loop
        foreach($pages[$current_page - 1] as $page) {
            $nextprev_t->display('post.tpl', array(
                'page' => $page
            ));
        }
        // Pagination
        $nextprev_t->display('nav.tpl', array(
            'current' => $current_page,
            'total' => $total_pages,
            'prev' => $current_page > 1 ? '?' . $nextprev_c['param'] . '=' . ($current_page - 1) : false,
            'next' => $current_page < $total_pages ? '?' . $nextprev_c['param'] . '=' . ($current_page + 1) : false
        ));
    } else {
        $nextprev_t->display('error.tpl');
    }

    unset($all_pages);

});

// For single article
// Usage => Action::run('nextprev.item');
Action::add('nextprev.item', function() use($nextprev_c, $nextprev_t) {

    // Get current URI segments
    $path = Url::getUriString();
    // Get all posts
    $all_pages = Pages::getPages(dirname($path), 'date', 'DESC', array('404', 'index'));
    // Count total posts
    $total_pages = count($all_pages);
    // Get current page data
    $current_page = Pages::getCurrentPage();
    // Testing...
    // echo $current_page['date'];

    // Find next and previous link from current page
    $prev_page = $next_page = false;
    for($i = 0; $i < $total_pages; $i++) {
        if( ! isset($current_page['date']) || ! isset($all_pages[$i]['date'])) continue;
        if($current_page['date'] === $all_pages[$i]['date']) {
            $prev_page = isset($all_pages[$i - 1]['url']) && ! empty($all_pages[$i - 1]['url']) ? $all_pages[$i - 1]['url'] : false;
            $next_page = isset($all_pages[$i + 1]['url']) && ! empty($all_pages[$i + 1]['url']) ? $all_pages[$i + 1]['url'] : false;
        }
    }

    unset($all_pages);

    // Pagination
    $nextprev_t->display('nav.tpl', array(
        'current' => 1,
        'total' => $total_pages,
        'prev' => $prev_page,
        'next' => $next_page
    ));

});

// Conditional action between `nextprev.index` and `nextprev.item`
// Usage => Action::run('nextprev');
Action::add('nextprev', function() {
    $path = Url::getUriString();
    Action::run('nextprev.' . (File::exists(STORAGE_PATH . '/pages/' . $path . '/index.md') ? 'index' : 'item'));
});