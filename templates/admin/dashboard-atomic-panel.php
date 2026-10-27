<?php
/**
 * Dashboard V4 (Atomic) panel, rendered next to the V3 panel of a tab.
 *
 * Only the Widgets and Features tabs list components; Extensions and GSAP
 * have no Atomic components yet and show an empty state.
 */

use Happy_Addons\Elementor\Classes\Atomic_Manager;
use Happy_Addons\Elementor\Classes\Atomic_Registry;

defined( 'ABSPATH' ) || die();

$tab = self::$atomic_tab;
$enabled = Atomic_Manager::is_enabled();
$supported = Atomic_Manager::is_environment_supported();
?>
<div class="ha-dashboard-panel ha-atomic-panel<?php echo $enabled ? '' : ' ha-atomic-panel--off'; ?>">
	<?php if ( 'widgets' === $tab ) : ?>
		<input type="hidden" name="atomic_scope" value="1">
		<div class="ha-dashboard-panel__header">
			<div class="ha-dashboard-panel__header-content ha-atomic-hero">
				<div class="ha-atomic-hero__text">
				<h2><?php esc_html_e( 'Elementor V4 (Atomic) Integration', 'happy-elementor-addons' ); ?></h2>
				<p class="f16"><?php esc_html_e( 'Happy Addons components built for Elementor Atomic elements. They are separate from the Classic widgets and features: turning them on or off here never changes the V3 settings.', 'happy-elementor-addons' ); ?></p>
				</div>
				<div class="ha-dashboard-widgets__item ha-atomic-switch">
					<span class="ha-dashboard-widgets__item-icon">
						<svg class="ha-atomic-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="1em" height="1em" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="1.6" fill="currentColor"/><ellipse cx="12" cy="12" rx="10" ry="4"/><ellipse cx="12" cy="12" rx="10" ry="4" transform="rotate(60 12 12)"/><ellipse cx="12" cy="12" rx="10" ry="4" transform="rotate(120 12 12)"/></svg>
					</span>
					<h3 class="ha-dashboard-widgets__item-title"><label for="ha-atomic-integration"><?php esc_html_e( 'Enable V4(Atomic)', 'happy-elementor-addons' ); ?></label></h3>
					<div class="ha-dashboard-widgets__item-toggle ha-toggle">
						<input id="ha-atomic-integration" <?php checked( $enabled ); ?> type="checkbox" class="ha-toggle__check ha-atomic-integration" name="atomic_integration" value="on">
						<b class="ha-toggle__switch"></b><b class="ha-toggle__track"></b>
					</div>
				</div>
			</div>
		</div>
	<?php endif; ?>

	<?php if ( ! $supported ) : ?>
		<div class="ha-atomic-notice">
			<?php esc_html_e( 'Elementor V4 (Atomic) is not available on this site. It needs Elementor 4.0 or later with the Atomic Elements experiment active (Elementor > Settings > Features). Your selections are saved and apply as soon as it is available.', 'happy-elementor-addons' ); ?>
		</div>
	<?php elseif ( ! $enabled && 'widgets' !== $tab ) : ?>
		<div class="ha-atomic-notice">
			<?php esc_html_e( 'Enable the Atomic integration from the Widgets tab to use these components.', 'happy-elementor-addons' ); ?>
		</div>
	<?php elseif ( ! $enabled ) : ?>
		<div class="ha-atomic-notice">
			<?php esc_html_e( 'Enable the Atomic integration above, then save, to register these widgets and features.', 'happy-elementor-addons' ); ?>
		</div>
	<?php endif; ?>

	<?php if ( 'widgets' === $tab || 'features' === $tab ) :
		$is_widgets = 'widgets' === $tab;
		$items = $is_widgets ? Atomic_Registry::widgets() : Atomic_Registry::features();
		$active = Atomic_Manager::get_active( $is_widgets ? 'widgets' : 'features' );
		$field = $is_widgets ? 'atomic_widgets[]' : 'atomic_features[]';
		$class = $is_widgets ? 'ha-atomic-widget' : 'ha-atomic-feature';
		?>
		<div class="ha-dashboard-panel__header">
			<div class="ha-dashboard-panel__header-content">
				<h2><?php echo $is_widgets ? esc_html__( 'Atomic Widgets', 'happy-elementor-addons' ) : esc_html__( 'Atomic Features', 'happy-elementor-addons' ); ?></h2>
				<div class="ha-action-list">
					<button type="button" class="ha-action--btn" data-filter="*"><?php esc_html_e( 'All', 'happy-elementor-addons' ); ?></button>
					<button type="button" class="ha-action--btn" data-filter="free"><?php esc_html_e( 'Free', 'happy-elementor-addons' ); ?></button>
					<button type="button" class="ha-action--btn" data-filter="pro"><?php esc_html_e( 'Pro', 'happy-elementor-addons' ); ?></button>
					<span class="ha-action--divider">|</span>
					<button type="button" class="ha-action--btn" data-action="<?php echo $is_widgets ? 'enable_atomic_widget' : 'enable_atomic_feature'; ?>"><?php esc_html_e( 'Enable All', 'happy-elementor-addons' ); ?></button>
					<button type="button" class="ha-action--btn" data-action="<?php echo $is_widgets ? 'disable_atomic_widget' : 'disable_atomic_feature'; ?>"><?php esc_html_e( 'Disable All', 'happy-elementor-addons' ); ?></button>
				</div>
			</div>
		</div>
		<div class="ha-dashboard-widgets ha-dashboard-widgets--tab ha-atomic-list">
			<?php foreach ( $items as $key => $item ) :
				$is_pro = ! empty( $item['is_pro'] );
				$id = 'ha-atomic-' . ( $is_widgets ? 'widget' : 'feature' ) . '-' . $key;
				?>
				<div class="ha-dashboard-widgets__item<?php echo $is_pro ? ' item--is-pro' : ''; ?>" data-title="<?php echo esc_attr( $item['title'] ); ?>">
					<?php if ( $is_pro ) : ?><span class="ha-dashboard-widgets__item-badge"><?php esc_html_e( 'Pro', 'happy-elementor-addons' ); ?></span><?php endif; ?>
					<span class="ha-dashboard-widgets__item-icon"><i class="<?php echo esc_attr( $item['icon'] ); ?>"></i></span>
					<h3 class="ha-dashboard-widgets__item-title"><label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $item['title'] ); ?></label></h3>
					<div class="ha-dashboard-widgets__item-toggle ha-toggle">
						<input id="<?php echo esc_attr( $id ); ?>" <?php checked( in_array( $key, $active, true ) ); ?> type="checkbox" class="ha-toggle__check <?php echo esc_attr( $class ); ?>" name="<?php echo esc_attr( $field ); ?>" value="<?php echo esc_attr( $key ); ?>">
						<b class="ha-toggle__switch"></b><b class="ha-toggle__track"></b>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	<?php else : ?>
		<div class="ha-atomic-empty">
			<h3><?php echo 'extensions' === $tab ? esc_html__( 'No Atomic extensions yet', 'happy-elementor-addons' ) : esc_html__( 'No Atomic GSAP items here', 'happy-elementor-addons' ); ?></h3>
			<p><?php echo 'extensions' === $tab
				? esc_html__( 'None of the Happy extensions have an Elementor V4 version yet. The Classic extensions keep working for Classic elements.', 'happy-elementor-addons' )
				: esc_html__( 'The Atomic GSAP widgets and features are listed in the Widgets and Features tabs.', 'happy-elementor-addons' ); ?></p>
		</div>
	<?php endif; ?>

	<div class="ha-dashboard-panel__footer">
		<button disabled class="ha-dashboard-btn ha-dashboard-btn--save" type="submit"><?php esc_html_e( 'Save Settings', 'happy-elementor-addons' ); ?></button>
	</div>
</div>
