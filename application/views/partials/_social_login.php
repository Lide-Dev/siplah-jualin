<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php if (!empty($this->general_settings->facebook_app_id)): ?>
    <a href="<?php echo base_url(); ?>connect-with-facebook" class="btn btn-social btn-social-facebook">
        <i class="icon-facebook"></i>&nbsp;<?php echo trans("connect_with_facebook"); ?>
    </a>
<?php endif; ?>
<?php if (!empty($this->general_settings->google_client_id)): ?>
    <a href="<?php echo base_url(); ?>connect-with-google" class="btn btn-social btn-social-google">
        <i class="icon-google"></i>&nbsp;<?php echo trans("connect_with_google"); ?>
    </a>
<?php endif; ?>

<?php if (!empty($this->general_settings->vk_app_id)): ?>
    <a href="<?php echo base_url(); ?>connect-with-vk" class="btn btn-social btn-social-vk">
        <i class="icon-vk"></i>&nbsp;<?php echo trans("connect_with_vk"); ?>
    </a>
<?php endif; ?>
<?php if (!empty($this->general_settings->facebook_app_id) || !empty($this->general_settings->google_client_id) || !empty($this->general_settings->vk_app_id)): ?>
    <div class="form-group">
        <p class="p-social-media"><?php echo $or_text; ?></p>
    </div>
<?php endif; ?>
