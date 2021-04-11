<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="profile-tabs">
	<ul class="nav">
		<li class="nav-item <?php echo ($active_tab == 'cart') ? 'active' : ''; ?>">
			<a class="nav-link" href="<?php echo generate_url("cart"); ?>">
				<span><?php echo trans("cart"); ?></span>
			</a>
		</li>
		<li class="nav-item <?php echo ($active_tab == 'make_an_offer') ? 'active' : ''; ?>">
			<a class="nav-link" href="<?php echo generate_url("make_an_offer"); ?>">
				<span><?php echo trans("make_an_offer"); ?></span>
			</a>
		</li>
	</ul>
</div>
