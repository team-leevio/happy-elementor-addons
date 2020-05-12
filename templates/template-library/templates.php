<?php
/**
 * Template library templates
 */

defined( 'ABSPATH' ) || exit;

?>
<script type="text/template" id="tmpl-haTemplateLibrary__header-logo">
    <span class="elementor-templates-modal__header__logo__icon-wrapper">
		<i class="hm hm-happyaddons"></i>
	</span>
    <span class="elementor-templates-modal__header__logo__title">{{{ title }}}</span>
</script>

<script type="text/template" id="tmpl-haTemplateLibrary__header-back">
	<i class="eicon-" aria-hidden="true"></i>
	<span><?php echo __( 'Back to Library', 'happy-elementor-addons' ); ?></span>
</script>

<script type="text/template" id="tmpl-haTemplateLibrary__header-menu">
	<# console.log(tabs); _.each( tabs, function( args, tab ) { #>
		<div class="elementor-component-tab elementor-template-library-menu-item" data-tab="{{{ tab }}}">{{{ args.title }}}</div>
	<# } ); #>
</script>

<script type="text/template" id="tmpl-haTemplateLibrary__header-actions">
	<div id="elementor-template-library-header-sync" class="elementor-templates-modal__header__item">
		<i class="eicon-sync" aria-hidden="true" title="<?php esc_attr_e( 'Sync Library', 'happy-elementor-addons' ); ?>"></i>
		<span class="elementor-screen-only"><?php echo __( 'Sync Library', 'happy-elementor-addons' ); ?></span>
	</div>
</script>

<script type="text/template" id="tmpl-haTemplateLibrary__header-insert">
	<div id="elementor-template-library-header-preview-insert-wrapper" class="elementor-templates-modal__header__item">
		{{{ elementor.templates.layout.getTemplateActionButton( obj ) }}}
	</div>
</script>

<script type="text/template" id="tmpl-elementor-template-library-loading">
	<div class="elementor-loader-wrapper">
		<div class="elementor-loader">
			<div class="elementor-loader-boxes">
				<div class="elementor-loader-box"></div>
				<div class="elementor-loader-box"></div>
				<div class="elementor-loader-box"></div>
				<div class="elementor-loader-box"></div>
			</div>
		</div>
		<div class="elementor-loading-title"><?php echo __( 'Loading', 'happy-elementor-addons' ); ?></div>
	</div>
</script>

<script type="text/template" id="tmpl-haTemplateLibrary__collection">
	<div>Hello library <button type="button" class="btn-back">Show Back</button> <button type="button" class="btn-go">Go Back</button></div>
</script>

<script type="text/template" id="tmpl-haTemplateLibrary__iframe">
    <iframe style="width:100%;height:100%"></iframe>
</script>
