<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="profile-tabs">
	<ul class="nav">
		<li class="nav-item <?php echo ($active_tab == 'all') ? 'active' : ''; ?>">
			<a class="nav-link" href="<?php echo generate_url("orders"); ?>">
				<span><?php echo trans("all"); ?></span>
			</a>
		</li>
		<li class="nav-item <?php echo ($active_tab == 'need_confirmation') ? 'active' : ''; ?>">
			<a class="nav-link" href="<?php echo generate_url("orders", "completed_orders"); ?>">
				<span><?php echo trans("need_confirmation"); ?></span>
			</a>
		</li>
		<li class="nav-item <?php echo ($active_tab == 'on_delivery') ? 'active' : ''; ?>">
			<a class="nav-link" href="<?php echo generate_url("orders"); ?>">
				<span><?php echo trans("on_delivery"); ?></span>
			</a>
		</li>
		<li class="nav-item <?php echo ($active_tab == 'not_yet_paid') ? 'active' : ''; ?>">
			<a class="nav-link" href="<?php echo generate_url("orders", "completed_orders"); ?>">
				<span><?php echo trans("not_yet_paid"); ?></span>
			</a>
		</li>
		<li class="nav-item <?php echo ($active_tab == 'complaint') ? 'active' : ''; ?>">
			<a class="nav-link" href="<?php echo generate_url("orders", "completed_orders"); ?>">
				<span><?php echo trans("complaint"); ?></span>
			</a>
		</li>
		<li class="nav-item <?php echo ($active_tab == 'active_orders') ? 'active' : ''; ?>">
			<a class="nav-link" href="<?php echo generate_url("orders"); ?>">
				<span><?php echo trans("active_orders"); ?></span>
			</a>
		</li>
		<li class="nav-item <?php echo ($active_tab == 'completed_orders') ? 'active' : ''; ?>">
			<a class="nav-link" href="<?php echo generate_url("orders", "completed_orders"); ?>">
				<span><?php echo trans("completed_orders"); ?></span>
			</a>
		</li>
	</ul>
</div>
