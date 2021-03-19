<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row">
	<div class="col-sm-12 col-lg-8">
		<div class="box">
			<div class="box-header with-border">
				<div class="left">
					<h3 class="box-title"><?php echo trans("route_settings");; ?></h3>
				</div>
			</div><!-- /.box-header -->

			<!-- form start -->
			<?php echo form_open_multipart('settings_controller/route_settings_post'); ?>
			<div class="box-body">
				<div class="row">
					<!-- include message block -->
					<div class="col-sm-12">
						<?php $this->load->view('admin/includes/_messages'); ?>
					</div>
				</div>
				<?php if (!empty($this->routes)):
					foreach ($this->routes as $key => $value):
						if ($key != "id"):?>
							<div class="row">
								<div class="col-sm-6 col-xs-6">
									<div class="form-group">
										<input type="text" class="form-control" name="<?php echo $key; ?>_readonly" value="<?php echo str_replace('_', '-', $key); ?>" <?php echo ($this->rtl == true) ? 'dir="rtl"' : ''; ?> readonly>
									</div>
								</div>
								<div class="col-sm-6 col-xs-6">
									<div class="form-group">
										<input type="text" class="form-control" name="<?php echo $key; ?>" value="<?php echo $value; ?>" maxlength="100" <?php echo ($this->rtl == true) ? 'dir="rtl"' : ''; ?> required>
									</div>
								</div>
							</div>
						<?php endif;
					endforeach;
				endif; ?>
			</div><!-- /.box-body -->

			<!-- /.box-body -->
			<div class="box-footer">
				<button type="submit" class="btn btn-primary pull-right"><?php echo trans('save_changes'); ?></button>
			</div>
			<!-- /.box-footer -->
			<?php echo form_close(); ?><!-- form end -->
		</div>
		<div class="alert alert-danger alert-large">
			<strong><?php echo trans("warning"); ?>!</strong>&nbsp;&nbsp;<?php echo trans("route_settings_warning"); ?>
		</div>
	</div>
</div>


