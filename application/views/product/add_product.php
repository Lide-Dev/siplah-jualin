<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<!-- File Manager -->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/file-manager/file-manager.css">

<!-- Added Video Upload -->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/file-uploader/css/jquery.dm-uploader.min.css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/file-uploader/css/styles.css" />
<script src="<?php echo base_url(); ?>assets/vendor/file-uploader/js/jquery.dm-uploader.min.js"></script>
<script src="<?php echo base_url(); ?>assets/vendor/file-uploader/js/demo-ui.js"></script>

<script src="<?php echo base_url(); ?>assets/vendor/file-manager/file-manager.js"></script>
<!-- Ckeditor js -->
<script src="<?php echo base_url(); ?>assets/vendor/ckeditor/ckeditor.js"></script>
<script src="<?php echo base_url(); ?>assets/vendor/ckeditor/lang/<?php echo $this->selected_lang->ckeditor_lang; ?>.js"></script>

<!-- Wrapper -->
<div id="wrapper">
    <div class="container">
        <div class="row">
            <div id="content" class="col-12">
                <nav class="nav-breadcrumb" aria-label="breadcrumb">
                    <ol class="breadcrumb"></ol>
                </nav>
                <h1 class="page-title page-title-product"><?php echo trans("sell_now"); ?></h1>
                <div class="form-add-product">
                    <div class="row justify-content-center">
                        <div class="col-12 col-md-12 col-lg-11">
                            <div class="row">
                                <div class="col-12">
                                    <!-- include message block -->
                                    <?php $this->load->view('product/_messages'); ?>
                                </div>
                            </div>
                            <!-- END OFF UPLOAD PHOTO -->
                            <!-- PRODUCT TYPE -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-box">
                                        <div class="form-box-head">
                                            <h4 class="title">Kategori Utama</h4>
                                        </div>
                                        <div class="form-box-body">
                                            <div class="row">
                                                <div class="col-sm-4 mb-3">
                                                    <div class="dropdown">
                                                        <button class="btn col-12 btn-outline-gray dropdown-toggle" type="button" id="dropdownMenuType" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <?php switch (xss_clean($type)) {
                                                                case 'general_item':
                                                                    echo "Barang Umum";
                                                                    break;
                                                                case 'specified_item':
                                                                    echo "Barang Spesifik";
                                                                    break;
                                                                case 'service':
                                                                    echo "Jasa";
                                                                    break;
                                                                default:
                                                                    echo "Pilih salah satu...";
                                                                    break;
                                                            } ?>
                                                        </button>
                                                        <div class="dropdown-menu col-12" aria-labelledby="dropdownMenuType">
                                                            <a class="dropdown-item" href="<?= base_url("sell-now/general_item") ?>">Barang Umum</a>
                                                            <a class="dropdown-item" href="<?= base_url("sell-now/specified_item") ?>">Barang Spesifik</a>
                                                            <a class="dropdown-item" href="<?= base_url("sell-now/service") ?>">Jasa</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php switch (xss_clean($type)) {
                                        case "general_item":
                                            $this->load->view("product/_add_product_general_item");
                                            break;
                                        case "specified_item":
                                            $this->load->view("product/_add_product_specified_item");
                                            break;
                                        case "service":
                                            $this->load->view("product/_add_product_service");
                                            break;
                                        default:
                                            break;
                                    } ?>



                                    <?php echo form_close(); ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Wrapper End-->
<script src="<?php echo base_url(); ?>assets/vendor/plyr/plyr.min.js"></script>
<script src="<?php echo base_url(); ?>assets/vendor/plyr/plyr.polyfilled.min.js"></script>

<script>
    function get_subcategories(category_id, data_select_id) {
        var subcategories = get_subcategories_array(category_id);
        var date = new Date();
        //reset subcategories
        $('.subcategory-select').each(function() {
            if (parseInt($(this).attr('data-select-id')) > parseInt(data_select_id)) {
                $(this).remove();
            }
        });
        if (category_id == 0) {
            return false;
        }
        if (subcategories.length > 0) {
            var new_data_select_id = date.getTime();
            var select_tag = '<div class="selectdiv m-t-5"><select class="form-control subcategory-select" data-select-id="' + new_data_select_id + '" name="category_id_' + new_data_select_id + '" required onchange="get_subcategories(this.value,' + new_data_select_id + ');">' +
                '<option value=""><?php echo trans("select_category"); ?></option>';
            for (i = 0; i < subcategories.length; i++) {
                select_tag += '<option value="' + subcategories[i].id + '">' + subcategories[i].name + '</option>';
            }
            select_tag += '</select></div>';
            $('#subcategories_container').append(select_tag);
        }
        //remove empty selectdivs
        $(".selectdiv").each(function() {
            if ($(this).children('select').length == 0) {
                $(this).remove();
            }
        });
    }

    function get_subcategories_array(category_id) {
        var categories_array = <?php echo get_categories_json($this->selected_lang->id); ?>;
        var subcategories_array = [];
        for (i = 0; i < categories_array.length; i++) {
            if (categories_array[i].parent_id == category_id) {
                subcategories_array.push(categories_array[i]);
            }
        }
        return subcategories_array;
    }
</script>

<?php $this->load->view("product/_file_manager_ckeditor"); ?>

<!-- Ckeditor -->
<script>
    var ckEditor = document.getElementById('ckEditor');
    if (ckEditor != undefined && ckEditor != null) {
        CKEDITOR.replace('ckEditor', {
            language: '<?php echo $this->selected_lang->ckeditor_lang; ?>',
            filebrowserBrowseUrl: 'path',
            removeButtons: 'Save',
            allowedContent: true,
            extraPlugins: 'videoembed,oembed'
        });
    }

    function selectFile(fileUrl) {
        window.opener.CKEDITOR.tools.callFunction(1, fileUrl);
    }

    CKEDITOR.on('dialogDefinition', function(ev) {
        var editor = ev.editor;
        var dialogDefinition = ev.data.definition;

        // This function will be called when the user will pick a file in file manager
        var cleanUpFuncRef = CKEDITOR.tools.addFunction(function(a) {
            $('#ckFileManagerModal').modal('hide');
            CKEDITOR.tools.callFunction(1, a, "");
        });
        var tabCount = dialogDefinition.contents.length;
        for (var i = 0; i < tabCount; i++) {
            var browseButton = dialogDefinition.contents[i].get('browse');
            if (browseButton !== null) {
                browseButton.onClick = function(dialog, i) {
                    editor._.filebrowserSe = this;
                    var iframe = $('#ckFileManagerModal').find('iframe').attr({
                        src: editor.config.filebrowserBrowseUrl + '&CKEditor=body&CKEditorFuncNum=' + cleanUpFuncRef + '&langCode=en'
                    });
                    $('#ckFileManagerModal').appendTo('body').modal('show');
                }
            }
        }
    });

    CKEDITOR.on('instanceReady', function(evt) {
        $(document).on('click', '.btn_ck_add_image', function() {
            if (evt.editor.name != undefined) {
                evt.editor.execCommand('image');
            }
        });
        $(document).on('click', '.btn_ck_add_video', function() {
            if (evt.editor.name != undefined) {
                evt.editor.execCommand('videoembed');
            }
        });
        $(document).on('click', '.btn_ck_add_iframe', function() {
            if (evt.editor.name != undefined) {
                evt.editor.execCommand('iframe');
            }
        });
    });
</script>