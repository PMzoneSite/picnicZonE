<?php
/**
 * Подвал сайта
 *
 * @package Picnic_Zone
 */
?>
<footer class="footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-logo">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="logo">
                    <div class="logo-icon">
                        <i class="fas fa-tree"></i>
                    </div>
                    <div class="logo-text">
                        <span class="logo-title">Беседки 52</span>
                        <span class="logo-subtitle">Пикник ZonE</span>
                    </div>
                </a>
                <p class="footer-text">Отдых на природе с комфортом. Аренда беседок для любых мероприятий.</p>
            </div>

            <div class="footer-links">
                <h4>Меню</h4>
                <ul>
                    <li><a href="<?php echo esc_url(home_url('/#hero')); ?>">Главная</a></li>
                    <li><a href="<?php echo esc_url(home_url('/#about')); ?>">О нас</a></li>
                    <li><a href="<?php echo esc_url(get_post_type_archive_link('gazebos')); ?>">Беседки</a></li>
                    <li><a href="<?php echo esc_url(home_url('/#services')); ?>">Услуги</a></li>
                    <li><a href="<?php echo esc_url(home_url('/#gallery')); ?>">Галерея</a></li>
                    <li><a href="<?php echo esc_url(home_url('/#contacts')); ?>">Контакты</a></li>
                </ul>
            </div>

            <div class="footer-social">
                <h4>Мы в соцсетях</h4>
                <div class="social-icons">
                    <a href="#" aria-label="ВКонтакте"><i class="fab fa-vk"></i></a>
                    <a href="#" aria-label="Telegram"><i class="fab fa-telegram"></i></a>
                    <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                </div>
                <p class="footer-text">Подписывайтесь, чтобы быть в курсе акций и новостей!</p>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; <?php echo esc_html(gmdate('Y')); ?> "Беседки 52 — Пикник ZonE". Все права защищены.</p>
            <p>г. Москва, ул. Примерная, д. 1 | <a href="tel:+78001234567">+7 (XXX) XXX-XX-XX</a></p>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
