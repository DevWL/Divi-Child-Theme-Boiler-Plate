<?php 

namespace My\Lib\AdminPages;

use My\Lib\Helpers\BaseWpAbstarct;

/**
 * Create a child theme admin page
 * 
 * @see https://www.youtube.com/watch?v=nvsK0cTH0So
 */
class AdminMenuPage extends BaseWpAbstarct
{
    public $title;
    public $menuTitle;
    public $slug;
    public $iconUrl = "";
    public $position = null;
    public $parentSlug = '';

    public $body = "";

    /**
     * User access capability
     * 
     * @see https://wordpress.org/support/article/roles-and-capabilities/#manage_options
     */
    const MANAGE_OPTIONS = 'manage_options';

    /**
     * Defeine all requierd params
     *
     * @param string $title
     * @param string $slug
     */
    public function __construct(string $title, string $slug, string $menuTitle, string $parentSlug = '')
    {
        $this->title = $title;
        $this->slug = $slug;
        $this->menuTitle = $menuTitle;
        $this->parentSlug = $parentSlug;

        if (!!!$this->parentSlug ) {
            $this->addAction('admin_menu', 'addAdminMenuPage');
        } else {
            $this->addAction('admin_menu', 'addSubMenuPage');
        } 
    }

    /**
     * Wrpaper function for add_menu_page
     *
     * @see WP add_menu_page(...) manual at https://developer.wordpress.org/reference/functions/add_menu_page/
     * @return void
     */
    public function addAdminMenuPage()
    {
        add_menu_page(
            $this->title,
            $this->menuTitle,
            self::MANAGE_OPTIONS,
            $this->slug,
            [$this, 'createPage'],
            $this->iconUrl, // string url or name fo the icon @see https://developer.wordpress.org/resource/dashicons/#editor-ol
            $this->position // integer|null
        );
    }

    /**
     * Add submenu menu admin page
     *
     * @param [type] $parentSlug
     * @return void
     */
    public function addSubMenuPage()
    {
        add_submenu_page( 
            $this->parentSlug, 
            $this->title,
            $this->menuTitle, 
            self::MANAGE_OPTIONS, 
            $this->slug, 
            [$this, 'createPage'],
            $this->position 
        );
    }

    public function addRawContent(string $content)
    {
        $this->body .= $content;
    }

    /**
     * Load php template file
     * 
     * @see wp setup example https://www.youtube.com/watch?v=W2KfdcHDO3Y&list=PLriKzYyLb28kpEnFFi9_vJWPf5-_7d3rX&index=4
     * @see rendering template files in php https://stackoverflow.com/questions/1312300/how-to-pass-parameters-to-php-template-rendered-with-include
     *
     * @return void
     */
    public function loadTemplate($pathToTemplate)
    {
        ob_start();
        include_once $pathToTemplate;
        $this->body .= ob_get_contents();
        ob_clean();
    }

    /**
     * Echo whole page body content
     *
     * @return void
     */
    public function createPage()
    {
        echo $this->body;
    }
}

