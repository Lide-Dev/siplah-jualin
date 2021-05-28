<div class="row">
    <?php foreach ($books as $key => $book) :  ?>
        <div class="col-4">
            <div class="card col-12">
                <div class="card-body">
                    <h5 class="card-title"><?= $book->title ?></h5>
                    <h6 class="card-subtitle mb-2 text-muted">Card subtitle</h6>
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    <a href="#" class="card-link">Card link</a>
                    <a href="#" class="card-link">Another link</a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

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