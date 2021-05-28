<div class="container m-auto p-3">
	<div class="row">
		<div class="text-capitalize font-weight-bold col-md-2" style="font-size: medium;"><?= trans('payment_source') ?></div>
	</div>
	<select name="input_payment_source" id="input_payment_source" class="custom-select my-3 col-md-4">
		<option selected><?= trans('choose_source') ?></option>
		<?php if (!empty($arr_payment_source)) :  ?>
			<!-- payment source list -->
			<?php foreach ($arr_payment_source as $payment_source) : ?>
				<option value="<?= $payment_source ?>"><?= $payment_source ?></option>
			<?php endforeach ?>
		<?php endif ?>
	</select>
	<div class="row mx-auto my-3 card py-3 px-0">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div id="compare_carousel" class="carousel slide" data-ride="carousel" data-interval="false">
						<!-- Carousel items -->
						<div class="carousel-inner">
							<!-- Pagination Loop -->
							<?php $product_index = 0 ?>
							<?php for ($i = 0; $i < 3; $i++) : ?>
								<div class="carousel-item <?= $i == 0 ? "active" : "" ?>  min-vh-100">
									<div class="row">
										<!-- Compared Product Loop -->
										<?php for ($j = 0; $j < 2; $j++) : ?>
											<div class="col-md-6 border p-2">
												<div class="container">
													<div class="row">
														<?php if ($products[$product_index] == null) : ?>
															<div class="container">
																<div class="row col">
																	<input type="text" id="search-input" class="form-control search-input" placeholder="Cari Penjual">
																</div>
																<div class="list-vendors" id="list-vendors">
																	<?php foreach ($list_vendors as $vendor) : ?>
																		<a href="#" class="border p-1"><?= $vendor->username ?></a>
																	<?php endforeach ?>
																</div>
															</div>
														<?php endif ?>
													</div>
													<?php if ($products[$product_index] != null) : ?>
														<div class="row">
															<?php $image_url = $products[$product_index]->image != null ? $products[$product_index]->image : base_url('assets/img/') . "no-image.jpg" ?>
															<img src="<?= $image_url ?>" alt="Product Image" class="img-thumbnail m-auto" height="300px" width="300px">
														</div>
														<div class="row mt-3">
															<h4 class="m-auto"><?= $products[$product_index]->title ?></h4>
														</div>
														<div class="row">
															<p class="m-auto"><?= $products[$product_index]->category ?></p>
														</div>
														<div class="row">
															<p class="m-auto">Barang Kena PPN</p>
														</div>
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
																<h6 class="font-weight-bold"><?= $products[$product_index]->price_formatted ?></h6>
																<h6 class="font-weight-bold"><?= $products[$product_index]->ppn_formatted ?></h6>
																<h6 class="font-weight-bold">Buat penawaran dulu</h6>
																<h6 class="font-weight-bold"><?= $products[$product_index]->total_price_with_ppn ?></h6>
															</div>
														</div>
													<?php endif ?>
													<?php if ($products[$product_index] != null) : ?>
														<div class="row">
															<?php if (($products[$product_index]->title != $products[0]->title)) : ?>
																<div class="col" align="end">
																	<a href="<?= base_url('compare/') . "delete_compared_product?compared_product_id=" . $products[$product_index]->id ?>" class="btn btn-danger btn-lg" style="color: white;"><?= trans("remove") ?></a>
																</div>
															<?php endif ?>
														</div>
													<?php endif ?>
												</div>
											</div>
											<?php if ($products[0]->price > $fifty_mil && $j == 0 && $i == 2) {
												break;
											} elseif (($product_price > $fifty_mil && $product_price < $two_hundred_mil) && $j == 0 && $i == 1) {
												break;
											} ?>
											<?php $product_index++ ?>
										<?php endfor ?>
										<!-- End Compared Product Loop -->
									</div>
									<!--.row-->
								</div>
								<?php if (($product_price > $fifty_mil && $product_price < $two_hundred_mil) && $i == 1) {
									break;
								} ?>
							<?php endfor ?>
							<!-- End Pagination Loop -->
							<!--.item-->
						</div>
						<div class="col-md-12 mt-4" align="center">
							<a class="btn btn-success btn-lg" style="color: white;" href=""><?= trans('make_an_offer') ?></a>
						</div>
						<!--.carousel-inner-->
						<a class="" href="#compare_carousel" role="button" data-slide="prev"></a>
						<a class="" href="#compare_carousel" role="button" data-slide="next"></a>
					</div>
					<!--.Carousel-->
				</div>
			</div>
		</div>
		<!--.container-->
	</div>
	<!--.row-->
</div>
<style>
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
</style>
<script>
	$(document).ready(function() {
		$("#search-input").on("keyup", function() {
			var value = $(this).val().toLowerCase();
			$("#list-vendors a").filter(function() {
				$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
			});
		});

		$("#search-input").click(function() {
			$("#list-vendors").toggle("hide");
		})
	});
</script>