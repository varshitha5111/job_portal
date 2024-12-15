<?php require "../include/nav.php";  ?>

<?php
if (!isset($_SESSION['userId'])) {
    header("location:../mainPage/index.php");
}
if ($_SESSION['type'] == 'Company' && isset($_POST['catSubmit'])) {
    $cat_name = $_POST['cat_name'];
    $insert = $conn->prepare('insert into categories(cat_title) values(?);');
    $insert->execute(array(
        $cat_name
    ));
    echo '<script>window.location.href="../jobs/postJob.php?postJobUserId=' . $_SESSION['userId'] . '"</script>';
}
if ($_SESSION['type'] == 'Company') {
?>
    <div class="d-flex" style="height: 150px;">
        <div class="vr"></div>
    </div>

    <div class="container lead fw-bolder" style="color: white;">
        <h2>Create Category </h2>
        <small>
            <a href="../mainPage/index.php" id="link1" style="color:greenyellow">Home/</a>
            <a href="../jobs/postJob.php?postJobUserId=<?php echo $_SESSION['userId'] ?>" id="link2" style="color: white;">PostJob</a>
            <a href="../jobs/category.php" id="link2" style="color: greenyellow;">/Create Category</a>
        </small>
    </div>

    <div class="d-flex" style="height: 100px;">
        <div class="vr"></div>
    </div>

    <!-- single post by the company card -->
    <div class="p-5" style="background-color: white;">

        <div class="container ">
            <div class="mx-5 my-4 row row-cols-1 row-cols-md-2 g-3">
                <div class="mx-3 my-1 col-sm-12  col-md-12  col-lg-12">
                    <div class="card">
                        <div class="card-header" id="button">
                            <h5>Create Category</h5>
                        </div>
                        <div class="card-body">
                            <form method="post" action="category.php" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Category Name</label>
                                    <input type="text" class="form-control" name="cat_name" id="exampleFormControlInput1" placeholder="Enter Category Name">
                                </div>
                                <input type="submit" class="btn" name="catSubmit" value="Create" id="button">
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
    <?php require "../include/footer.php"; ?>