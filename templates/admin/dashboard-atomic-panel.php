<?php
/**
 * Dashboard V4 (Atomic) panel, rendered next to the V3 panel of a tab.
 *
 * The Widgets, Features, Extensions and GSAP tabs list components.
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

	<?php if ( in_array( $tab, [ 'widgets', 'features', 'extensions', 'gsap' ], true ) ) :
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
			$is_extensions_tab = 'extensions' === $tab;

			foreach ( Atomic_Registry::features() as $key => $item ) {
				// Extensions (Text Stroke, Foreground Overlay...) live in the Extensions tab, as in Classic.
				if ( $is_extensions_tab !== ! empty( $item['is_extension'] ) ) {
					continue;
				}

				if ( $is_extensions_tab ) {
					$push( __( 'Extensions', 'happy-elementor-addons' ), 'feature', $key, $item );
					continue;
				}

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
			'extensions' => __( 'Atomic Extensions', 'happy-elementor-addons' ),
			'gsap'     => __( 'Atomic GSAP', 'happy-elementor-addons' ),
		][ $tab ];
		$action  = [ 'widgets' => 'atomic_widget', 'features' => 'atomic_feature', 'extensions' => 'atomic_feature', 'gsap' => 'atomic_gsap' ][ $tab ];

		$count_of = function ( $type ) use ( $groups ) {
			$total = 0;
			foreach ( $groups as $entries ) {
				foreach ( $entries as $entry ) {
					if ( $entry[0] === $type ) {
						$total++;
					}
				}
			}
			return $total;
		};
		$total_count = $count_of( 'feature' ) + $count_of( 'widget' );
		?>
		<div class="ha-dashboard-panel__header">
			<div class="ha-dashboard-panel__header-content">
				<h2><?php echo esc_html( $heading ); ?></h2>
				<?php if ( 'gsap' === $tab ) : ?>
					<p class="f16"><?php printf( esc_html__( 'Here is the list of our %1$s Atomic GSAP features and %2$s Atomic GSAP widgets. %3$sAfter enabling or disabling any feature or widget make sure to click the Save Changes button.%4$s', 'happy-elementor-addons' ), $count_of( 'feature' ), $count_of( 'widget' ), '<strong>', '</strong>' ); ?></p>
				<?php elseif ( 'extensions' === $tab ) : ?>
					<p class="f16"><?php printf( esc_html__( 'Here is the list of our all %1$s Atomic extensions. You can enable or disable extensions from here. %2$sAfter enabling or disabling any extension make sure to click the Save Changes button.%3$s', 'happy-elementor-addons' ), $total_count, '<strong>', '</strong>' ); ?></p>
				<?php elseif ( 'features' === $tab ) : ?>
					<p class="f16"><?php printf( esc_html__( 'Here is the list of our all %1$s Atomic features. You can enable or disable features from here. %2$sAfter enabling or disabling any feature make sure to click the Save Changes button.%3$s', 'happy-elementor-addons' ), $total_count, '<strong>', '</strong>' ); ?></p>
				<?php endif; ?>
				<div class="ha-action-list">
					<button type="button" class="ha-action--btn" data-filter="*"><?php esc_html_e( 'All', 'happy-elementor-addons' ); ?></button>
					<button type="button" class="ha-action--btn" data-filter="free"><?php esc_html_e( 'Free', 'happy-elementor-addons' ); ?></button>
					<button type="button" class="ha-action--btn" data-filter="pro"><?php esc_html_e( 'Pro', 'happy-elementor-addons' ); ?></button>
					<span class="ha-action--divider">|</span>
					<button type="button" class="ha-action--btn" data-action="enable_<?php echo esc_attr( $action ); ?>"><?php esc_html_e( 'Enable All', 'happy-elementor-addons' ); ?></button>
					<button type="button" class="ha-action--btn" data-action="disable_<?php echo esc_attr( $action ); ?>"><?php esc_html_e( 'Disable All', 'happy-elementor-addons' ); ?></button>
					<span class="ha-search-action--btn"><input type="search" value="" placeholder="<?php esc_html_e( 'Search', 'happy-elementor-addons' ); ?>"></span>
				</div>
			</div>
		</div>
		<div class="ha-dashboard-widgets ha-dashboard-widgets--tab ha-atomic-list">
			<?php if ( 'widgets' === $tab && empty( $groups ) ) : ?>
				<div class="ha-atomic-empty" style="width:100%;min-height:320px;display:flex;flex-direction:column;align-items:center;justify-content:center;">
					<h3><?php esc_html_e( 'Atomic widgets are coming soon', 'happy-elementor-addons' ); ?></h3>
					<p><?php esc_html_e( 'We are building Happy Addons widgets for Elementor V4 (Atomic). They are not available yet, but will appear here as soon as they are released. Your Classic widgets are not affected.', 'happy-elementor-addons' ); ?></p>
				</div>
			<?php endif; ?>
			<?php foreach ( $groups as $group_title => $entries ) : ?>
				<h2 style="width: 100%; margin-left: 10px;"><?php echo esc_html( $group_title ); ?></h2>
				<?php foreach ( $entries as $entry ) :
					list( $type, $key, $item ) = $entry;
					$is_pro = ! empty( $item['is_pro'] );
					$is_placeholder = $is_pro && ! ha_has_pro();
					$id     = 'ha-atomic-' . ( 'gsap' === $tab ? 'gsap-' : '' ) . $type . '-' . $key;
					?>
					<div class="ha-dashboard-widgets__item<?php echo $is_pro ? ' item--is-pro' : ''; ?><?php echo $is_placeholder ? ' item--is-placeholder' : ''; ?>" data-title="<?php echo esc_attr( $item['title'] ); ?>">
						<?php if ( $is_pro ) : ?><span class="ha-dashboard-widgets__item-badge"><?php esc_html_e( 'Pro', 'happy-elementor-addons' ); ?></span><?php endif; ?>
						<span class="ha-dashboard-widgets__item-icon"><i class="<?php echo esc_attr( $item['icon'] ); ?>"></i></span>
						<h3 class="ha-dashboard-widgets__item-title"><label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $item['title'] ); ?></label></h3>
						<div class="ha-dashboard-widgets__item-toggle ha-toggle">
							<input id="<?php echo esc_attr( $id ); ?>" <?php checked( in_array( $key, $active_lists[ $type ], true ) ); ?> <?php disabled( $is_placeholder ); ?> type="checkbox" class="ha-toggle__check ha-atomic-<?php echo esc_attr( $type ); ?>" name="atomic_<?php echo esc_attr( $type ); ?>s[]" value="<?php echo esc_attr( $key ); ?>">
							<b class="ha-toggle__switch"></b><b class="ha-toggle__track"></b>
						</div>
					</div>
				<?php endforeach; ?>
			<?php endforeach; ?>
		</div>
	<?php else : ?>
		<div class="ha-atomic-empty">
			<h3><?php esc_html_e( 'Nothing to show yet', 'happy-elementor-addons' ); ?></h3>
		</div>
	<?php endif; ?>

	<div class="ha-dashboard-panel__footer">
		<button disabled class="ha-dashboard-btn ha-dashboard-btn--save" type="submit"><?php esc_html_e( 'Save Settings', 'happy-elementor-addons' ); ?></button>
	</div>
</div>
