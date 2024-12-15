<?php include("../include/nav.php"); 
?>

<?php

$getWorkers = $conn->prepare('select name,user_id,email,image,about,cv from users where userType="worker"');
$getWorkers->execute();

?>

<div class="d-flex" style="height: 150px;">
    <div class="vr"></div>
</div>
<div class="container lead fw-bolder" style="color: white;">
    <h2>Workers</h2>
    <small><a href="../mainPage/index.php" id="link1" style="color:greenyellow">Home/</a><a href="../workerandCompanies/showingWorkers.php" id="link2" style="color: white;">workers</a></small>
</div>
<div class="d-flex" style="height: 100px;">
    <div class="vr"></div>
</div>

<div class="container mt-5">
    <div class="row">
        <?php

        while ($getWorkersDetails = $getWorkers->fetch(PDO::FETCH_ASSOC)) {
            $name = $getWorkersDetails['name'];
            $userId = $getWorkersDetails['user_id'];
            $_SESSION['showUserDetailsId'] = $userId;
            $email = $getWorkersDetails['email'];
            $image = $getWorkersDetails['image'];
            $about = $getWorkersDetails['about'];
        ?>
            <div class="col-md-4 mb-4">
                <div class="card worker-card">
                    <img src="../images/<?php echo $image ?>" class="worker-image" alt="Worker Name" width="400">
                    <div class="card-body text-center">
                        <h5 class="card-title"><?php echo $name ?></h5>
                        <p class="card-text line clamp">About the worker: <?php echo $about ?></p>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<?php include("../include/footer.php"); ?>