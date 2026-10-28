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

	<?php if ( in_array( $tab, [ 'widgets', 'features', 'gsap' ], true ) ) :
		$active_lists = [
			'widget'  => Atomic_Manager::get_active( 'widgets' ),
			'feature' => Atomic_Manager::get_active( 'features' ),
		];

		// Same grouping as the Classic tabs: GSAP first, then the categories.
		$groups = [];
		$push   = function ( $heading, $type, $key, $item ) use ( &$groups ) {
			$groups[ $heading ][] = [ $type, $key, $item ];
		};

		if ( 'widgets' === $tab || 'gsap' === $tab ) {
			$widgets = Atomic_Registry::widgets();

			if ( 'gsap' === $tab ) {
				$gsap_features = array_filter( Atomic_Registry::features(), function ( $item ) {
					return ! empty( $item['is_gsap'] );
				} );

				foreach ( $gsap_features as $key => $item ) {
					$push( __( 'GSAP Features', 'happy-elementor-addons' ), 'feature', $key, $item );
				}
			}

			foreach ( $widgets as $key => $item ) {
				if ( ! empty( $item['is_gsap'] ) ) {
					$push( __( 'GSAP Widgets', 'happy-elementor-addons' ), 'widget', $key, $item );
				}
			}

			if ( 'widgets' === $tab ) {
				foreach ( $widgets as $key => $item ) {
					if ( empty( $item['is_gsap'] ) ) {
						$category = ucwords( str_replace( '-', ' ', isset( $item['cat'] ) ? $item['cat'] : 'general' ) );
						/* translators: %s: widget category */
						$push( sprintf( __( '%s Widgets', 'happy-elementor-addons' ), $category ), 'widget', $key, $item );
					}
				}
			}
		} else {
			foreach ( Atomic_Registry::features() as $key => $item ) {
				$push( ! empty( $item['is_gsap'] ) ? __( 'GSAP Features', 'happy-elementor-addons' ) : __( 'General Features', 'happy-elementor-addons' ), 'feature', $key, $item );
			}

			// GSAP group first, as in the Classic Features tab.
			uksort( $groups, function ( $a, $b ) {
				return ( __( 'GSAP Features', 'happy-elementor-addons' ) === $b ) <=> ( __( 'GSAP Features', 'happy-elementor-addons' ) === $a );
			} );
		}

		$heading = [
			'widgets'  => __( 'Atomic Widgets', 'happy-elementor-addons' ),
			'features' => __( 'Atomic Features', 'happy-elementor-addons' ),
			'gsap'     => __( 'Atomic GSAP', 'happy-elementor-addons' ),
		][ $tab ];
		$action  = [ 'widgets' => 'atomic_widget', 'features' => 'atomic_feature', 'gsap' => 'atomic_gsap' ][ $tab ];
		?>
		<div class="ha-dashboard-panel__header">
			<div class="ha-dashboard-panel__header-content">
				<h2><?php echo esc_html( $heading ); ?></h2>
				<div class="ha-action-list">
					<button type="button" class="ha-action--btn" data-filter="*"><?php esc_html_e( 'All', 'happy-elementor-addons' ); ?></button>
					<button type="button" class="ha-action--btn" data-filter="free"><?php esc_html_e( 'Free', 'happy-elementor-addons' ); ?></button>
					<button type="button" class="ha-action--btn" data-filter="pro"><?php esc_html_e( 'Pro', 'happy-elementor-addons' ); ?></button>
					<span class="ha-action--divider">|</span>
					<button type="button" class="ha-action--btn" data-action="enable_<?php echo esc_attr( $action ); ?>"><?php esc_html_e( 'Enable All', 'happy-elementor-addons' ); ?></button>
					<button type="button" class="ha-action--btn" data-action="disable_<?php echo esc_attr( $action ); ?>"><?php esc_html_e( 'Disable All', 'happy-elementor-addons' ); ?></button>
				</div>
			</div>
		</div>
		<div class="ha-dashboard-widgets ha-dashboard-widgets--tab ha-atomic-list">
			<?php foreach ( $groups as $group_title => $entries ) : ?>
				<h2 style="width: 100%; margin-left: 10px;"><?php echo esc_html( $group_title ); ?></h2>
				<?php foreach ( $entries as $entry ) :
					list( $type, $key, $item ) = $entry;
					$is_pro = ! empty( $item['is_pro'] );
					$id     = 'ha-atomic-' . ( 'gsap' === $tab ? 'gsap-' : '' ) . $type . '-' . $key;
					?>
					<div class="ha-dashboard-widgets__item<?php echo $is_pro ? ' item--is-pro' : ''; ?>" data-title="<?php echo esc_attr( $item['title'] ); ?>">
						<?php if ( $is_pro ) : ?><span class="ha-dashboard-widgets__item-badge"><?php esc_html_e( 'Pro', 'happy-elementor-addons' ); ?></span><?php endif; ?>
						<span class="ha-dashboard-widgets__item-icon"><i class="<?php echo esc_attr( $item['icon'] ); ?>"></i></span>
						<h3 class="ha-dashboard-widgets__item-title"><label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $item['title'] ); ?></label></h3>
						<div class="ha-dashboard-widgets__item-toggle ha-toggle">
							<input id="<?php echo esc_attr( $id ); ?>" <?php checked( in_array( $key, $active_lists[ $type ], true ) ); ?> type="checkbox" class="ha-toggle__check ha-atomic-<?php echo esc_attr( $type ); ?>" name="atomic_<?php echo esc_attr( $type ); ?>s[]" value="<?php echo esc_attr( $key ); ?>">
							<b class="ha-toggle__switch"></b><b class="ha-toggle__track"></b>
						</div>
					</div>
				<?php endforeach; ?>
			<?php endforeach; ?>
		</div>
	<?php else : ?>
		<div class="ha-atomic-empty">
			<h3><?php esc_html_e( 'No Atomic extensions yet', 'happy-elementor-addons' ); ?></h3>
			<p><?php esc_html_e( 'None of the Happy extensions have an Elementor V4 version yet. The Classic extensions keep working for Classic elements.', 'happy-elementor-addons' ); ?></p>
		</div>
	<?php endif; ?>

	<div class="ha-dashboard-panel__footer">
		<button disabled class="ha-dashboard-btn ha-dashboard-btn--save" type="submit"><?php esc_html_e( 'Save Settings', 'happy-elementor-addons' ); ?></button>
	</div>
</div>
