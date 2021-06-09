<div class="container m-auto p-3">
	<div class="row">
		<?= $this->session->flashdata('payment_source_error') ?>
		<?= $this->session->flashdata('total_compared_product') ?>
	</div>
	<div class="row">
		<div class="text-capitalize font-weight-bold col-md-2" style="font-size: medium;"><?= trans('payment_source') ?></div>
	</div>
	<select name="ps" id="payment_source" class="custom-select my-3 col-md-4">
		<option selected><?= trans('choose_source') ?></option>
		<?php if (!empty($arr_payment_source)) :  ?>
			<!-- payment source list -->
			<?php foreach ($arr_payment_source as $payment_source) : ?>
				<option value="<?= $payment_source ?>"><?= $payment_source ?></option>
			<?php endforeach ?>
		<?php endif ?>
	</select>
	<div class="compared-container row card">
		<?= $this->session->flashdata("compare_status") ?>
		<div class="container py-3">
			<?php for ($i = 0; $i < (count($products) + 1); $i++) : ?>
				<div class="item-compared border">
					<div class="container">
						<!-- Compared Product Loop -->
						<?php if ($products[$i] == null && $temp_vendor == null) : ?>
							<!-- Search Vendor -->
							<div class="row mt-2">
								<div class="container">
									<input type="text" id="search-input" class="form-control search-input" placeholder="Cari Penjual">
									<div class="list-vendors" id="list-vendors">
										<?php foreach ($list_vendors as $vendor) : ?>
											<a href="<?= base_url("compare/add_compared_vendor") . "?vendor_id=" . $vendor->id ?>" class="border p-1 wrap-text"><?= $vendor->username ?></a>
										<?php endforeach ?>
									</div>
								</div>
							</div>
							<!-- End Search Vendor -->

						<?php else : ?>
							<?php if ($temp_vendor != null || $products[$i] != null) : ?>
								<?php $vendor = $temp_vendor != null ? $temp_vendor : $products[$i]->vendor ?>
								<!-- Vendor Detail -->
								<div class="row m-2 pb-2 border-bottom">
									<div class="container">
										<div class="row">
											<div class="col-2">
												<img src="<?= base_url("assets/img/") . "user.png" ?>" alt="thumbnail_vendor" height="50px" width="50px">
											</div>
											<div class="col-7">
												<div class="vendor-name font-weight-bold text-wrap"><?= $vendor->username ?></div>
												<div class="vendor-location font-weight-bold text-wrap">Vendor's Location</div>
											</div>
											<?php if ($i != 0) : ?>
												<div class="col-3" style="display: block;">
													<div class="row">
														<a href="<?= generate_profile_url($vendor->slug) ?>" class="btn btn-sm btn-success text-white">Cari barang</a>
													</div>
													<?php if ($products[$i] == null) : ?>
														<div class="row">
															<a href="<?= base_url("compare/delete_compared_product") ?>" class="btn btn-sm btn-danger text-white mt-1">Ganti Penjual</a>
														</div>
													<?php endif ?>
												</div>
											<?php endif ?>
										</div>
									</div>
								</div>
								<!-- End Vendor Detail -->
							<?php endif ?>
							<?php if ($products[$i] != null) : ?>
								<!-- Product Detail -->
								<div class="row">
									<?php $image_url = $products[$i]->image != null ? $products[$i]->image : base_url('assets/img/') . "no-image.jpg" ?>
									<a href="<?= generate_product_url_by_slug($products[$i]->slug) ?>" class="m-auto">
										<img src="<?= $image_url ?>" alt="Product Image" class="img-thumbnail img-product">
									</a>
								</div>
								<div class="row mt-3">
									<h5 class="text-wrap text-center mx-auto"><?= $products[$i]->title ?></h5>
								</div>
								<div class="row">
									<p class="text-wrap mx-auto text-center"><?= $products[$i]->category ?></p>
								</div>
								<div class="row">
									<p class="text-wrap mx-auto"><?= $product[$i] != 0 ? "Barang Kena PPN" : "" ?></p>
								</div>
								<!-- End Product Detail -->

								<!-- Price Detail -->
								<div class="row my-4">
									<div class="col-md-6" align="left">
										<h6 class="font-weight-bold">Banyaknya</h6>
										<h6 class="font-weight-bold">Harga Satuan Sebelum PPN</h6>
										<h6 class="font-weight-bold">PPN</h6>
										<h6 class="font-weight-bold">Biaya Kirim</h6>
										<h6 class="font-weight-bold">Total</h6>
									</div>
									<div class="col-md-6" align="right">
										<h6 class="font-weight-bold"><?= $product_quantity ?></h6>
										<h6 class="font-weight-bold"><?= $products[$i]->price_formatted ?></h6>
										<h6 class="font-weight-bold"><?= $products[$i]->ppn_formatted ?></h6>
										<h6 class="font-weight-bold">Buat penawaran dulu</h6>
										<h6 class="font-weight-bold"><?= $products[$i]->total_price_with_ppn ?></h6>
									</div>
								</div>
								<!-- End Price Detail -->

								<!-- Delete Button -->
								<?php if ($products[$i] != null) : ?>
									<div class="row">
										<?php if ($products[$i]->title != $products[0]->title) : ?>
											<div class="col" align="end">
												<a href="<?= base_url('compare/delete_compared_product') . "?compared_product_id=" . $products[$i]->id ?>" class="btn btn-danger btn-lg" style="color: white;"><?= trans("remove") ?></a>
											</div>
										<?php endif ?>
									</div>
								<?php endif ?>
							<?php endif ?>
							<!-- End Delete Button -->
						<?php endif ?>
						<!-- End Compared Product Loop -->
					</div>
				</div>
			<?php endfor ?>
		</div>
	</div>
	<!-- Make an Offer Button-->
	<div class="col-md-12 mt-4" align="center">
		<a class="btn btn-success btn-lg" style="color: white;" href="<?= base_url('compare/do_negotiation') . '?ps=' ?>"><?= trans('make_an_offer') ?></a>
	</div>
	<!-- End Make an Offer Button -->
</div>
<style>
	.img-product {
		height: 300px;
		width: 300px;
		object-fit: fill;
	}

	.list-vendors {
		position: absolute;
		z-index: 1;
		background-color: white;
		min-width: 50vmin;
		max-height: 20vmax;
		display: none;
	}

	.list-vendors a {
		display: block;
	}

	.list-vendors a:hover {
		background-color: blue;
		color: white;
	}

	.item-compared {
		width: 50%;
		height: 100%;
		min-height: 100vmin;
		display: inline-block;
	}

	.compared-container {
		overflow: auto;
		white-space: nowrap;
	}
</style>
<script>
	$(document).ready(function() {
		$("#search-input").on("keyup", function() {
			var value = $(this).val().toLowerCase();
			$("#list-vendors a").filter(function() {
				$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
			});
		});

		$("#search-input").on("focusout", function() {
			$("#list-vendors").hide("hide");
		});

		$("#search-input").on("focusin", function() {
			$("#list-vendors").show("show");
		});
	});
</script>