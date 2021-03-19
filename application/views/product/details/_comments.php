<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div id="comment-result">
    <div class="row">
        <div class="col-12">
            <div class="comments">
                <div class="row-custom row-comment-label">
                    <label class="label-comment"><?php echo trans("comments"); ?>&nbsp;(<?php echo $comment_count; ?>)</label>
                </div>
                <?php if (empty($comments)): ?>
                    <p class="no-comments-found"><?php echo trans("no_comments_found"); ?></p>
                <?php else: ?>
                    <ul class="comment-list">
                        <?php foreach ($comments as $comment): ?>
                            <li>
                                <div class="left">
                                    <?php if (!empty($comment->user_slug)): ?>
                                        <a href="<?php echo generate_profile_url($comment->user_slug); ?>">
                                            <img src="<?php echo get_user_avatar_by_image_url($comment->user_avatar, $comment->user_type); ?>" alt="<?php echo html_escape($comment->name); ?>">
                                        </a>
                                    <?php else: ?>
                                        <img src="<?php echo get_user_avatar_by_image_url($comment->user_avatar, $comment->user_type); ?>" alt="<?php echo html_escape($comment->name); ?>">
                                    <?php endif; ?>
                                </div>
                                <div class="right">
                                    <div class="row-custom">
                                        <p class="username">
                                            <?php echo (!empty($comment->user_slug)) ? '<a href="' . generate_profile_url($comment->user_slug) . '">' : '';
                                            if (!empty($comment->user_id)):
                                                echo !empty($comment->user_shop_name) ? html_escape($comment->user_shop_name) : html_escape($comment->name);
                                            else:
                                                echo html_escape($comment->name);
                                            endif;
                                            echo (!empty($comment->user_slug)) ? '</a>' : ''; ?>
                                        </p>
                                    </div>
                                    <div class="row-custom comment">
                                        <?php echo html_escape($comment->comment); ?>
                                    </div>
                                    <div class="row-custom">
                                        <span class="date"><?php echo time_ago($comment->created_at); ?></span>
                                        <a href="javascript:void(0)" class="btn-reply" onclick="show_comment_box('<?php echo $comment->id; ?>');"><i class="icon-reply"></i> <?php echo trans('reply'); ?></a>
                                        <?php if ($this->auth_check):
                                            if ($comment->user_id == $this->auth_user->id || $this->auth_user->role == "admin"): ?>
                                                <a href="javascript:void(0)" class="btn-delete-comment" onclick="delete_comment('<?php echo $comment->id; ?>','<?php echo $product->id; ?>','<?php echo trans("confirm_comment"); ?>');">&nbsp;<i class="icon-trash"></i>&nbsp;<?php echo trans("delete"); ?></a>
                                            <?php endif;
                                        endif; ?>
                                    </div>
                                    <div id="sub_comment_form_<?php echo $comment->id; ?>" class="row-custom row-sub-comment visible-sub-comment">

                                    </div>
                                    <div class="row-custom row-sub-comment">
                                        <!-- include subcomments -->
                                        <?php $this->load->view('product/details/_subcomments', ['parent_comment' => $comment]); ?>
                                    </div>

                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
        <?php if ($comment_count > $comment_limit): ?>
            <div id="load_comment_spinner" class="col-12 load-more-spinner">
                <div class="row">
                    <div class="spinner">
                        <div class="bounce1"></div>
                        <div class="bounce2"></div>
                        <div class="bounce3"></div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <button type="button" class="btn-load-more" onclick="load_more_comment('<?php echo $product->id; ?>');">
                    <?php echo trans("load_more"); ?>
                </button>
            </div>
        <?php endif; ?>
    </div>
</div>


