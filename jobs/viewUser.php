<?php
require "../include/nav.php";
if (isset($_POST['accept'])) {
    $user_id = $_POST['user_id'];
    $applicationNo = $_POST['applicationNo'];
    $job_id = $_POST['job_id'];
    $modifyStatus = $conn->prepare("update appliedJobs set status=1 where user_id=? && applicationNo=? && job_id=?");
    $modifyStatus->execute(array(
        $user_id,
        $applicationNo,
        $job_id
    ));
} else if (isset($_POST['reject'])) {
    $user_id = $_POST['user_id'];
    $applicationNo = $_POST['applicationNo'];
    $job_id = $_POST['job_id'];
    $modifyStatus = $conn->prepare("update appliedJobs set status=-1 where user_id=? && applicationNo=? && job_id=?");
    $modifyStatus->execute(array(
        $user_id,
        $applicationNo,
        $job_id
    ));
}
?>
<div class="d-flex" style="height: 150px;">
    <div class="vr"></div>
</div>
<div class="container lead fw-bolder" style="color: white;">
    <h2>Job Applications By Workers</h2>
    <small><a href="../mainPage/index.php" id="link1" style="color:greenyellow">Home/</a><a href="../authentication/register.php" id="link2" style="color: white;">Register</a></small>
</div>
<div class="d-flex" style="height: 100px;">
    <div class="vr"></div>
</div>
<div class="p-5" style="background-color: white;">

    <div class="container ">

        <?php
        if (isset($_GET['job_id'])) {
            $showJob = $conn->prepare("
        SELECT
    jobs.job_id as job_id,
    users.user_id as user_id,
    users.name as name,
    users.email as email,
    users.image as img,
    users.cv as cv,
    appliedJobs.status as status,
    appliedJobs.applicationNo as applicationNo
FROM
    appliedjobs
INNER JOIN jobs ON appliedjobs.job_id = jobs.job_id
INNER JOIN users ON users.user_id = appliedjobs.user_id
WHERE
    jobs.job_id = ?;");
            $showJob->execute(array($_GET['job_id']));
            $jobListed = $showJob->rowCount();
        ?>
            <h2 class="text-center justify-content-center"><?php echo $jobListed ?> Workers Applied</h2>
            <div class="mx-5 my-4 row row-cols-1 row-cols-md-2 g-3">
                <?php
                while ($showJobDetails = $showJob->fetch(PDO::FETCH_ASSOC)) {
                    $user_id = $showJobDetails['user_id'];
                    $job_id = $showJobDetails['job_id'];
                    $status = $showJobDetails['status'];
                    $applicationNo = $showJobDetails['applicationNo'];
                    $name = $showJobDetails['name'];
                    $email = $showJobDetails['email'];
                    $img = $showJobDetails['img'];
                    $cv = $showJobDetails['cv'];
                ?>
                    <div class="mx-3 my-1 col-sm-12  col-md-12  col-lg-12">
                        <div class="card mb-3 w-300">
                            <div class="row g-0">
                                <div class="col-md-2  d-flex align-item-start flex-column">
                                    <img src="../images/<?php echo $img ?>" class="img-fluid rounded-start" alt="...">
                                </div>
                                <div class="col-md-10">
                                    <div class="card-body text-center"><br>
                                        <div class="clearfix">
                                            <h5 class="float-start">
                                                <figure>
                                                    <blockquote class="blockquote">
                                                        <a class="btn btn-link" href="#"><?php echo $name ?></a>
                                                    </blockquote>
                                                    <figcaption class="blockquote-footer">
                                                        <?php echo $email ?>
                                                    </figcaption>
                                                </figure>
                                            </h5>
                                            <div class="float-end">
                                                <div>
                                                    <button class="my-2 btn btn-sm" id="button">Download Cv</button>
                                                </div>
                                                <form method="POST" action="viewUser.php">
                                                    <input type="hidden" name="user_id" value="<?php echo $user_id ?>">
                                                    <input type="hidden" name="job_id" value="<?php echo $job_id ?>">
                                                    <input type="hidden" name="applicationNo" value="<?php echo $applicationNo ?>">
                                                    <?php
                                                    if ($status == 1) {
                                                        echo '<button class="btn btn-success btn-sm" value="Accepted" disabled>Accepted</button> ';
                                                    } else if ($status == -1) {
                                                        echo '<button class="btn btn-danger btn-sm" value="Rejected" disabled>Rejected</button>';
                                                    } else {
                                                        echo '<input type="submit" class="btn btn-success btn-sm" name="accept" value="Accept" />
                                                        <input type="submit" class="btn btn-danger btn-sm" name="reject" value="Reject" />';
                                                    }

                                                    ?>

                                                </form>
                                                <!-- <button class="btn btn-danger btn-sm ">part time</button> -->
                                            </div>
                                        </div>
                                        <br>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
    </div>
</div>
<?php } ?>