<?php
/**
 * Пикник ZonE — Функции темы
 *
 * @package Picnic_Zone
 */

if (!defined('ABSPATH')) {
    exit;
}

define('PICNIC_ZONE_VERSION', '1.0');

/**
 * Подключение стилей и скриптов
 */
function picnic_zone_scripts() {
    $theme_uri = get_template_directory_uri();

    wp_enqueue_style(
        'picnic-zone-fonts',
        'https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Open+Sans:wght@300;400;600&display=swap',
        array(),
        null
    );
    wp_enqueue_style(
        'font-awesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css',
        array(),
        '6.4.0'
    );
    wp_enqueue_style(
        'picnic-zone-style',
        $theme_uri . '/assets/css/style.css',
        array(),
        PICNIC_ZONE_VERSION
    );

    wp_enqueue_script(
        'picnic-zone-script',
        $theme_uri . '/assets/js/script.js',
        array(),
        PICNIC_ZONE_VERSION,
        true
    );

    wp_localize_script('picnic-zone-script', 'picnicZone', array(
        'crmApiUrl' => esc_url(home_url('/api/crm-lead')),
        'ajaxUrl'   => admin_url('admin-ajax.php'),
    ));
}
add_action('wp_enqueue_scripts', 'picnic_zone_scripts');

/**
 * Регистрация Custom Post Type «Беседки»
 */
require get_template_directory() . '/inc/cpt-gazebos.php';
