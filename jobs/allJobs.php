<?php require "../include/nav.php";  ?>

<?php

?>

<div class="d-flex" style="height: 150px;">
    <div class="vr"></div>
</div>

<div class="container lead fw-bolder" style="color: white;">
    <h2>All Job</h2>
    <small><a href="../mainPage/index.php" id="link1" style="color:greenyellow">Home/</a><a href="" id="link2" style="color: white;">Jobs</a></small>
</div>

<div class="d-flex" style="height: 100px;">
    <div class="vr"></div>
</div>

<div class="p-5" style="background-color: white;">
    <div class="container ">
        <?php
        $showJob = $conn->prepare("SELECT
                  COUNT(DISTINCT(appliedjobs.user_id)) as no_user_id,
                  company.company_id as cId,
                  company.company_user_id as cUserId,
                  company.company_name AS cName,
                  company.company_image AS cImage,
                  jobs.job_title AS jobTitle,
                  jobs.job_region AS jobRegion,
                  jobs.job_type AS jobType,
                  jobs.job_id AS jobId
                  FROM
                  jobs
                  LEFT JOIN appliedjobs ON jobs.job_id = appliedjobs.job_id
                  INNER JOIN company ON jobs.company_id = company.company_id
                  GROUP BY
                  jobs.job_id
                  ORDER BY
                  jobs.created_at desc;
                  ");
        $showJob->execute();
        $jobListed = $showJob->rowCount();
        ?>
        <h2 class="text-center justify-content-center"><?php echo $jobListed ?> Jobs Listed</h2>
        <div class="mx-5 my-4 row row-cols-1 row-cols-md-2 g-3">
            <?php
            while (($showJobDetails = $showJob->fetch(PDO::FETCH_ASSOC))) {
                $jobTitle = $showJobDetails['jobTitle'];
                $cName = $showJobDetails['cName'];
                $cUserId = $showJobDetails['cUserId'];
                $jobRegion = $showJobDetails['jobRegion'];
                $cImage = $showJobDetails['cImage'];
                $jobType = $showJobDetails['jobType'];
                $jobId = $showJobDetails['jobId'];
                $cId = $showJobDetails['cId'];
                $no_user_id = $showJobDetails['no_user_id']; ?>
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
                                                    <a class="btn btn-link" href="../jobs/singleJob.php?singleJobId=<?php echo $jobId ?>">
                                                        <?php echo $jobTitle ?>
                                                    </a>
                                                </blockquote>
                                                <figcaption class="blockquote-footer">
                                                    <a style="color: black; text-decoration: none;" href="../jobs/companyJobs.php?company_id=<?php echo $cId ?>"><?php echo $cName ?></a>
                                                </figcaption>
                                            </figure>
                                        </h5>
                                        <div class="float-end">
                                            <div>
                                                <span class="mx-1 my-2 fs-4"><?php echo $jobRegion ?></span>
                                                <button class="my-2 btn btn-<?php echo $jobType == 'Full Time' ? 'success' : ($jobType == 'Hybrid' ? 'warning' : 'danger') ?> btn-sm mx-4"><?php echo $jobType ?></button>
                                            </div>
                                            <!-- <button class="btn btn-danger btn-sm ">part time</button> -->
                                        </div>
                                    </div>
                                    <div class="float-start">
                                        <?php if (isset($_SESSION['userId']) && $_SESSION['type'] == 'Company' && $cUserId == $_SESSION['userId']) { ?>
                                            <a href="../jobs/viewUser.php?job_id=<?php echo $jobId ?>" class="btn btn-secondary btn-sm">NO OF APPLICATION : <?php echo $no_user_id; ?></a>
                                        <?php } else { ?>
                                            <button class="btn btn-secondary btn-sm" disabled>NO OF APPLICATION : <?php echo $no_user_id; ?></button>
                                        <?php } ?>
                                    </div>
                                    <br>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            } ?>
        </div>
    </div>
</div>
<?php require "../include/footer.php"; ?>