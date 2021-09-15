<div class="inner-content">
    <img src="<?php echo HAPPY_ADDONS_ASSETS; ?>imgs/admin/congrats.svg" alt="">
    <h2 class="title-big color-purple">Welcome to HappyAddons</h2>
    <?php 
    if(function_exists('ini_get')) { 
        $memory = ini_get('memory_limit');
        $hasLowMem = (str_replace("M",'',$memory) < 256)?true:false;
    ?>
    <div class="php-info">Your Current PHP Memory limit is: <strong><?=$memory?></strong>
        <?php if($hasLowMem): ?>
        <p>( Please increase your memory limit or disable unused widgets to get better performance )</p>
        <?php endif; ?>
    </div>
    <?php } ?>

    <div class="welcome-buttongroup">
        <div class="switch active">
            <span class="radio"></span>
            <div class="switch-data">
                <span class="title">I’m a regular User</span>
                <span class="description">Config the widget for me</span>
            </div>
        </div>
        <div class="switch">
            <span class="radio"></span>
            <div class="switch-data">
                <span class="title">I’m a power User</span>
                <span class="description">I can config myself</span>
            </div>
        </div>
    </div>
    
    <ha-nav
    prev="bepro"
    next="congrats"
    done=""
    @set-tab="setTab"
    ></ha-nav>
</div>