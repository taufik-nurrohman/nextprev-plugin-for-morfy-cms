<?php

/**
 * Next & Previous Navigation for Morfy CMS
 *
 * @package Morfy
 * @subpackage Plugins
 * @author Taufik Nurrohman <http://latitudu.com>
 * @copyright 2014 Romanenko Sergey / Awilum
 * @version 1.0.4
 *
 */

// Include `shell.css` in header
// Uncomment the hook function below if you are not using Bootstrap.

// Morfy::factory()->addAction('theme_header', function() {
//     echo '<link href="' . Morfy::$config['site_url'] . '/plugins/nextprev/lib/css/shell.css" rel="stylesheet">' . "\n";
// });

// For posts listing page
// Usage => Morfy::factory()->runAction('index_nextprev');
Morfy::factory()->addAction('index_nextprev', function() {

    // Configuration data
    $config = Morfy::$config['nextprev_config'];
    // Get current URI segments
    $path = Morfy::factory()->getUriSegments();
    $path = implode('/', $path);
    // Number of posts to display per page
    $per_page = isset($config['limit']) ? $config['limit'] : 5;
    // Get all posts
    $all_posts = Morfy::factory()->getPages(CONTENT_PATH . '/' . $path . '/', 'date', 'DESC', array('404', 'index'));
    // Calculate total pages
    $total_pages = ceil(count($all_posts) / $per_page);
    // Get current page offset
    $current_page = isset($_GET[$config['param']]) ? $_GET[$config['param']] : 1;
    // Split all posts into chunks
    $posts = is_array($all_posts) ? array_chunk($all_posts, $per_page) : array();

    // Posts loop
    if(isset($posts[$current_page - 1]) && ! empty($posts[$current_page - 1])) {
        foreach($posts[$current_page - 1] as $post) {
            echo '<div class="' . $config['classes']['page_item'] . '">';
            echo $post['title'] ? '<h3><a href="' . $post['url'] . '">' . $post['title'] . '</a></h3>' : "";
            echo $post['date'] ? '<p><em><strong>Published on:</strong> ' . $post['date'] . '</em></p>' : "";
            if(strlen($post['description']) > 0) {
                echo '<p>' . $post['description'] . '</p>';
            } elseif(strlen($post['content_short']) > 0) {
                echo '<p>' . $post['content_short'] . '</p>';
            }
            echo '</div>';
        }
    } else {
        echo '<div class="' . $config['classes']['page_item'] . '">' . $config['labels']['not_found'] . '</div>';
    }

    // Build the pagination
    $html  = '<ul class="' . $config['classes']['nav'] . '">';
    $html .= $current_page > 1 ? '<li class="' . $config['classes']['nav_prev'] . '"><a href="?' . $config['param'] . '=' . ($current_page - 1) . '">' . $config['labels']['nav_prev'] . '</a></li>' : '<li class="' . $config['classes']['nav_prev'] . ' ' . $config['classes']['nav_disabled'] . '"><span>' . $config['labels']['nav_prev'] . '</span></li>';
    $html .= $current_page < $total_pages ? ' <li class="' . $config['classes']['nav_next'] . '"><a href="?' . $config['param'] . '=' . ($current_page + 1) . '">' . $config['labels']['nav_next'] . '</a></li>' : ' <li class="' . $config['classes']['nav_next'] . ' ' . $config['classes']['nav_disabled'] . '"><span>' . $config['labels']['nav_next'] . '</span></li>';
    $html .= '</ul>';

    echo $html;

});

// For single article
// Usage => Morfy::factory()->runAction('item_nextprev');
Morfy::factory()->addAction('item_nextprev', function() {

    // Configuration data
    $config = Morfy::$config['nextprev_config'];
    // Get current URI segments
    $path = Morfy::factory()->getUriSegments();
    array_pop($path);
    $path = implode('/', $path);
    // Get all posts
    $all_posts = Morfy::factory()->getPages(CONTENT_PATH . '/' . $path . '/', 'date', 'DESC', array('404', 'index'));
    // Count total posts
    $total_posts = count($all_posts);
    // Get current page URL
    $current_url = Morfy::factory()->getUrl();
    // Get current page data
    $current_page = Morfy::factory()->getPage($current_url);
    // Testing...
    // echo $current_page['date'];

    // Find next and previous link from current page
    for($i = 0; $i < $total_posts; $i++) {
        if($current_page['date'] == $all_posts[$i]['date']) {
            $prev_page = isset($all_posts[$i - 1]['url']) && ! empty($all_posts[$i - 1]['url']) ? $all_posts[$i - 1]['url'] : null;
            $next_page = isset($all_posts[$i + 1]['url']) && ! empty($all_posts[$i + 1]['url']) ? $all_posts[$i + 1]['url'] : null;
        } else {
            $prev_page = $next_page = null;
        }
    }

    // Build the pagination
    $html  = '<ul class="' . $config['classes']['nav'] . '">';
    $html .= $prev_page !== null ? '<li class="' . $config['classes']['nav_prev'] . '"><a href="' . $prev_page . '">' . $config['labels']['nav_prev'] . '</a></li>' : '<li class="' . $config['classes']['nav_prev'] . ' ' . $config['classes']['nav_disabled'] . '"><span>' . $config['labels']['nav_prev'] . '</span></li>';
    $html .= $next_page !== null ? ' <li class="' . $config['classes']['nav_next'] . '"><a href="' . $next_page . '">' . $config['labels']['nav_next'] . '</a>' : ' <li class="' . $config['classes']['nav_next'] . ' ' . $config['classes']['nav_disabled'] . '"><span>' . $config['labels']['nav_next'] . '</span></li>';
    $html .= '</ul>';

    echo $html;

});
