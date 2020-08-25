<?php

namespace WpLinks;

/**
 * Основной класс
 */
class WpLinks
{

    private $searcher;

    public function __construct(SearchInterface $searcher) {
        $this->searcher = $searcher;
        load_theme_textdomain('wplinks', WPLINK_DIR . '/languages');
        $this->initHooks();
    }

    public function initHooks()
    {
        add_action( 'admin_menu', [$this, 'addMenupageToAdminpanel'] );
        add_action( 'admin_enqueue_scripts', [$this, 'wpLinksScripts'] );
        add_filter( 'the_content', [$this, 'findLinks'] );
    }

    /**
     * Страница настроек
     */
    public function addMenupageToAdminpanel()
    {
        add_menu_page(__('Settings', 'wplinks'), __('WP links', 'wplinks'), 'manage_options', 'wplinks', [$this, 'addWplinksSettingsPage'], 'dashicons-admin-users', "70");
    }

    public function addWplinksSettingsPage()
    {
        require_once WPLINK_DIR . '/WpLinks/admin/page-settings.php';
    }

    /**
     * Обработчик страницы, поиск ссылок в контенте
     */
    public function findLinks( $content )
    {
        $postTypes = (array)get_option('wplink_postTypes', []);
        $postType = get_post_type();

        if(!empty($postTypes) && in_array($postType, $postTypes)){
            $searcher = $this->searcher;
            $result = $searcher->findLinks($content);

            $searcher->findLinksInMeta(get_the_ID());
        }

        return $result ?? $content;
    }

    function wpLinksScripts() {
        wp_enqueue_style( 'wplinks-bootstrap-css', WPLINK_URL . '/assets/css/plugins.css', array() );

//        wp_enqueue_script('jquery');
//        wp_enqueue_script( 'wplinks-bootstrap-js', get_template_directory_uri() . '/assets/js/plugins.js', array(), null, true );

    }

    public function plugin_deactivation()
    {

    }

}
