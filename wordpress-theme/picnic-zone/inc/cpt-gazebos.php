<?php
/**
 * Custom Post Type: Беседки (Gazebos)
 *
 * @package Picnic_Zone
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Регистрация CPT «Беседки»
 */
function picnic_zone_register_gazebos_cpt() {
    $labels = array(
        'name'               => 'Беседки',
        'singular_name'      => 'Беседка',
        'add_new'            => 'Добавить беседку',
        'add_new_item'       => 'Добавить новую беседку',
        'edit_item'          => 'Редактировать беседку',
        'new_item'           => 'Новая беседка',
        'view_item'          => 'Посмотреть беседку',
        'search_items'       => 'Искать беседки',
        'not_found'          => 'Беседки не найдены',
        'not_found_in_trash' => 'В корзине беседок не найдено',
        'menu_name'          => 'Беседки',
    );

    $args = array(
        'labels'              => $labels,
        'public'              => true,
        'has_archive'         => true,
        'rewrite'             => array('slug' => 'besedki'),
        'supports'            => array('title', 'editor', 'thumbnail', 'excerpt'),
        'menu_icon'           => 'dashicons-store',
        'show_in_rest'        => true,
    );

    register_post_type('gazebos', $args);
}
add_action('init', 'picnic_zone_register_gazebos_cpt');

/**
 * Регистрация метаполей для беседок
 */
function picnic_zone_register_gazebo_meta_boxes() {
    add_meta_box(
        'gazebo_details',
        'Параметры беседки',
        'picnic_zone_gazebo_meta_box_callback',
        'gazebos',
        'normal'
    );
}

/**
 * Вывод метабокса с полями
 */
function picnic_zone_gazebo_meta_box_callback($post) {
    wp_nonce_field('picnic_zone_save_gazebo_meta', 'picnic_zone_gazebo_nonce');

    $area      = get_post_meta($post->ID, '_gazebo_area', true);
    $capacity  = get_post_meta($post->ID, '_gazebo_capacity', true);
    $price     = get_post_meta($post->ID, '_gazebo_price', true);
    $amenities = get_post_meta($post->ID, '_gazebo_amenities', true);
    if (!is_array($amenities)) {
        $amenities = array();
    }

    $all_amenities = array(
        'Мангал',
        'Гриль',
        'Камин',
        'Кухонная зона',
        'Зона отдыха',
        'Освещение',
        'Розетки 220В',
        'Wi-Fi',
        'Парковка',
        'Вид на водоём',
    );
    ?>
    <table class="form-table">
        <tr>
            <th><label for="gazebo_area">Площадь (м²)</label></th>
            <td><input type="number" id="gazebo_area" name="gazebo_area" value="<?php echo esc_attr($area); ?>" min="0" step="1" class="regular-text"></td>
        </tr>
        <tr>
            <th><label for="gazebo_capacity">Вместимость (человек)</label></th>
            <td><input type="number" id="gazebo_capacity" name="gazebo_capacity" value="<?php echo esc_attr($capacity); ?>" min="0" step="1" class="regular-text"></td>
        </tr>
        <tr>
            <th><label for="gazebo_price">Цена (руб./сутки)</label></th>
            <td><input type="number" id="gazebo_price" name="gazebo_price" value="<?php echo esc_attr($price); ?>" min="0" step="100" class="regular-text"></td>
        </tr>
        <tr>
            <th>Удобства</th>
            <td>
                <?php foreach ($all_amenities as $amenity) : ?>
                    <label style="display: block; margin-bottom: 5px;">
                        <input type="checkbox" name="gazebo_amenities[]" value="<?php echo esc_attr($amenity); ?>"
                            <?php checked(in_array($amenity, $amenities)); ?>>
                        <?php echo esc_html($amenity); ?>
                    </label>
                <?php endforeach; ?>
            </td>
        </tr>
    </table>
    <?php
}
add_action('add_meta_boxes', 'picnic_zone_register_gazebo_meta_boxes');

/**
 * Сохранение метаполей
 */
function picnic_zone_save_gazebo_meta($post_id) {
    if (!isset($_POST['picnic_zone_gazebo_nonce']) ||
        !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['picnic_zone_gazebo_nonce'])), 'picnic_zone_save_gazebo_meta')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['gazebo_area'])) {
        update_post_meta($post_id, '_gazebo_area', absint($_POST['gazebo_area']));
    }
    if (isset($_POST['gazebo_capacity'])) {
        update_post_meta($post_id, '_gazebo_capacity', absint($_POST['gazebo_capacity']));
    }
    if (isset($_POST['gazebo_price'])) {
        update_post_meta($post_id, '_gazebo_price', absint($_POST['gazebo_price']));
    }
    if (isset($_POST['gazebo_amenities']) && is_array($_POST['gazebo_amenities'])) {
        $amenities = array_map('sanitize_text_field', wp_unslash($_POST['gazebo_amenities']));
        update_post_meta($post_id, '_gazebo_amenities', $amenities);
    }
}
add_action('save_post_gazebos', 'picnic_zone_save_gazebo_meta');
