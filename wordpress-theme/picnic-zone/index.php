<?php
/**
 * Главный шаблон
 *
 * @package Picnic_Zone
 */

get_header();
?>

<main>
    <?php
    if (have_posts()) {
        while (have_posts()) {
            the_post();
            the_content();
        }
    }
    ?>
</main>

<?php
get_footer();
