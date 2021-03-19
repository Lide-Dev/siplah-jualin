<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="profile-tabs">
    <ul class="nav">
        <li class="nav-item <?php echo ($active_tab == 'update_profile') ? 'active' : ''; ?>">
            <a class="nav-link" href="<?php echo generate_url("settings"); ?>">
                <span><?php echo trans("update_profile"); ?></span>
            </a>
        </li>
        <li class="nav-item <?php echo ($active_tab == 'personal_information') ? 'active' : ''; ?>">
            <a class="nav-link" href="<?php echo generate_url("settings", "personal_information"); ?>">
                <span><?php echo trans("personal_information"); ?></span>
            </a>
        </li>
        <?php if (is_user_vendor()): ?>
            <li class="nav-item <?php echo ($active_tab == 'shop_settings') ? 'active' : ''; ?>">
                <a class="nav-link" href="<?php echo generate_url("settings", "shop_settings"); ?>">
                    <span><?php echo trans("shop_settings"); ?></span>
                </a>
            </li>
        <?php endif; ?>
        <li class="nav-item <?php echo ($active_tab == 'shipping_address') ? 'active' : ''; ?>">
            <a class="nav-link" href="<?php echo generate_url("settings", "shipping_address"); ?>">
                <span><?php echo trans("shipping_address"); ?></span>
            </a>
        </li>
        <li class="nav-item <?php echo ($active_tab == 'social_media') ? 'active' : ''; ?>">
            <a class="nav-link" href="<?php echo generate_url("settings", "social_media"); ?>">
                <span><?php echo trans("social_media"); ?></span>
            </a>
        </li>
        <li class="nav-item <?php echo ($active_tab == 'change_password') ? 'active' : ''; ?>">
            <a class="nav-link" href="<?php echo generate_url("settings", "change_password"); ?>">
                <span><?php echo trans("change_password"); ?></span>
            </a>
        </li>
    </ul>
</div>
