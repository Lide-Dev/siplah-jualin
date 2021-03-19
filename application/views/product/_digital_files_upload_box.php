<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row">
    <div class="col-12">
        <label class="control-label font-600"><?php echo trans("digital_files"); ?></label>
        <?php $exts = str_replace('"', '', $this->form_settings->digital_allowed_file_extensions);
        $exts = str_replace(',', ", ", $exts);
        $exts = strtoupper($exts); ?>
        <small>(<?php echo trans("allowed_file_extensions"); ?>:&nbsp;<?php echo $exts; ?>)</small>
        <div id="digital_files_upload_result">
            <?php $this->load->view('product/_digital_files_upload_response'); ?>
        </div>
    </div>
</div>

<div class="error-message error-message-file-upload"></div>

<!-- File item template -->
<script type="text/html" id="files-template-digital-files">
    <li class="media">
        <div class="media-body">
            <div class="progress">
                <div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
    </li>
</script>

<script>
    $('#drag-and-drop-zone-digital-files').dmUploader({
        url: '<?php echo base_url(); ?>upload-digital-files-post',
        queue: true,
        extFilter: [<?php echo $this->form_settings->digital_allowed_file_extensions;?>],
        multiple: false,
        extraData: function (id) {
            return {
                "product_id": <?php echo $product->id; ?>,
                "<?php echo $this->security->get_csrf_token_name(); ?>": $.cookie(csfr_cookie_name)
            };
        },
        onDragEnter: function () {
            this.addClass('active');
        },
        onDragLeave: function () {
            this.removeClass('active');
        },
        onInit: function () {
        },
        onComplete: function (id) {
        },
        onNewFile: function (id, file) {
            ui_multi_add_file(id, file, "digital-files");
        },
        onBeforeUpload: function (id) {
            ui_multi_update_file_progress(id, 0, '', true);
            ui_multi_update_file_status(id, 'uploading', 'Uploading...');
        },
        onUploadProgress: function (id, percent) {
            ui_multi_update_file_progress(id, percent);
        },
        onUploadSuccess: function (id, data) {
            document.getElementById("digital_files_upload_result").innerHTML = data;
        },
        onUploadError: function (id, xhr, status, message) {
        },
        onFallbackMode: function () {
        },
        onFileSizeError: function (file) {
        },
        onFileTypeError: function (file) {
        },
        onFileExtError: function (file) {
            $(".error-message-file-upload").html("<?php echo trans('invalid_file_type'); ?>");
            setTimeout(function () {
                $(".error-message-file-upload").empty();
            }, 4000);
        },
    });
    $(document).ajaxStop(function () {
        $('#drag-and-drop-zone-digital-files').dmUploader({
            url: '<?php echo base_url(); ?>upload-digital-files-post',
            queue: true,
            extFilter: [<?php echo $this->form_settings->digital_allowed_file_extensions;?>],
            multiple: false,
            extraData: function (id) {
                return {
                    "product_id": <?php echo $product->id; ?>,
                    "<?php echo $this->security->get_csrf_token_name(); ?>": $.cookie(csfr_cookie_name)
                };
            },
            onDragEnter: function () {
                this.addClass('active');
            },
            onDragLeave: function () {
                this.removeClass('active');
            },
            onInit: function () {
            },
            onComplete: function (id) {
            },
            onNewFile: function (id, file) {
                ui_multi_add_file(id, file, "digital-files");
            },
            onBeforeUpload: function (id) {
                ui_multi_update_file_progress(id, 0, '', true);
                ui_multi_update_file_status(id, 'uploading', 'Uploading...');
            },
            onUploadProgress: function (id, percent) {
                ui_multi_update_file_progress(id, percent);
            },
            onUploadSuccess: function (id, data) {
                document.getElementById("digital_files_upload_result").innerHTML = data;
            },
            onUploadError: function (id, xhr, status, message) {
            },
            onFallbackMode: function () {
            },
            onFileSizeError: function (file) {
            },
            onFileTypeError: function (file) {
            },
            onFileExtError: function (file) {
                $(".error-message-file-upload").html("<?php echo trans('invalid_file_type'); ?>");
                setTimeout(function () {
                    $(".error-message-file-upload").empty();
                }, 4000);
            },
        });
    });
</script>

