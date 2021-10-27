<?php
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
	<?php echo Utils::get_meta_viewport( 'theme-builder' ); ?>
    <?php if (!current_theme_supports('title-tag')) : ?>
        <title>
            <?php echo wp_get_document_title(); ?>
        </title>
    <?php endif; ?>
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>

    <?php do_action('happyaddons/template/before_header'); ?>

    <div class="ekit-template-content-markup ekit-template-content-header ekit-template-content-theme-support">
        <?php
        $template = \Happy_Addons\Elementor\Theme_Builder::template_ids();
        echo \Happy_Addons\Elementor\Theme_Builder::render_builder_data($template[0]);
        ?>
    </div>
    <?php do_action('happyaddons/template/after_header'); ?>