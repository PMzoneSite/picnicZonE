<?php
/**
 * Главная страница (лендинг)
 *
 * @package Picnic_Zone
 */

get_header();
?>

<!-- Герой-секция -->
<section class="hero" id="hero" style="background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('<?php echo esc_url(get_template_directory_uri()); ?>/assets/img/bg.jpg') center/cover;">
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title">Отдых на природе <span class="highlight">с комфортом</span></h1>
            <p class="hero-text">Аренда беседок для пикников, корпоративов и семейных праздников. Уют, чистота и все необходимое для отличного отдыха.</p>
            <div class="hero-buttons">
                <a href="<?php echo esc_url(get_post_type_archive_link('gazebos')); ?>" class="btn btn-primary">Выбрать беседку</a>
                <a href="#contacts" class="btn btn-secondary">Наши контакты</a>
            </div>
        </div>
    </div>
</section>

<!-- О нас -->
<section class="section about" id="about">
    <div class="container">
        <h2 class="section-title">Почему выбирают нас</h2>
        <p class="section-subtitle">Мы создаем идеальные условия для вашего отдыха на природе</p>
        <div class="features">
            <div class="feature">
                <div class="feature-icon"><i class="fas fa-home"></i></div>
                <h3>Уютные беседки</h3>
                <p>Просторные и комфортабельные беседки на 10-40 человек, оборудованные всем необходимым.</p>
            </div>
            <div class="feature">
                <div class="feature-icon"><i class="fas fa-utensils"></i></div>
                <h3>Полная комплектация</h3>
                <p>Мангалы, гриль, столы, скамейки, освещение, розетки — все для удобного отдыха.</p>
            </div>
            <div class="feature">
                <div class="feature-icon"><i class="fas fa-tree"></i></div>
                <h3>Живописная природа</h3>
                <p>Расположение в зеленой зоне, чистый воздух и ухоженная территория.</p>
            </div>
            <div class="feature">
                <div class="feature-icon"><i class="fas fa-car"></i></div>
                <h3>Удобная парковка</h3>
                <p>Бесплатная охраняемая парковка для всех гостей.</p>
            </div>
        </div>
    </div>
</section>

