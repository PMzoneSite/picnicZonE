<?php
/**
 * Шапка сайта
 *
 * @package Picnic_Zone
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
    <link rel="icon" type="image/x-icon" href="https://img.icons8.com/color/96/000000/park.png">
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="header">
    <div class="container">
        <nav class="navbar">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="logo">
                <div class="logo-icon">
                    <i class="fas fa-tree"></i>
                </div>
                <div class="logo-text">
                    <span class="logo-title">Беседки 52</span>
                    <span class="logo-subtitle">Пикник ZonE</span>
                </div>
            </a>

            <button class="menu-toggle" id="menuToggle" aria-label="Меню">
                <i class="fas fa-bars"></i>
            </button>

            <ul class="nav-menu" id="navMenu">
                <li><a href="<?php echo esc_url(home_url('/#hero')); ?>">Главная</a></li>
                <li><a href="<?php echo esc_url(home_url('/#about')); ?>">О нас</a></li>
                <li><a href="<?php echo esc_url(get_post_type_archive_link('gazebos')); ?>">Беседки</a></li>
                <li><a href="<?php echo esc_url(home_url('/#services')); ?>">Услуги</a></li>
                <li><a href="<?php echo esc_url(home_url('/#gallery')); ?>">Галерея</a></li>
                <li><a href="<?php echo esc_url(home_url('/#contacts')); ?>">Контакты</a></li>
                <li><button type="button" class="btn btn-small btn-book btn-nav" data-id="1" data-name="Беседка №1">Забронировать</button></li>
            </ul>
        </nav>
    </div>
</header>
