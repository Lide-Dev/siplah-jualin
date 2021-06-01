<?php
$book_result = $books["result"];
// dd($books);
// dd($book_result[0]->price_collection,$book_result[0]);
?>

<div class="row">
    <?php foreach ($book_result as $key => $book) :  ?>
        <div class="col-sm-4">
            <div class="card col-12 mb-5">
                <div class="card-body">
                    <h5 class="card-title"><?= $book->title ?></h5>
                    <h6 class="card-subtitle mb-2 text-muted"><?= $book->price_collection ?> </h6>
                    <p class="card-text">
                        <b>Deskripsi:</b> <?= $book->description ?? "-" ?> <br>
                        <b>ISBN:</b> <?= $book->isbn ?? "-" ?> <br>
                        <b>Penulis:</b> <?= $book->author ?? "-" ?> <br>
                    </p>
                    <a href="<?= base_url("text-book/detail-book/" . $book->id) ?>" class="btn btn-custom btn-sell-now">Lebih Detail</a>
                    <a href=" <?= base_url("text-book/select-book/" . $book->id) ?>" class="btn text-white btn-success">Pilih Buku Ini</a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

</div>
<div class="row justify-content-center">
    <div class="col-auto">
        <?php for ($i = 1; $i <= ($books["total_page"] > 10 ? 10 : $books["total_page"]); $i++) : ?>
            <?php if ($books["current_page"] == $i) : ?>
                <a class="btn btn-disabled" href="javascript:void(0)"><?= $i ?></a>
            <?php else : ?>
                <a class="btn btn-custom btn-sell-now" href="<?= (empty($books["url_query"]) ? "?" : $books["url_query"] . "&") . "page={$i}" ?>"><?= $i ?></a>

        <?php endif;
        endfor; ?>
    </div>
</div>
<script>
    // var tabledata = [{
    //         id: 1,
    //         name: "Billy Bob",
    //         age: 12,
    //         gender: "male",
    //         height: 95,
    //         col: "red",
    //         dob: "14/05/2010"
    //     },
    //     {
    //         id: 2,
    //         name: "Jenny Jane",
    //         age: 42,
    //         gender: "female",
    //         height: 142,
    //         col: "blue",
    //         dob: "30/07/1954"
    //     },
    //     {
    //         id: 3,
    //         name: "Steve McAlistaire",
    //         age: 35,
    //         gender: "male",
    //         height: 176,
    //         col: "green",
    //         dob: "04/11/1982"
    //     },
    // ];

    // var table = new Tabulator("#example-table", {
    //     // data: tabledata,
    //     autoColumns: true,
    //     ajaxURL:"<?= base_url("catalog-text-books") ?>"
    // });
</script>