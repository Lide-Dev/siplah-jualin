<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="box">
    <div class="box-header with-border">
        <div class="left">
            <h3 class="box-title"><?php echo trans('categories'); ?></h3>
        </div>
        <div class="right">
            <a href="<?php echo admin_url(); ?>add-category" class="btn btn-success btn-add-new">
                <i class="fa fa-plus"></i>&nbsp;&nbsp;<?php echo trans('add_category'); ?>
            </a>
        </div>
    </div><!-- /.box-header -->

    <div class="box-body">
        <div class="row">
            <!-- include message block -->
            <div class="col-sm-12">
                <?php $this->load->view('admin/includes/_messages'); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" role="grid">
                        <?php $this->load->view('admin/category/_filter_categories'); ?>
                        <thead>
                        <tr role="row">
                            <th width="20"><?php echo trans('id'); ?></th>
                            <th><?php echo trans('category_name'); ?></th>
                            <th><?php echo trans('parent_category'); ?></th>
                            <th><?php echo trans('order'); ?></th>
                            <th><?php echo trans('visibility'); ?></th>
                            <th><?php echo trans('show_on_homepage'); ?></th>
                            <th class="th-options"><?php echo trans('options'); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if (!empty($categories)):
                            foreach ($categories as $item): ?>
                                <tr>
                                    <td><?php echo html_escape($item->id); ?></td>
                                    <td><?php echo category_name($item); ?></td>
                                    <td>
                                        <?php
                                        $parent = get_category_by_id($item->parent_id);
                                        if (!empty($parent)) {
                                            echo category_name($parent);
                                        } else {
                                            echo "-";
                                        } ?>
                                    </td>
                                    <td><?php echo html_escape($item->category_order); ?></td>
                                    <td>
                                        <?php if ($item->visibility == 1): ?>
                                            <label class="label label-success label-table"><i class="fa fa-eye"></i></label>
                                        <?php else: ?>
                                            <label class="label label-danger label-table"><i class="fa fa-eye"></i></label>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($item->show_on_homepage == 1): ?>
                                            <label class="label label-success label-table"><?php echo trans('yes'); ?></label>
                                        <?php else: ?>
                                            <label class="label label-danger label-table"><?php echo trans('no'); ?></label>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn bg-purple dropdown-toggle btn-select-option"
                                                    type="button"
                                                    data-toggle="dropdown"><?php echo trans('select_option'); ?>
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu options-dropdown">
                                                <li>
                                                    <a href="<?php echo admin_url(); ?>update-category/<?php echo html_escape($item->id); ?>"><i class="fa fa-edit option-icon"></i><?php echo trans('edit'); ?></a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0)" onclick="delete_item('category_controller/delete_category_post','<?php echo $item->id; ?>','<?php echo trans("confirm_category"); ?>');"><i class="fa fa-trash option-icon"></i><?php echo trans('delete'); ?></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach;
                        endif; ?>
                        </tbody>
                    </table>

                    <?php if (empty($categories)): ?>
                        <p class="text-center">
                            <?php echo trans("no_records_found"); ?>
                        </p>
                    <?php endif; ?>
                    <div class="col-sm-12 table-ft">
                        <div class="row">

                            <div class="pull-right">
                                <?php echo $this->pagination->create_links(); ?>
                            </div>
                            <?php if (count($categories) > 0): ?>
                                <div class="pull-left">
                                    <button class="btn btn-sm btn-danger btn-table-delete" onclick="delete_selected_products('<?php echo trans("confirm_products"); ?>');"><?php echo trans('delete'); ?></button>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div><!-- /.box-body -->
</div>
