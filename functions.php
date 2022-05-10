<?php
/**
 * Divi-child Theme functions and definitions.
 *
 * @category Child-theme
 * @package  Divi-child
 * @author   DevWL <w.liszkiewicz@gmail.com>
 * @license  all rights reserve
 * @link     https://developer.wordpress.org/themes/basics/theme-functions/
 */

require 'vendor/autoload.php';

use My\Lib\AdminPages\AdminMenuPage;
use My\Lib\CustomPosts\CustomPost;
use My\Lib\ScriptsEnqueue\EnqueStyle;
use My\Lib\ScriptsEnqueue\EnqueScript;

const MAINDIR = __DIR__;

/* Enqueue parent style using custom class wrapper*/
(new EnqueStyle(
    'parent-style',
    get_template_directory_uri() . '/style.css'
))->setVersion(1.1)->enqueue();

/* Enqueue child theme front css styles */
(new EnqueStyle(
    'child-style',
    get_stylesheet_directory_uri() . '/assets/css/custom-front.css',
    ['parent-style'],
    1.1,
    'all'
))->setDisableOnAdmin()->enqueue();

/* Enqueue child theme admin css styles */
(new EnqueStyle(
    'child-admin-style',
    get_stylesheet_directory_uri() . '/assets/css/custom-admin.css',
    ['parent-style'],
    1.1,
    'all'
))->setDisableOnFront()->enqueue();

/* Enqueue js scripts */
(new EnqueScript(
    'script', // label
    get_stylesheet_directory_uri() . '/assets/js/custom.js', // link to a js file 
    ['jquery'],  // scripts to be loaded before
    1.0, // version
    true // 
))->setVersion(1.1)->setDisableOnAdmin()->enqueue();

/* Register custom post */
new CustomPost();

/* Add Top Level Admin Menu Page */
$adminPage = new AdminMenuPage("Child Theme Admin", "child_theme_admin", "My Settings");
$adminPage->iconUrl = get_stylesheet_directory_uri(). '/assets/images/icons/wp-icon.png';
$adminPage->position = 110;

/* Add Top Level Admin Menu Page Content */
$adminSubPage1 = new AdminMenuPage("Child Theme Admin", "child_theme_admin", "Home", $adminPage->slug);
$adminSubPage1->addRawContent("<h1>{$adminPage->menuTitle} - {$adminSubPage1->menuTitle}</h1>");
$adminSubPage1->addRawContent("<p>This is my demo content</p>");
$adminSubPage1->body .= "asdasdasd";
$adminSubPage1->body .= "asjdajsdasjd";

/* Add Sub Page Admin Menu - Level down Page and Content */
$adminSubPage2 = new AdminMenuPage("Theme Admin", "child_theme_admin-settings", "Basics", $adminPage->slug);
$adminSubPage2->addRawContent("<h1>{$adminPage->menuTitle} - {$adminSubPage2->menuTitle}</h1>");
$adminSubPage2->addRawContent("Some content goes here");
$adminSubPage2->loadTemplate(get_stylesheet_directory().'/View/Admin/child-theme-setting-page-template.php');