<?php

/**
 * Dashboard credentials tab template
 */

defined('ABSPATH') || die();

$widgets = self::get_widgets();
$inactive_widgets = \Happy_Addons\Elementor\Widgets_Manager::get_inactive_widgets();
$used_widget = self::get_raw_usage();
$unuse_widget = self::get_un_usage();

$total_widgets_count = count( $widgets );
$total_used_widget_count = count( $used_widget );
$total_unuse_widget_count = count( $unuse_widget );
echo '<pre>';
// var_dump($widgets);
// var_dump($total_widgets_count);
// var_dump($total_used_widget_count);
// var_dump($total_unuse_widget_count);
echo '</pre>';
?>
<div class="ha-dashboard-panel">

	<!-- Used Widget Analytics -->
	<div class="ha-dashboard-panel__header" style="padding-bottom: 25px;">
        <div class="ha-dashboard-panel__header-content">
            <h2 style="margin: 0 0 10px;"><?php esc_html_e( 'Used Widgets', 'happy-elementor-addons' ); ?></h2>
            <p class="f16" style="margin: 0 0;"><?php printf( esc_html__( 'You are yousing only %s %s widgets. %s', 'happy-elementor-addons' ), '<strong style="color:#562dd4;">', $total_used_widget_count,  '</strong>' ); ?></p>
        </div>
    </div>

    <div class="ha-dashboard-analytics" style="margin-bottom: 40px;">
		<?php
        foreach ($used_widget as $key => $data) :
			?>
			<div class="ha-dashboard-analytics__item">
                <h3 class="ha-dashboard-analytics__item-title"><?php echo $widgets[$key]['title'];?></h3>
				<span class="ha-dashboard-analytics__item-total-count">total use: <?php echo $data;?></span>
        	</div>
        <?php
        endforeach;
        ?>
		<?php //for ($i=0; $i < 10; $i++) { ?>
			<!-- <div class="ha-dashboard-analytics__item">
                <h3 class="ha-dashboard-analytics__item-title">Happy Tooltip</h3>
				<span class="ha-dashboard-analytics__item-total-count">total use: 3</span>
        	</div> -->
		<?php //} ?>
    </div>


	<!-- Unused Widget Analytics -->
	<div class="ha-dashboard-panel__header" style="padding-bottom: 25px;">
        <div class="ha-dashboard-panel__header-content">
            <h2 style="margin: 0 0 10px;"><?php esc_html_e( 'Unused Widgets', 'happy-elementor-addons' ); ?></h2>
            <p class="f16" style="margin: 0 0;"><?php printf( esc_html__( '%s %s widgets %s are unused right now. You can disable this to make the site faster.', 'happy-elementor-addons' ), '<strong style="color:#e2498a;">', $total_unuse_widget_count,  '</strong>' ); ?></p>
        </div>
    </div>

	<?php if( !empty($unuse_widget) ) :?>
    <div class="ha-dashboard-analytics">
		<?php
        foreach ($unuse_widget as $key => $data) :
			?>
			<div class="ha-dashboard-analytics__item">
                <h3 class="ha-dashboard-analytics__item-title"><?php echo $widgets[$data]['title'];?></h3>
				<span class="ha-dashboard-analytics__item-total-count">total use: 0</span>
        	</div>
        <?php
        endforeach;
        ?>
    </div>
	<?php endif;?>

	<hr>
	<hr>
	<h2><?php esc_html_e('Un-used Widgets', 'happy-elementor-addons'); ?></h2>
    <div class="ha-dashboard-analytics">
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
