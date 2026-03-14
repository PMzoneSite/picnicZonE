<?php
/**
 * Архив беседок (Custom Post Type)
 *
 * @package Picnic_Zone
 */

get_header();
?>

<section class="section pavilions" id="pavilions">
    <div class="container">
        <h2 class="section-title">Наши беседки</h2>
        <p class="section-subtitle">Выберите идеальную беседку для вашего мероприятия</p>
        <div class="pavilion-grid">
            <?php
            if (have_posts()) {
                $index = 0;
                while (have_posts()) {
                    the_post();
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
                    <article class="pavilion-card" data-id="<?php echo esc_attr($id); ?>" data-name="<?php echo esc_attr(get_the_title()); ?>">
                        <div class="pavilion-image">
                            <a href="<?php the_permalink(); ?>">
                                <?php if ($thumb) : ?>
                                    <img src="<?php echo esc_url($thumb); ?>" alt="<?php the_title_attribute(); ?>">
                                <?php else : ?>
                                    <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/img/placeholder.jpg" alt="<?php the_title_attribute(); ?>">
                                <?php endif; ?>
                            </a>
                            <?php if ($index === 1) : ?><span class="pavilion-badge">Популярно</span><?php endif; ?>
                        </div>
                        <div class="pavilion-content">
                            <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
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
                    </article>
                    <?php
                }
            } else {
                echo '<p class="section-subtitle">Беседки пока не добавлены.</p>';
            }
            ?>
        </div>
    </div>
</section>

<?php
get_footer();
