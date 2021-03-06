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
                      <div class="dropdown m-auto">
                        <button class="btn btn-light dropdown-toggle" type="button" id="select_compared_product" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          Pilih Produk
                        </button>
                        <div class="dropdown-menu" aria-labelledby="select_compared_product">
                          <?php foreach ($related_products as $product) : ?>
                            <a class="dropdown-item" href="<?= base_url('compare/add_compared_product?product_id=' . $product->id) ?>"><?= $product->title ?></a>
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
                        <h6 class="font-weight-bold">Total Sebelum PPN</h6>
                        <h6 class="font-weight-bold">PPN</h6>
                        <h6 class="font-weight-bold">Biaya Kirim</h6>
                        <h6 class="font-weight-bold">Total</h6>
                      </div>
                      <div class="col-md-6" align="right">
                        <h6 class="font-weight-bold"><?= $products[$product_index]->price_formatted ?></h6>
                        <h6 class="font-weight-bold"><?= $products[$product_index]->ppn_formatted ?></h6>
                        <h6 class="font-weight-bold">Buat penawaran dulu</h6>
                        <h6 class="font-weight-bold"><?= $products[$product_index]->total_price_with_ppn ?></h6>
                      </div>
                    </div>
                  <?php endif ?>
                  <?php if ($products[$product_index] != null) : ?>
                    <div class="row">
                      <div class="<?= $products[$product_index]->title = $products[0]->title ? "col-md-12" : "col-md-6" ?>" align="center">
                        <a class="btn btn-success btn-lg" style="color: white;" href="<?= base_url('cart/negotiation?product_id=' . $product[$product_index]->id) ?>"><?= trans("make_an_offer") ?></a>
                      </div>
                      <?php if (($products[$product_index]->title != $products[0]->title)) : ?>
                        <div class="col-md-6" align="center">
                          <a class="btn btn-danger btn-lg" style="color: white;"><?= trans("remove") ?></a>
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
      <?php endfor ?>
      <!-- End Pagination Loop -->
      <!--.item-->
    </div>
    <!--.carousel-inner-->
    <a class="carousel-control-prev" href="#compare_carousel" role="button" data-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#compare_carousel" role="button" data-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="sr-only">Next</span>
    </a>
  </div>
  <!--.Carousel-->
</div>


<!-- backup 2 -->
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
  <div class="row my-3">
    <div class="compared-container border rounded p-3">
      <?php foreach ($compared_items as $vendor) : ?>
        <div class="container compared-item">
          <!-- Vendor Desc and Search -->
          <div class="row vendor-container p-2">
            <div class="col-2">
              <img src="<?= $vendor->avatar ?>" alt="image vendor">
            </div>
            <div class="col-8">
              <h6 class="no-spacing"><?= $vendor->username; ?></h6>
              <!-- Location <p class="no-spacing"></p> -->
            </div>
            <div class="col-2 p-0">
              <a href="#" class="btn btn-danger" style="color: white;">Hapus</a>
            </div>
          </div>
          <!-- END Vendor Desc -->
          <!-- Compared Product -->
          <?php $product_index = 1 ?>
          <?php foreach ($vendor->products as $product) : ?>
            <div class="row compared-products-container border-top pt-2">
              <div class="col-1 compared-product-index">
                <p class="font-weight-bold no-spacing"><?= $product_index . "." ?></p>
              </div>
              <div class="col-2 compared-product-img p-0">
                <img src="<?= base_url("assets/img/") . "user.png" ?>" alt="">
              </div>
              <div class="col-5 compared-product-desc">
                <h6 class="no-spacing"><?= $product->title ?></h6>
                <p class="no-spacing"><?= $product->category ?></p>
                <p class="no-spacing"><?= $product->is_vat_active ? trans("product_with_vat") : "" ?></p>
              </div>
              <div class="col-4 compared-product-price p-2" align="end">
                <h6 class="font-weight-bold"><?= $product->price_formatted ?></h6>
              </div>
            </div>
          <?php endforeach ?>
          <!-- END Compared Product -->
          <!-- Product Totals -->
          <div class="row compared-products-totals mt-3">
            <div class="col-6" align="start">
              <h6>Total Sebelum PPN</h6>
              <h6>PPN</h6>
              <h6>Biaya Kirim</h6>
              <h6>Total</h6>
            </div>
            <div class="col-6" align="end">
              <h6><?= $vendor->total_price_without_vat ?></h6>
              <h6><?= $vendor->total_vat ?></h6>
              <h6>Buat Penawaran dahulu</h6>
              <h6><?= $vendor->total_price_with_vat ?></h6>
            </div>
          </div>
          <!-- END Product Totals -->
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<style>
  .compared-products-totals h6 {
    font-weight: bold;
  }

  .no-spacing {
    padding: 0%;
    margin: 0%;
  }

  .dummy {
    border: 1px solid red;
  }

  .vendor-container img {
    height: 50px;
    width: 50px;
  }

  .compared-product-img img {
    height: 60px;
    width: 60px;
  }

  .compared-container {
    min-width: 100%;
    min-height: 50vmin;
    display: block;
    overflow-x: auto;
    white-space: nowrap;
  }

  .compared-item {
    max-width: 50%;
    white-space: nowrap;
    display: inline-block;
  }
</style>