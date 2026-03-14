<?php
/**
 * Шаблон отдельной беседки
 *
 * @package Picnic_Zone
 */

get_header();
?>

<?php
while (have_posts()) {
    the_post();
    $id = get_the_ID();
    $area = get_post_meta($id, '_gazebo_area', true);
    $capacity = get_post_meta($id, '_gazebo_capacity', true);
    $price = get_post_meta($id, '_gazebo_price', true);
    $amenities = get_post_meta($id, '_gazebo_amenities', true);
    if (!is_array($amenities)) {
        $amenities = array();
    }
    $thumb = get_the_post_thumbnail_url($id, 'large');
    $gallery = get_post_meta($id, '_gazebo_gallery', true);
    ?>
    <section class="section single-gazebo">
        <div class="container">
            <a href="<?php echo esc_url(get_post_type_archive_link('gazebos')); ?>" class="back-link">&larr; Назад к беседкам</a>

            <article class="pavilion-card pavilion-single" data-id="<?php echo esc_attr($id); ?>" data-name="<?php echo esc_attr(get_the_title()); ?>">
                <div class="pavilion-image pavilion-image-large">
                    <?php if ($thumb) : ?>
                        <img src="<?php echo esc_url($thumb); ?>" alt="<?php the_title_attribute(); ?>">
                    <?php else : ?>
                        <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/img/placeholder.jpg" alt="<?php the_title_attribute(); ?>">
                    <?php endif; ?>
                </div>
                <div class="pavilion-content">
                    <h1><?php the_title(); ?></h1>
                    <div class="pavilion-meta">
                        <?php if ($area) : ?><span><i class="fas fa-ruler-combined"></i> <?php echo esc_html($area); ?> м²</span><?php endif; ?>
                        <?php if ($capacity) : ?><span><i class="fas fa-users"></i> До <?php echo esc_html($capacity); ?> чел.</span><?php endif; ?>
                        <?php if ($price) : ?><span class="price"><i class="fas fa-tag"></i> <?php echo esc_html(number_format_i18n($price)); ?> руб./сутки</span><?php endif; ?>
                    </div>
                    <div class="pavilion-description">
                        <?php the_content(); ?>
                    </div>
                    <?php if (!empty($amenities)) : ?>
                        <h3>Удобства</h3>
                        <ul class="pavilion-features">
                            <?php foreach ($amenities as $a) : ?>
                                <li><i class="fas fa-check"></i> <?php echo esc_html($a); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                    <div class="pavilion-actions">
                        <button type="button" class="btn btn-primary btn-book" data-id="<?php echo esc_attr($id); ?>" data-name="<?php echo esc_attr(get_the_title()); ?>">Забронировать</button>
                    </div>
                </div>
            </article>
        </div>
    </section>
    <?php
}
?>

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
