<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!-- Wrapper -->
<div id="wrapper">
    <div class="container">
        <div class="auth-container">
            <div class="auth-box">
                <div class="row">
                    <div class="col-12">
                        <h1 class="title"><?php echo trans("register_buyer"); ?></h1>
                        <!-- form start -->
                        <?php
                        if ($recaptcha_status) {
                            echo form_open('register-post', [
                                'id' => 'form_validate', 'class' => 'validate_terms',
                                'onsubmit' => "var serializedData = $(this).serializeArray();var recaptcha = ''; $.each(serializedData, function (i, field) { if (field.name == 'g-recaptcha-response') {recaptcha = field.value;}});if (recaptcha.length < 5) { $('.g-recaptcha>div').addClass('is-invalid');return false;} else { $('.g-recaptcha>div').removeClass('is-invalid');}"
                            ]);
                        } else {
                            echo form_open('register-post', ['id' => 'form_validate', 'class' => 'validate_terms']);
                        }
                        ?>
                        <div class="social-login-cnt">
                            <?php $this->load->view("partials/_social_login", ['or_text' => trans("register_with_email")]); ?>
                        </div>
                        <!-- include message block -->
                        <div id="result-register">
                            <?php $this->load->view('partials/_messages'); ?>
                        </div>
                        <div class="spinner display-none spinner-activation-register">
                            <div class="bounce1"></div>
                            <div class="bounce2"></div>
                            <div class="bounce3"></div>
                        </div>
                        <!-- PROFILE BUYER -->
                        <h4 class="title-auth">1. <?php echo trans("profile_user_buyer"); ?></h4>
                        <div class="form-group">
                            <input type="text" name="full_name" class="form-control auth-form-input" placeholder="<?php echo trans("full_name"); ?>" value="<?php echo old("full_name"); ?>" maxlength="<?php echo $this->username_maxlength; ?>" required>
                        </div>
                        <div class="form-group">
                            <input type="text" name="user_position" class="form-control auth-form-input" placeholder="<?php echo trans("user_position"); ?>" value="<?php echo old("user_position"); ?>" required>
                        </div>
                        <div class="form-group">
                            <input type="number" name="phone_number" class="form-control auth-form-input" placeholder="<?php echo trans("phone_number"); ?>" value="<?php echo old("phone_number"); ?>" maxlength="<?php echo $this->username_maxlength; ?>" required>
                        </div>
                        <div class="form-group">
                            <input type="email" name="email_address" class="form-control auth-form-input" placeholder="<?php echo trans("email_address"); ?>" value="<?php echo old("email_address"); ?>" maxlength="<?php echo $this->username_maxlength; ?>" required>
                        </div>
                        <div class="form-group">
                            <input type="number" name="nitk" class="form-control auth-form-input" placeholder="<?php echo trans("nitk"); ?>" value="<?php echo old("nitk"); ?>" maxlength="<?php echo $this->username_maxlength; ?>" required>
                        </div>
                        <div class="form-group">
                            <input type="number" name="nuptk" class="form-control auth-form-input" placeholder="<?php echo trans("nuptk"); ?>" value="<?php echo old("nuptk"); ?>" maxlength="<?php echo $this->username_maxlength; ?>" required>
                        </div>
                        <!-- NPWP -->
                        <div class="form-group">
                            <input type="number" name="npwp" class="form-control auth-form-input" placeholder="<?php echo trans("npwp"); ?>" value="<?php echo old("npwp"); ?>" required>
                        </div>
                        <div class="">
                            <div class="col-12 m-b-30">
                                <label class="control-label font-600"><?php echo trans("upload_npwp"); ?></label>
                                <?php $this->load->view("auth/_document_upload"); ?>
                            </div>
                        </div>
                        <!-- END OF NPWP -->
                        <!-- ADDRESS BUYER -->
                        <div class="form-group">
                            <textarea type="text" name="complete_address" class="form-control auth-form-input" placeholder="<?php echo trans("complete_address"); ?>" value="<?php echo old("complete_address"); ?>" required></textarea>
                        </div>
                        <div class="form-group">
                            <input type="text" name="province" class="form-control auth-form-input" placeholder="<?php echo trans("province"); ?>" value="<?php echo old("province"); ?>" required>
                        </div>
                        <div class="form-group">
                            <input type="text" name="districs" class="form-control auth-form-input" placeholder="<?php echo trans("districs"); ?>" value="<?php echo old("districs"); ?>" required>
                        </div>
                        <div class="form-group">
                            <input type="text" name="sub_distric" class="form-control auth-form-input" placeholder="<?php echo trans("sub_distric"); ?>" value="<?php echo old("sub_distric"); ?>" required>
                        </div>
                        <div class="form-group">
                            <input type="text" name="village" class="form-control auth-form-input" placeholder="<?php echo trans("village"); ?>" value="<?php echo old("village"); ?>" required>
                        </div>
                        <div class="form-group">
                            <input type="number" name="postal_code" class="form-control auth-form-input" placeholder="<?php echo trans("postal_code"); ?>" value="<?php echo old("postal_code"); ?>" required>
                        </div>
                        <!-- END OF ADDRESS BUYER -->
                        <!-- NIB -->
                        <div class="form-group">
                            <input type="number" name="nib" class="form-control auth-form-input" placeholder="<?php echo trans("nib"); ?>" value="<?php echo old("nib"); ?>" required>
                        </div>
                        <div class="">
                            <div class="col-12 m-b-30">
                                <label class="control-label font-600"><?php echo trans("upload_selected_document"); ?></label>
                                <?php $this->load->view("auth/_document_upload"); ?>
                            </div>
                        </div>
                        <!-- END OF NIB -->
                        <!-- END OF PROFILE BUYER -->
                        <!-- PROFILE SCHOOL -->
                        <h4 class="title-auth">2. <?php echo trans("profile_school"); ?></h4>
                        <div class="form-group">
                            <input type="text" name="official_name" class="form-control auth-form-input" placeholder="<?php echo trans("official_name"); ?>" value="<?php echo old("official_name"); ?>" required>
                        </div>
                        <div class="form-group">
                            <input type="email" name="email_address_official" class="form-control auth-form-input" placeholder="<?php echo trans("email_address"); ?>" value="<?php echo old("email_address"); ?>" maxlength="<?php echo $this->username_maxlength; ?>" required>
                        </div>
                        <div class="form-group">
                            <input type="number" name="phone_number_official" class="form-control auth-form-input" placeholder="<?php echo trans("phone_number"); ?>" value="<?php echo old("phone_number"); ?>" maxlength="<?php echo $this->username_maxlength; ?>" required>
                        </div>
                        <div class="form-group">
                            <input type="number" name="npsn" class="form-control auth-form-input" placeholder="<?php echo trans("npsn"); ?>" value="<?php echo old("npsn"); ?>" required>
                        </div>
                        <!-- SCHOOL NPWP -->
                        <div class="form-group">
                            <input type="text" name="npwp_official" class="form-control auth-form-input" placeholder="<?php echo trans("npwp"); ?>" value="<?php echo old("npwp"); ?>" required>
                        </div>
                        <div class="">
                            <div class="col-12 m-b-30">
                                <label class="control-label font-600"><?php echo trans("upload_npwp"); ?></label>
                                <?php $this->load->view("auth/_document_upload"); ?>
                            </div>
                        </div>
                        <!-- END OF SCHOOL NPWP -->
                        <!-- SCHOOL ADDRESS -->
                        <div class="form-group">
                            <textarea type="text" name="complete_address_official" class="form-control auth-form-input" placeholder="<?php echo trans("complete_address"); ?>" value="<?php echo old("complete_address"); ?>" required></textarea>
                        </div>
                        <div class="form-group">
                            <input type="text" name="province_official" class="form-control auth-form-input" placeholder="<?php echo trans("province"); ?>" value="<?php echo old("province"); ?>" required>
                        </div>
                        <div class="form-group">
                            <input type="text" name="districs_official" class="form-control auth-form-input" placeholder="<?php echo trans("districs"); ?>" value="<?php echo old("districs"); ?>" required>
                        </div>
                        <div class="form-group">
                            <input type="text" name="sub_distric_official" class="form-control auth-form-input" placeholder="<?php echo trans("sub_distric"); ?>" value="<?php echo old("sub_distric"); ?>" required>
                        </div>
                        <div class="form-group">
                            <input type="text" name="village_official" class="form-control auth-form-input" placeholder="<?php echo trans("village"); ?>" value="<?php echo old("village"); ?>" required>
                        </div>
                        <div class="form-group">
                            <input type="number" name="postal_code_official" class="form-control auth-form-input" placeholder="<?php echo trans("postal_code"); ?>" value="<?php echo old("postal_code"); ?>" required>
                        </div>
                        <!-- END OF SCHOOL ADDRESS -->
                        <!-- END OF PROFILE SCHOOL -->
                        <h4 class="title-auth">3. <?php echo trans("password_title"); ?></h4>
                        <div class="form-group">
                            <input type="password" name="password" class="form-control auth-form-input" placeholder="<?php echo trans("password"); ?>" value="<?php echo old("password"); ?>" required>
                        </div>
                        <div class="form-group">
                            <input type="password" name="confirm_password" class="form-control auth-form-input" placeholder="<?php echo trans("password_confirm"); ?>" required>
                        </div>
                        <div class="form-group m-t-30 m-b-20">
                            <div class="custom-control custom-checkbox custom-control-validate-input">
                                <input type="checkbox" class="custom-control-input" name="terms" id="checkbox_terms" required>
                                <?php $page_terms_condition = get_page_by_default_name("terms_conditions", $this->selected_lang->id); ?>
                                <label for="checkbox_terms" class="custom-control-label"><?php echo trans("terms_conditions_exp"); ?>&nbsp;<a href="<?php echo lang_base_url() . $page_terms_condition->slug; ?>" class="link-terms" target="_blank"><strong><?php echo html_escape($page_terms_condition->title); ?></strong></a></label>
                            </div>
                        </div>
                        <?php if ($recaptcha_status) : ?>
                            <div class="recaptcha-cnt">
                                <?php generate_recaptcha(); ?>
                            </div>
                        <?php endif; ?>
                        <div class="form-group">
                            <button type="submit" class="btn btn-md btn-custom btn-block"><?php echo trans("register"); ?></button>
                        </div>
                        <p class="p-social-media m-0 m-t-15"><?php echo trans("have_account"); ?>&nbsp;<a href="javascript:void(0)" class="link" data-toggle="modal" data-target="#loginModal"><?php echo trans("login"); ?></a></p>

                        <?php echo form_close(); ?>
                        <!-- form end -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Wrapper End-->