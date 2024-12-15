<?php
require "../include/config.php";
require "../include/checkSession.php";
if (isset($_POST['saveJob'])) {
    $job_id = $_POST['job_id'];
    $user_id = $_SESSION['userId'];
    $insert = $conn->prepare("insert into savedjobs (user_id,job_id) values (?,?)");
    $insert->execute(array($user_id, $job_id));
    echo  '<script>window.location.href="../jobs/singleJob.php?singleJobId=' . $job_id . '"</script>';
} else if (isset($_GET['user_id'])) {
    if (isset($_SESSION['userId'])) {
        if (isset($_SESSION['type']) && $_SESSION['type'] == 'worker' && $_SESSION['userId'] == $_GET['user_id']) {
            require "../include/nav.php"; ?>

            <div class="d-flex" style="height: 150px;">
                <div class="vr"></div>
            </div>
            <div class="container lead fw-bolder" style="color: white;">
                <h2>Saved Jobs By <?php echo $_SESSION['username'] ?></h2>
                <small><a href="../mainPage/index.php" id="link1" style="color:greenyellow">Home/</a><a href="" id="link2" style="color: white;">Saved Jobs</a></small>
            </div>
            <div class="d-flex" style="height: 100px;">
                <div class="vr"></div>
            </div>

            <div class="p-5" style="background-color: white;">

                <div class="container ">
                    <?php
                    $showSavedJobs = $conn->prepare("SELECT
                        company.company_user_id as CuserId,
                        company.company_id as cId,
                        company.company_name as cName,
                        jobs.job_title as jobTitle,
                        jobs.job_region as jobRegion,
                        jobs.job_type as jobType,
                        jobs.job_id as jobId,
                        company.company_image as cImage
                    FROM
                        jobs
                    INNER JOIN savedjobs ON  savedjobs.job_id=jobs.job_id
                    INNER JOIN company ON company.company_id=jobs.company_id
                    WHERE
                        savedjobs.user_id = ?;");
                    $showSavedJobs->execute(array($_SESSION['userId']));
                    $jobListed = $showSavedJobs->rowCount();
                    ?>
                    <h2 class="text-center justify-content-center"><?php echo $jobListed ?> Jobs Listed</h2>
                    <div class="mx-5 my-4 row row-cols-1 row-cols-md-2 g-3">
                        <?php
                        while ($showSavedJobsDetails = $showSavedJobs->fetch(PDO::FETCH_ASSOC)) {
                            $jobTitle = $showSavedJobsDetails['jobTitle'];
                            $cName = $showSavedJobsDetails['cName'];
                            $cId = $showSavedJobsDetails['cId'];
                            $CuserId = $showSavedJobsDetails['CuserId'];
                            $jobRegion = $showSavedJobsDetails['jobRegion'];
                            $cImage = $showSavedJobsDetails['cImage'];
                            $jobType = $showSavedJobsDetails['jobType'];
                            $jobId = $showSavedJobsDetails['jobId']; ?>
                            <div class="mx-3 my-1 col-sm-12  col-md-12  col-lg-12">
                                <div class="card mb-3 w-300">
                                    <div class="row g-0">
                                        <div class="col-md-2  d-flex align-item-start flex-column">
                                            <img src="../images/<?php echo $cImage ?>" class="img-fluid rounded-start" alt="...">
                                        </div>
                                        <div class="col-md-10">
                                            <div class="card-body text-center"><br>
                                                <div class="clearfix">
                                                    <h5 class="float-start">
                                                        <figure>
                                                            <blockquote class="blockquote">
                                                                <a class="btn btn-link" href="../jobs/singleJob.php?singleJobId=<?php echo $jobId ?>"><?php echo $jobTitle ?></a>
                                                            </blockquote>
                                                            <figcaption class="blockquote-footer">
                                                                <?php echo $cName ?>
                                                            </figcaption>
                                                        </figure>
                                                    </h5>
                                                    <div class="float-end">
                                                        <div>
                                                            <span class="mx-1 my-2 fs-4"><?php echo $jobRegion ?></span>
                                                            <button class="my-2 btn btn-<?php echo $jobType == 'Full Time' ? 'success' : ($jobType == 'Hybrid' ? 'warning' : 'danger') ?> btn-sm mx-1"><?php echo $jobType ?></button>
                                                            <form method="POST" action="savedJob.php">
                                                                <input type="hidden" value="<?php echo $jobId ?>" name="job_id" ?>
                                                                <input type="submit" class="my-2 btn btn-danger btn-sm mx-1" name="unSave" value="delete">
                                                            </form>
                                                        </div>
                                                        <!-- <button class="btn btn-danger btn-sm ">part time</button> -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
<?php  } else {
            echo '<script>window.location.href="../mainPage/index.php"</script>';
        }
    } else {
        echo '<script>window.location.href="../mainPage/index.php"</script>';
    }
} else if (isset($_POST['unSave'])) {
    $job_id = $_POST['job_id'];
    $delete = $conn->prepare("delete from savedjobs where job_id=? and user_id=?");
    $delete->execute(array($_POST['job_id'], $_SESSION['userId']));
    echo  '<script>window.location.href="../jobs/singleJob.php?singleJobId=' . $job_id . '"</script>';
} else {
    echo  '<script>window.location.href="../jobs/singleJob.php?singleJobId=' . $job_id . '"</script>';
}
