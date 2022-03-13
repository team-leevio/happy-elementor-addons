<?php

use Happy_Addons\Elementor\Theme_Builder;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$types = Theme_Builder::TEMPLATE_TYPE;
$selected = get_query_var('ha_library_type');

?>

<script type="text/template" id="tmpl-modal-template-condition">
<div class="modal micromodal-slide modal-template-condition" id="modal-template-condition" aria-hidden="false">
    <div class="modal__overlay" tabindex="-1">
        <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-login-title">
            <header class="modal__header">
                <h3 class="modal__title" id="modal-2-title">
                    Template Elements Condition
                </h3>
            </header>
            <div class="modal__content" id="modal-2-content">
                <div class="modal__information">
                    <div class="info-title">Where Do You Want to Display Your Template?</div>
                    <div class="info-message">
                        Set the conditions that determine where your Template is used throughout your site.
                        <br>
                        For example, choose 'Entire Site' to display the template across your site.
                    </div>
                </div>
                <form id="ha-template-edit-form">
                    <div class="ha-template-condition-wrap"></div>
                    <button class="ha-cond-repeater-add" type="button">Add Condition</button>
                </form>
            </div>
            <footer class="modal__footer">
                <button class="modal__close" aria-label="Close modal" data-micromodal-close="">Cancel</button>
                <button id="ha-template-save-data" class="modal__btn modal__btn-primary">Save Settings</button>
            </footer>
        </div>
    </div>
</div>
</script>

<script type="text/template" id="tmpl-elementor-new-template">
    <div class="ha-template-condition-item">
        <div class="ha-template-condition-item-row">
            <div class="ha-tce-type">
                <select id="elementor-control-default-{{id}}" data-setting="type">
                    <option value="include">Include</option>
                    <option value="exclude">Exclude</option>
                </select>
            </div>
            <div class="ha-tce-name">
                <select id="elementor-control-default-{{id}}" data-setting="name">
                    <optgroup label="General">
                        <option value="general">Entire Site</option>
                        <option value="archive">Archives</option>
                        <option value="singular">Singular</option>
                    </optgroup>
                </select>
            </div>
            <div class="ha-tce-sub_name">
                <select id="elementor-control-default-{{id}}" data-setting="sub_name">
                    <option value="">Entire Site</option>
                    <optgroup label="Archives">
                        <option value="archive">All Archives</option>
                        <option value="author">Author Archive</option>
                        <option value="date">Date Archive</option>
                        <option value="search">Search Results</option>
                        <option value="post_archive">Posts Archive</option>
                    </optgroup>
                    <optgroup label="Singular">
                        <option value="singular">All Singular</option>
                        <option value="front_page">Front Page</option>
                        <option value="post">Post</option>
                        <option value="page">Page</option>
                        <option value="e-landing-page">Landing Page</option>
                        <option value="attachment">Media</option>
                        <option value="child_of">Direct Child Of</option>
                        <option value="any_child_of">Any Child Of</option>
                        <option value="by_author">By Author</option>
                        <option value="not_found404">404 Page</option>
                    </optgroup>
                </select>
            </div>
            <div class="ha-tce-sub_id">
                <select id="elementor-control-default-{{id}}" data-setting="sub_id">
                    <option value="">All</option>
                </select>
            </div>
        </div>
    </div>
</script>