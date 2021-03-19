<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row">
	<div class="col-sm-12 col-xs-12 col-md-6">
		<div class="box box-primary">
			<div class="box-header with-border">
				<div class="left">
					<h3 class="box-title"><?php echo trans('visual_settings'); ?></h3>
				</div>
			</div><!-- /.box-header -->

			<!-- form start -->
			<?php echo form_open_multipart('admin_controller/visual_settings_post'); ?>

			<div class="box-body">

				<!-- include message block -->
				<?php $this->load->view('admin/includes/_messages'); ?>

                <div class="form-group">
                    <label><?php echo trans("color"); ?></label>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="visual-color-box" data-color="default" style="background-color: #222;"><?php echo ($this->general_settings->site_color === "default") ? '<i class="fa fa-check"></i>' : ""; ?></div>
                            <div class="visual-color-box" data-color="pacific_blue" style="background-color: #09b1ba;"><?php echo ($this->general_settings->site_color === "pacific_blue") ? '<i class="fa fa-check"></i>' : ""; ?></div>
                            <div class="visual-color-box" data-color="violet" style="background-color: #6770B7;"><?php echo ($this->general_settings->site_color === "violet") ? '<i class="fa fa-check"></i>' : ""; ?></div>
                            <div class="visual-color-box" data-color="meadow" style="background-color: #1abc9c;"><?php echo ($this->general_settings->site_color === "meadow") ? '<i class="fa fa-check"></i>' : ""; ?></div>
                            <div class="visual-color-box" data-color="blue" style="background-color: #1da7da;"><?php echo ($this->general_settings->site_color === "blue") ? '<i class="fa fa-check"></i>' : ""; ?></div>
                            <div class="visual-color-box" data-color="amaranth" style="background-color: #e91e63;"><?php echo ($this->general_settings->site_color === "amaranth") ? '<i class="fa fa-check"></i>' : ""; ?></div>
                            <div class="visual-color-box" data-color="red" style="background-color: #e74c3c;"><?php echo ($this->general_settings->site_color === "red") ? '<i class="fa fa-check"></i>' : ""; ?></div>
                            <div class="visual-color-box" data-color="orange" style="background-color: #f86923;"><?php echo ($this->general_settings->site_color === "orange") ? '<i class="fa fa-check"></i>' : ""; ?></div>
                            <div class="visual-color-box" data-color="yellow" style="background-color: #ffbb02;"><?php echo ($this->general_settings->site_color === "yellow") ? '<i class="fa fa-check"></i>' : ""; ?></div>
                            <div class="visual-color-box" data-color="bayoux" style="background-color: #495d7f;"><?php echo ($this->general_settings->site_color === "bayoux") ? '<i class="fa fa-check"></i>' : ""; ?></div>
                            <div class="visual-color-box" data-color="cascade" style="background-color: #95a5a6;"><?php echo ($this->general_settings->site_color === "cascade") ? '<i class="fa fa-check"></i>' : ""; ?></div>
                            <div class="visual-color-box" data-color="royal_blue" style="background-color: #2a41e8;"><?php echo ($this->general_settings->site_color === "royal_blue") ? '<i class="fa fa-check"></i>' : ""; ?></div>
                            <input type="hidden" name="site_color" id="input_user_site_color" value="<?php echo html_escape($this->general_settings->site_color); ?>">
                        </div>
                    </div>
                </div>

				<div class="form-group">
					<label class="control-label"><?php echo trans('logo'); ?> (180x50px)</label>
					<div style="margin-bottom: 10px;">
						<img src="<?php echo get_logo($this->general_settings); ?>" alt="logo" style="max-width: 160px; max-height: 160px;">
					</div>
					<div class="display-block">
						<a class='btn btn-success btn-sm btn-file-upload'>
							<?php echo trans('select_logo'); ?>
							<input type="file" name="logo" size="40" accept=".png, .jpg, .jpeg, .gif, .svg" onchange="$('#upload-file-info1').html($(this).val());">
						</a>
						(.png, .jpg, .jpeg, .gif, .svg)
					</div>
					<span class='label label-info' id="upload-file-info1"></span>
				</div>

				<div class="form-group">
					<label class="control-label"><?php echo trans('logo_email'); ?></label>
					<div style="margin-bottom: 10px;">
						<img src="<?php echo get_logo_email($this->general_settings); ?>" alt="logo" style="max-width: 160px; max-height: 160px;">
					</div>
					<div class="display-block">
						<a class='btn btn-success btn-sm btn-file-upload'>
							<?php echo trans('select_logo'); ?>
							<input type="file" name="logo_email" size="40" accept=".png, .jpg, .jpeg" onchange="$('#upload-file-info3').html($(this).val());">
						</a>
						(.png, .jpg, .jpeg)
					</div>
					<span class='label label-info' id="upload-file-info3"></span>
				</div>

				<div class="form-group">
					<label class="control-label"><?php echo trans('favicon'); ?> (16x16px)</label>
					<div style="margin-bottom: 10px;">
						<img src="<?php echo get_favicon($this->general_settings); ?>" alt="favicon" style="max-width: 100px; max-height: 100px;">
					</div>
					<div class="display-block">
						<a class='btn btn-success btn-sm btn-file-upload'>
							<?php echo trans('select_favicon'); ?>
							<input type="file" name="favicon" size="40" accept=".png" onchange="$('#upload-file-info2').html($(this).val());">
						</a>
						(.png)
					</div>
					<span class='label label-info' id="upload-file-info2"></span>
				</div>
			</div>
			<!-- /.box-body -->
			<div class="box-footer">
				<button type="submit" class="btn btn-primary pull-right"><?php echo trans('save_changes'); ?></button>
			</div>
			<!-- /.box-footer -->
			<?php echo form_close(); ?><!-- form end -->
		</div>
		<!-- /.box -->
	</div>
	<div class="col-sm-12 col-xs-12 col-md-6">
		<div class="box box-primary">
			<div class="box-header with-border">
				<div class="left">
					<h3 class="box-title"><?php echo trans('watermark'); ?></h3>
				</div>
			</div><!-- /.box-header -->

			<!-- form start -->
			<?php echo form_open_multipart('admin_controller/update_watermark_settings_post'); ?>

			<div class="box-body">

				<div class="form-group">
					<div class="row">
						<div class="col-sm-6 col-xs-12">
							<label><?php echo trans('add_watermark_product_images'); ?></label>
						</div>
						<div class="col-sm-3 col-xs-12 col-option">
							<input type="radio" name="watermark_product_images" value="1" id="watermark_product_images_1" class="square-purple" <?php echo ($this->general_settings->watermark_product_images == 1) ? 'checked' : ''; ?>>
							<label for="watermark_product_images_1" class="option-label"><?php echo trans('yes'); ?></label>
						</div>
						<div class="col-sm-3 col-xs-12 col-option">
							<input type="radio" name="watermark_product_images" value="0" id="watermark_product_images_2" class="square-purple" <?php echo ($this->general_settings->watermark_product_images != 1) ? 'checked' : ''; ?>>
							<label for="watermark_product_images_2" class="option-label"><?php echo trans('no'); ?></label>
						</div>
					</div>
				</div>

				<div class="form-group">
					<div class="row">
						<div class="col-sm-6 col-xs-12">
							<label><?php echo trans('add_watermark_blog_images'); ?></label>
						</div>
						<div class="col-sm-3 col-xs-12 col-option">
							<input type="radio" name="watermark_blog_images" value="1" id="watermark_blog_images_1" class="square-purple" <?php echo ($this->general_settings->watermark_blog_images == 1) ? 'checked' : ''; ?>>
							<label for="watermark_blog_images_1" class="option-label"><?php echo trans('yes'); ?></label>
						</div>
						<div class="col-sm-3 col-xs-12 col-option">
							<input type="radio" name="watermark_blog_images" value="0" id="watermark_blog_images_2" class="square-purple" <?php echo ($this->general_settings->watermark_blog_images != 1) ? 'checked' : ''; ?>>
							<label for="watermark_blog_images_2" class="option-label"><?php echo trans('no'); ?></label>
						</div>
					</div>
				</div>

				<div class="form-group">
					<div class="row">
						<div class="col-sm-6 col-xs-12">
							<label><?php echo trans('add_watermark_thumbnail_images'); ?></label>
						</div>
						<div class="col-sm-3 col-xs-12 col-option">
							<input type="radio" name="watermark_thumbnail_images" value="1" id="watermark_thumbnail_images_1" class="square-purple" <?php echo ($this->general_settings->watermark_thumbnail_images == 1) ? 'checked' : ''; ?>>
							<label for="watermark_thumbnail_images_1" class="option-label"><?php echo trans('yes'); ?></label>
						</div>
						<div class="col-sm-3 col-xs-12 col-option">
							<input type="radio" name="watermark_thumbnail_images" value="0" id="watermark_thumbnail_images_2" class="square-purple" <?php echo ($this->general_settings->watermark_thumbnail_images != 1) ? 'checked' : ''; ?>>
							<label for="watermark_thumbnail_images_2" class="option-label"><?php echo trans('no'); ?></label>
						</div>
					</div>
				</div>


				<div class="row">
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label"><?php echo trans('vertical_alignment'); ?></label>
							<select class="form-control" name="watermark_vrt_alignment" required>
								<option value="top" <?php echo ($this->general_settings->watermark_vrt_alignment == 'top') ? 'selected' : ''; ?>><?php echo trans('top'); ?></option>
								<option value="middle" <?php echo ($this->general_settings->watermark_vrt_alignment == 'middle') ? 'selected' : ''; ?>><?php echo trans('middle'); ?></option>
								<option value="bottom" <?php echo ($this->general_settings->watermark_vrt_alignment == 'bottom') ? 'selected' : ''; ?>><?php echo trans('bottom'); ?></option>
							</select>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="control-label"><?php echo trans('horizontal_alignment'); ?></label>
							<select class="form-control" name="watermark_hor_alignment" required>
								<option value="left" <?php echo ($this->general_settings->watermark_hor_alignment == 'left') ? 'selected' : ''; ?>><?php echo trans('left'); ?></option>
								<option value="center" <?php echo ($this->general_settings->watermark_hor_alignment == 'center') ? 'selected' : ''; ?>><?php echo trans('center'); ?></option>
								<option value="right" <?php echo ($this->general_settings->watermark_hor_alignment == 'right') ? 'selected' : ''; ?>><?php echo trans('right'); ?></option>
							</select>
						</div>
					</div>
				</div>

				<div class="form-group m-t-10">
					<label class="control-label"><?php echo trans('watermark_image'); ?></label>
					<div class="display-block">
						<a class='btn btn-success btn-sm btn-file-upload'>
							<?php echo trans('select_image'); ?>
							<input type="file" name="watermark_image" size="40" accept=".png" onchange="$('#upload-file-info4').html($(this).val().replace(/.*[\/\\]/, ''));">
						</a>
						(.png)
					</div>
					<span class='label label-info' id="upload-file-info4"></span>
					<div style="margin-top: 10px;">
						<img src="<?php echo base_url() . $this->general_settings->watermark_image_large; ?>" alt="" style="max-width: 300px; max-height: 300px; background-color: #ccc;padding: 5px;">
					</div>
				</div>

			</div>
			<!-- /.box-body -->
			<div class="box-footer">
				<button type="submit" class="btn btn-primary pull-right"><?php echo trans('save_changes'); ?></button>
			</div>
			<!-- /.box-footer -->
			<?php echo form_close(); ?><!-- form end -->
		</div>
		<!-- /.box -->
	</div>
</div>

<style>
	.form-group {
		margin-bottom: 30px !important;
	}
</style>

<script>
    //select site color
    $(document).on('click', '.visual-color-box', function () {
        var data_color = $(this).attr('data-color');
        $('.visual-color-box').empty();
        $(this).html('<i class="fa fa-check"></i>');
        $('#input_user_site_color').val(data_color);
    });
</script>
