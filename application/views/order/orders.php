<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!-- Wrapper -->
<div id="wrapper">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<nav class="nav-breadcrumb" aria-label="breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?php echo lang_base_url(); ?>"><?php echo trans("home"); ?></a></li>
						<li class="breadcrumb-item active" aria-current="page"><?php echo $title; ?></li>
					</ol>
				</nav>

				<h1 class="page-title"><?php echo $title; ?></h1>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12 col-md-3">
				<div class="row-custom">
					<!-- load profile nav -->
					<?php $this->load->view("order/_order_tabs"); ?>
				</div>
			</div>



			<div class="col-sm-12 col-md-9">
				<div class="row-custom">
					<div class="profile-tab-content">
						<!-- include message block -->
						<?php $this->load->view('partials/_messages'); ?>
						<!-- SEARCH -->
						<div class="container">
							<div class="row justify-content-end">
								<div class="col-12 col-md-10 col-lg-8">
									<form class="card card-sm">
										<div class="card-body row no-gutters align-items-center">
											<div class="col-auto">
												<i class="fas fa-search h4 text-body"></i>
											</div>
											<!--end of col-->
											<div class="col">
												<input class="form-control form-control-borderless src_fild" type="search" placeholder="Cari Pesanan">
											</div>
											<!--end of col-->
											<div class="col-auto">
												<button class="btn btn-lg btn-primary src_btn" type="submit">Cari</button>
											</div>
											<!--end of col-->
										</div>
									</form>
								</div>
								<!--end of col-->
							</div>
						</div>
						<!-- END OF SEARCH -->
						<div class="table-responsive">
							<table id="example" class="table table-striped">
								<thead>
									<tr>
										<th scope="col"><?php echo trans("order"); ?></th>
										<th scope="col"><?php echo trans("total"); ?></th>
										<th scope="col"><?php echo trans("payment"); ?></th>
										<th scope="col"><?php echo trans("status"); ?></th>
										<th scope="col"><?php echo trans("date"); ?></th>
										<th scope="col"><?php echo trans("options"); ?></th>
									</tr>
								</thead>
								<tbody>
									<?php if (!empty($orders)) : ?>
										<?php foreach ($orders as $order) : ?>
											<tr>
												<td>#<?php echo $order->order_number; ?></td>
												<td><?php echo price_formatted($order->price_total, $order->price_currency); ?></td>
												<td>
													<?php if ($order->payment_status == 'payment_received') :
														echo trans("payment_received");
													else :
														echo trans("awaiting_payment");
													endif; ?>
												</td>
												<td>
													<strong class="font-600">
														<?php if ($order->payment_status == 'awaiting_payment') :
															if ($order->payment_method == 'Cash On Delivery') {
																echo trans("order_processing");
															} else {
																echo trans("awaiting_payment");
															}
														else :
															if ($order->status == 1) :
																echo trans("completed");
															else :
																echo trans("order_processing");
															endif;
														endif; ?>
													</strong>
												</td>
												<td><?php echo formatted_date($order->created_at); ?></td>
												<td>
													<a href="<?php echo generate_url("order_details") . "/" . $order->order_number; ?>" class="btn btn-sm btn-table-info"><?php echo trans("details"); ?></a>
												</td>
											</tr>
										<?php endforeach; ?>
									<?php endif; ?>
								</tbody>
							</table>
						</div>


						<?php if (empty($orders)) : ?>
							<p class="text-center">
								<?php echo trans("no_records_found"); ?>
							</p>
						<?php endif; ?>
					</div>
				</div>
				<div class="row-custom m-t-15">
					<div class="float-right">
						<?php echo $this->pagination->create_links(); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Wrapper End-->