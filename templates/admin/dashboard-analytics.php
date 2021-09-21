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
// echo '<pre>';
// var_dump(implode(',',$unuse_widget));
// var_dump($unuse_widget);
// var_dump($inactive_widgets);
// var_dump($total_widgets_count);
// var_dump($total_used_widget_count);
// var_dump($total_unuse_widget_count);
// echo '</pre>';
?>
<div class="ha-dashboard-panel ha-dashboard-panel-analytics">

	<!-- Used Widget Analytics -->
	<div class="ha-dashboard-panel__header flex-content used-widgets">
        <div class="ha-dashboard-panel__header-content">
            <h2><?php esc_html_e( 'Used Widgets', 'happy-elementor-addons' ); ?></h2>
			<?php if( $total_used_widget_count ): ?>
				<p class="f16" style="margin: 0 0;"><?php printf( esc_html__( 'You are using only %s %s widgets. %s', 'happy-elementor-addons' ), '<strong>', $total_used_widget_count,  '</strong>' ); ?></p>
			<?php else: ?>
				<p class="f16"><?php printf( esc_html__( 'No used widget found!', 'happy-elementor-addons' ) ); ?></p>
			<?php endif; ?>
        </div>

        <div class="ha-dashboard-panel__header-summary">
            <div class="data"><?php printf( esc_html__('Total Widget: %s', 'happy-elementor-addons' ), $total_widgets_count);?></div>
            <div class="data"><?php printf( esc_html__('Used: %s', 'happy-elementor-addons' ), $total_used_widget_count);?></div>
            <div class="data"><?php printf( esc_html__('Unused: %s', 'happy-elementor-addons' ), $total_unuse_widget_count);?></div>
        </div>
    </div>

    <div class="ha-dashboard-analytics" style="margin-bottom: 40px;">
		<?php
        foreach ($used_widget as $key => $data) :
			?>
			<!-- <div class="ha-dashboard-analytics__item">
                <h3 class="ha-dashboard-analytics__item-title"><?php echo $widgets[$key]['title'];?></h3>
				<span class="ha-dashboard-analytics__item-total-count">total use: <?php echo $data;?></span>
        	</div> -->
            <div class="ha-dashboard-analytics__item">
                <fieldset>
				<?php
					if( isset( $widgets[$key]['is_pro'] ) && $widgets[$key]['is_pro'] ){
						printf( esc_html__('%sPRO%s', 'happy-elementor-addons' ), '<legend class="pro">', '</legend>');
					}else{
						printf( esc_html__('%sFREE%s', 'happy-elementor-addons' ), '<legend class="free">', '</legend>');
					}
				?>
                    <div class="widget_inner">
                        <div class="widget-title"><?php echo $widgets[$key]['title'];?></div>
                        <span class="ha-dashboard-analytics__item-total-count">total use: <?php echo $data;?></span>
                    </div>
                </fieldset>
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
	<div class="ha-dashboard-panel__header flex-content unused-widgets">
        <div class="ha-dashboard-panel__header-content">
			<h2><?php esc_html_e( 'Unused Widgets', 'happy-elementor-addons' ); ?></h2>
			<?php if( $total_unuse_widget_count ): ?>
            	<p class="f16"><?php printf( esc_html__( '%s %s widgets %s are unused right now. You can disable this to make the site faster.', 'happy-elementor-addons' ), '<strong>', $total_unuse_widget_count,  '</strong>' ); ?></p>
			<?php else: ?>
				<p class="f16"><?php printf( esc_html__( 'No unused widget found!', 'happy-elementor-addons' ) ); ?></p>
			<?php endif; ?>
        </div>
        <button id="ha-dashboard-analytics-disable" class="ha-dashboard-btn ha-dashboard-analytics__unused_disable" type="submit"><?php echo esc_html__( 'Disable all unused widget', 'happy-elementor-addons' ); ?></button>
		<!-- <input type="hidden" name="disable-unused-widgets" value="true<?php //echo $widget_key; ?>"> -->
    </div>

	<?php if( !empty($unuse_widget) ) :?>
    <div class="ha-dashboard-analytics">
		<?php
        foreach ($unuse_widget as $key => $data) :
			?>
            <div class="ha-dashboard-analytics__item">
                <fieldset>
				<?php
					if( isset( $widgets[$data]['is_pro'] ) && $widgets[$data]['is_pro'] ){
						printf( esc_html__('%sPRO%s', 'happy-elementor-addons' ), '<legend class="pro">', '</legend>');
					}else{
						printf( esc_html__('%sFREE%s', 'happy-elementor-addons' ), '<legend class="free">', '</legend>');
					}
				?>
                    <div class="widget_inner">
                        <div class="widget-title"><?php echo $widgets[$data]['title'];?></div>
                        <span class="ha-dashboard-analytics__item-total-count"><?php echo esc_html('total use: 0');?></span>
                    </div>
                </fieldset>
            </div>
        <?php
        endforeach;
        ?>
    </div>
	<?php endif;?>







	<!--
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
    </div> -->


</div>
