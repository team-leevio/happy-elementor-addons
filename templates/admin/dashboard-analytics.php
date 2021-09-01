<?php

/**
 * Dashboard credentials tab template
 */

defined('ABSPATH') || die();

$widgets = self::get_widgets();
$inactive_widgets = \Happy_Addons\Elementor\Widgets_Manager::get_inactive_widgets();
$used_widget = self::get_raw_usage();
$unuse_widget = self::get_un_usage();
// echo '<pre>';
// var_dump($used_widget);
// echo '</pre>';
?>
<div class="ha-dashboard-panel">
    <!-- <div class="ha-dashboard-panel__header">
        <div class="ha-dashboard-panel__header-content">
            <h2><?php //esc_html_e('Happy Analytics', 'happy-elementor-addons'); ?></h2>
        </div>
    </div> -->

	<h2><?php esc_html_e('Un-used Widgets', 'happy-elementor-addons'); ?></h2>
    <div class="ha-dashboard-credentials">
		<ul>

		<?php
        foreach ($unuse_widget as $key => $data) :

			?>
			<li> <?php echo $data; ?></li>
        <?php
        endforeach;
        ?>
		</ul>
    </div>

	<h2><?php esc_html_e('Used Widgets', 'happy-elementor-addons'); ?></h2>
    <div class="ha-dashboard-credentials">
		<ul>

		<?php
        foreach ($used_widget as $key => $data) :

			?>
			<li> <?php echo $key .' => '. $data; ?></li>
        <?php
        endforeach;
        ?>
		</ul>
    </div>

    
</div>