<!-- Наши беседки -->
<section class="section pavilions" id="pavilions">
    <div class="container">
        <h2 class="section-title">Наши беседки</h2>
        <p class="section-subtitle">Выберите идеальную беседку для вашего мероприятия</p>
        <div class="pavilion-grid">
            <?php
            $gazebos = new WP_Query(array(
                'post_type'      => 'gazebos',
                'posts_per_page' => -1,
                'orderby'        => 'menu_order',
                'order'          => 'ASC',
            ));

            if ($gazebos->have_posts()) {
                $index = 0;
                while ($gazebos->have_posts()) {
                    $gazebos->the_post();
                    $index++;
                    $id = get_the_ID();
                    $area = get_post_meta($id, '_gazebo_area', true);
                    $capacity = get_post_meta($id, '_gazebo_capacity', true);
                    $price = get_post_meta($id, '_gazebo_price', true);
                    $amenities = get_post_meta($id, '_gazebo_amenities', true);
                    if (!is_array($amenities)) {
                        $amenities = array();
                    }
                    $thumb = get_the_post_thumbnail_url($id, 'large');
                    ?>
                    <div class="pavilion-card" data-id="<?php echo esc_attr($id); ?>" data-name="<?php echo esc_attr(get_the_title()); ?>">
                        <div class="pavilion-image">
                            <?php if ($thumb) : ?>
                                <img src="<?php echo esc_url($thumb); ?>" alt="<?php the_title_attribute(); ?>">
                            <?php else : ?>
                                <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/img/placeholder.jpg" alt="<?php the_title_attribute(); ?>">
                            <?php endif; ?>
                            <?php if ($index === 1) : ?><span class="pavilion-badge">Популярно</span><?php endif; ?>
                        </div>
                        <div class="pavilion-content">
                            <h3><?php the_title(); ?></h3>
                            <?php the_excerpt(); ?>
                            <ul class="pavilion-features">
                                <?php if ($area) : ?><li><i class="fas fa-ruler-combined"></i> <?php echo esc_html($area); ?>м²</li><?php endif; ?>
                                <?php if ($capacity) : ?><li><i class="fas fa-users"></i> До <?php echo esc_html($capacity); ?> человек</li><?php endif; ?>
                                <?php foreach (array_slice($amenities, 0, 2) as $a) : ?>
                                    <li><i class="fas fa-check"></i> <?php echo esc_html($a); ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <div class="pavilion-price">
                                <?php if ($price) : ?>
                                    <span class="price"><?php echo esc_html(number_format_i18n($price)); ?> руб./сутки</span>
                                <?php else : ?>
                                    <span class="price">—</span>
                                <?php endif; ?>
                                <button type="button" class="btn btn-small btn-book" data-id="<?php echo esc_attr($id); ?>" data-name="<?php echo esc_attr(get_the_title()); ?>">Забронировать</button>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                wp_reset_postdata();
            }
            ?>
        </div>
    </div>
</section>

<!-- Услуги -->
<section class="section services" id="services">
    <div class="container">
        <h2 class="section-title">Дополнительные услуги</h2>
        <p class="section-subtitle">Сделаем ваш отдых еще комфортнее</p>
        <div class="services-grid">
            <div class="service-card">
                <div class="service-icon"><i class="fas fa-hamburger"></i></div>
                <h3>Кейтеринг</h3>
                <p>Заказ готовых блюд, напитков и закусок. Полный сервис питания для вашего мероприятия.</p>
            </div>
            <div class="service-card">
                <div class="service-icon"><i class="fas fa-music"></i></div>
                <h3>Музыкальное оборудование</h3>
                <p>Аренда колонок, микрофонов и музыкальной аппаратуры для проведения праздников.</p>
            </div>
            <div class="service-card">
                <div class="service-icon"><i class="fas fa-gamepad"></i></div>
                <h3>Развлечения</h3>
                <p>Настольные игры, бадминтон, мячи и другие активные развлечения для детей и взрослых.</p>
            </div>
            <div class="service-card">
                <div class="service-icon"><i class="fas fa-snowflake"></i></div>
                <h3>Холодильное оборудование</h3>
                <p>Аренда холодильников и морозильных камер для хранения продуктов и напитков.</p>
            </div>
        </div>
    </div>
</section>

<!-- Галерея -->
<section class="section gallery" id="gallery">
    <div class="container">
        <h2 class="section-title">Галерея</h2>
        <p class="section-subtitle">Наша территория и беседки</p>
        <div class="gallery-grid">
            <?php
            $gallery = new WP_Query(array(
                'post_type'      => 'gazebos',
                'posts_per_page' => 4,
                'post_status'    => 'publish',
            ));
            if ($gallery->have_posts()) {
                while ($gallery->have_posts()) {
                    $gallery->the_post();
                    $thumb = get_the_post_thumbnail_url(get_the_ID(), 'medium');
                    if ($thumb) :
                        ?>
                        <div class="gallery-item">
                            <img src="<?php echo esc_url($thumb); ?>" alt="<?php the_title_attribute(); ?>">
                        </div>
                        <?php
                    endif;
                }
                wp_reset_postdata();
            }
            ?>
        </div>
    </div>
</section>

<!-- Контакты -->
<section class="section contacts" id="contacts">
    <div class="container">
        <h2 class="section-title">Контакты</h2>
        <p class="section-subtitle">Свяжитесь с нами для уточнения деталей и бронирования</p>
        <div class="contacts-grid contacts-only">
            <div class="contact-info">
                <h3>Наши контакты</h3>
                <div class="contact-item">
                    <div class="contact-icon"><i class="fas fa-phone"></i></div>
                    <div class="contact-text">
                        <h4>Телефон</h4>
                        <p><a href="tel:+78001234567">+7 (XXX) XXX-XX-XX</a></p>
                    </div>
                </div>
                <div class="contact-item">
                    <div class="contact-icon"><i class="fas fa-envelope"></i></div>
                    <div class="contact-text">
                        <h4>Email</h4>
                        <p><a href="mailto:info@example.com">info@example.com</a></p>
                    </div>
                </div>
                <div class="contact-item">
                    <div class="contact-icon"><i class="fas fa-map-marker-alt"></i></div>
                    <div class="contact-text">
                        <h4>Адрес</h4>
                        <p>г. Москва, ул. Примерная, д. 1</p>
                    </div>
                </div>
                <div class="contact-item">
                    <div class="contact-icon"><i class="fas fa-clock"></i></div>
                    <div class="contact-text">
                        <h4>Часы работы</h4>
                        <p>Круглосуточно</p>
                    </div>
                </div>
                <div class="contact-item contact-social">
                    <h4>Социальные сети</h4>
                    <div class="social-icons">
                        <a href="#" aria-label="ВКонтакте"><i class="fab fa-vk"></i></a>
                        <a href="#" aria-label="Telegram"><i class="fab fa-telegram"></i></a>
                        <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Модальное окно бронирования -->
<div class="modal-overlay" id="bookingModal" aria-hidden="true">
    <div class="modal" role="dialog" aria-labelledby="modalTitle" aria-modal="true">
        <button type="button" class="modal-close" id="modalClose" aria-label="Закрыть окно"><i class="fas fa-times"></i></button>
        <h2 class="modal-title" id="modalTitle">Бронирование беседки</h2>
        <p class="modal-subtitle" id="modalSubtitle"></p>
        <form id="bookingModalForm" class="booking-form">
            <input type="hidden" id="bookingPavilionId" name="id_besedki">
            <input type="hidden" id="bookingPavilionName" name="nazvanie_besedki">
            <div class="form-row">
                <div class="form-group">
                    <label for="dateStart">Дата начала *</label>
                    <input type="date" id="dateStart" name="data_nachala" required>
                </div>
                <div class="form-group">
                    <label for="dateEnd">Дата окончания *</label>
                    <input type="date" id="dateEnd" name="data_okonchaniya" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="timeStart">Время начала *</label>
                    <input type="time" id="timeStart" name="vremya_nachala" required>
                </div>
                <div class="form-group">
                    <label for="timeEnd">Время окончания *</label>
                    <input type="time" id="timeEnd" name="vremya_okonchaniya" required>
                </div>
            </div>
            <div class="form-group">
                <label for="clientName">Ваше имя *</label>
                <input type="text" id="clientName" name="imya_klienta" required maxlength="100">
                <span class="form-error" id="nameError"></span>
            </div>
            <div class="form-group">
                <label for="clientPhone">Номер телефона *</label>
                <input type="tel" id="clientPhone" name="telefon_klienta" placeholder="+7 (___) ___-__-__" required>
                <span class="form-error" id="phoneError"></span>
            </div>
            <div class="form-status" id="formStatus" aria-live="polite"></div>
            <button type="submit" class="btn btn-primary btn-block" id="submitBookingBtn">Отправить заявку</button>
        </form>
    </div>
</div>

<?php
get_footer();
