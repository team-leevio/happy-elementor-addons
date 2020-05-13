<?php
/**
 * Template library templates
 */

defined( 'ABSPATH' ) || exit;

?>
<script type="text/template" id="tmpl-haTemplateLibrary__header-logo">
    <span class="haTemplateLibrary__logo-wrap">
		<i class="hm hm-happyaddons"></i>
	</span>
    <span class="haTemplateLibrary__logo-title">{{{ title }}}</span>
</script>

<script type="text/template" id="tmpl-haTemplateLibrary__header-back">
	<i class="eicon-" aria-hidden="true"></i>
	<span><?php echo __( 'Back to Library', 'happy-elementor-addons' ); ?></span>
</script>

<script type="text/template" id="tmpl-haTemplateLibrary__header-menu">
	<# _.each( tabs, function( args, tab ) { var activeClass = args.active ? 'elementor-active' : ''; #>
		<div class="elementor-component-tab elementor-template-library-menu-item {{activeClass}}" data-tab="{{{ tab }}}">{{{ args.title }}}</div>
	<# } ); #>
</script>

<script type="text/template" id="tmpl-haTemplateLibrary__header-menu-responsive">
	<div class="elementor-component-tab haTemplateLibrary__responsive-menu-item elementor-active" data-tab="desktop">
		<i class="eicon-device-desktop" aria-hidden="true" title="<?php esc_attr_e( 'Desktop view', 'happy-elementor-addons' ); ?>"></i>
		<span class="elementor-screen-only"><?php esc_html_e( 'Desktop view', 'happy-elementor-addons' ); ?></span>
	</div>
	<div class="elementor-component-tab haTemplateLibrary__responsive-menu-item" data-tab="tab">
		<i class="eicon-device-tablet" aria-hidden="true" title="<?php esc_attr_e( 'Tab view', 'happy-elementor-addons' ); ?>"></i>
		<span class="elementor-screen-only"><?php esc_html_e( 'Tab view', 'happy-elementor-addons' ); ?></span>
	</div>
	<div class="elementor-component-tab haTemplateLibrary__responsive-menu-item" data-tab="mobile">
		<i class="eicon-device-mobile" aria-hidden="true" title="<?php esc_attr_e( 'Mobile view', 'happy-elementor-addons' ); ?>"></i>
		<span class="elementor-screen-only"><?php esc_html_e( 'Mobile view', 'happy-elementor-addons' ); ?></span>
	</div>
</script>

<script type="text/template" id="tmpl-haTemplateLibrary__header-actions">
	<div id="haTemplateLibrary__header-sync" class="elementor-templates-modal__header__item">
		<i class="eicon-sync" aria-hidden="true" title="<?php esc_attr_e( 'Sync Library', 'happy-elementor-addons' ); ?>"></i>
		<span class="elementor-screen-only"><?php esc_html_e( 'Sync Library', 'happy-elementor-addons' ); ?></span>
	</div>
</script>

<script type="text/template" id="tmpl-haTemplateLibrary__collection">
	<div>Hello library <button type="button" class="btn-back">Show Back</button> <button type="button" class="btn-go">Go Back</button></div>
</script>

<script type="text/template" id="tmpl-haTemplateLibrary__preview">
    <iframe></iframe>
</script>

<script type="text/template" id="tmpl-haTemplateLibrary__header-insert">
	<div id="elementor-template-library-header-preview-insert-wrapper" class="elementor-templates-modal__header__item">
		{{{ haLibary.getModal().getTemplateActionButton( obj ) }}}
	</div>
</script>

<script type="text/template" id="tmpl-haTemplateLibrary__insert-button">
	<a class="elementor-template-library-template-action elementor-button elementor-template-library-template-insert ">
		<i class="eicon-file-download" aria-hidden="true"></i>
		<span class="elementor-button-title"><?php esc_html_e( 'Insert', 'happy-elementor-addons' ); ?></span>
	</a>
</script>

<script type="text/template" id="tmpl-haTemplateLibrary__pro-button">
	<a class="elementor-template-library-template-action elementor-button elementor-go-pro" href="https://happyaddons.com/" target="_blank">
		<i class="eicon-external-link-square" aria-hidden="true"></i>
		<span class="elementor-button-title"><?php esc_html_e( 'Get Pro', 'happy-elementor-addons' ); ?></span>
	</a>
</script>

<script type="text/template" id="tmpl-haTemplateLibrary__loading">
	<div class="elementor-loader-wrapper">
		<div class="elementor-loader">
			<div class="elementor-loader-boxes">
				<div class="elementor-loader-box"></div>
				<div class="elementor-loader-box"></div>
				<div class="elementor-loader-box"></div>
				<div class="elementor-loader-box"></div>
			</div>
		</div>
		<div class="elementor-loading-title"><?php esc_html_e( 'Loading', 'happy-elementor-addons' ); ?></div>
	</div>
</script>
