<?php
$all_widgets = self::get_real_widgets_map();
?>
<div class="inner-content">
    <h2 class="title-small color-purple">Choose which widget you want</h2>
    <p>You can always Enable/Disable widgets later from your dashboard</p>

    <div class="widget-container list masked">
    <?php 
    foreach ( $all_widgets as $key =>$widget ) {
        // print_r($widget);
        $label = isset( $widget['is_pro'] ) && $widget['is_pro'] ? 'Pro':'Free';
        $title = $widget['title'];

        echo '<ha-item type="',$label,'" title="',$title,'" key="',$key,'" isActive="true"></ha-item>';
    }
    ?>
    </div>

    <ha-nav
    prev="welcome"
    next="widgets"
    done=""
    @set-tab="setTab"
    ></ha-nav>
</div>