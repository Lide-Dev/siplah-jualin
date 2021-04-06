<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="profile-tabs">
	<ul class="nav">
	<li class="nav-item <?php echo ($active_tab == 'all') ? 'active' : ''; ?>">
			<a class="nav-link" href="<?php echo generate_url("sales"); ?>">
				<span><?php echo trans("all"); ?></span>
			</a>
		</li>
		<li class="nav-item <?php echo ($active_tab == 'need_confirmation') ? 'active' : ''; ?>">
			<a class="nav-link" href="<?php echo generate_url("sales"); ?>">
				<span><?php echo trans("need_confirmation"); ?></span>
			</a>
		</li>
		<li class="nav-item <?php echo ($active_tab == 'on_delivery') ? 'active' : ''; ?>">
			<a class="nav-link" href="<?php echo generate_url("sales"); ?>">
				<span><?php echo trans("on_delivery"); ?></span>
			</a>
		</li>
		<li class="nav-item <?php echo ($active_tab == 'not_yet_paid') ? 'active' : ''; ?>">
			<a class="nav-link" href="<?php echo generate_url("sales"); ?>">
				<span><?php echo trans("not_yet_paid"); ?></span>
			</a>
		</li>
		<li class="nav-item <?php echo ($active_tab == 'complaint') ? 'active' : ''; ?>">
			<a class="nav-link" href="<?php echo generate_url("sales"); ?>">
				<span><?php echo trans("complaint"); ?></span>
			</a>
		</li>
		<li class="nav-item <?php echo ($active_tab == 'active_sales') ? 'active' : ''; ?>">
			<a class="nav-link" href="<?php echo generate_url("sales"); ?>">
				<span><?php echo trans("active_sales"); ?></span>
			</a>
		</li>
		<li class="nav-item <?php echo ($active_tab == 'completed_sales') ? 'active' : ''; ?>">
			<a class="nav-link" href="<?php echo generate_url("sales","completed_sales"); ?>">
				<span><?php echo trans("completed_sales"); ?></span>
			</a>
		</li>
	</ul>
</div>
