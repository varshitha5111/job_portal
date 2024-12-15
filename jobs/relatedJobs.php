<?php
require "../include/config.php";
require "../include/checkSession.php";
if (isset($_GET['job_id']) && isset($_GET['cat_id'])) {
    $job_cat_id = $_GET['cat_id'];
    $job_id = $_GET['job_id'];

    require "../include/nav.php"; ?>
    <div class="d-flex" style="height: 150px;">
        <div class="vr"></div>
    </div>
    <div class="container lead fw-bolder" style="color: white;">
        <h2>Categories</h2>
        <small><a href="../mainPage/index.php" id="link1" style="color:greenyellow">Home/</a><a href="../authentication/register.php" id="link2" style="color: white;">Register</a></small>
    </div>
    <div class="d-flex" style="height: 100px;">
        <div class="vr"></div>
    </div>
    <div class="p-20" style="background-color: white; width: 100%;" ?>
        <div class="container"><br>
        <?php 
    } 
        else if (isset($_SESSION['job_cat_id']) && isset($_SESSION['jobId'])) {
        $job_cat_id = $_SESSION['job_cat_id'];
        $job_id = $_SESSION['jobId'];
        echo '<div class="container ">';
        }
    $showJob = $conn->prepare("SELECT
                    company.company_user_id as userId,
                    company.company_id as cId,
                    company.company_name as cName,
                    jobs.job_title as jobTitle,
                    jobs.job_region as jobRegion,
                    jobs.job_type as jobType,
                    jobs.job_id as jobId,
                    company.company_image as cImage
                FROM
                    jobs
                INNER JOIN company ON company.company_id = jobs.company_id
                WHERE
                    jobs.job_cat_id=?
                AND
                    jobs.job_id!=? ");
    $showJob->execute(array(
        $job_cat_id,
        $job_id
    ));
    $jobListed = $showJob->rowCount();
        ?>
        
        
        
        <h2 class="text-center justify-content-center"><?php echo $jobListed ?> related jobs </h2>
        <div class="mx-5 my-4 row row-cols-1 row-cols-md-2 g-3">
            <?php
            while ($showJobDetails = $showJob->fetch(PDO::FETCH_ASSOC)) {
                $jobTitle = $showJobDetails['jobTitle'];
                $cName = $showJobDetails['cName'];
                $cId = $showJobDetails['cId'];
                $userId = $showJobDetails['userId'];
                $jobRegion = $showJobDetails['jobRegion'];
                $cImage = $showJobDetails['cImage'];
                $jobType = $showJobDetails['jobType'];
                $jobId = $showJobDetails['jobId']; ?>
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
                                                <button class="my-2 btn btn-<?php echo $jobType == 'Full Time' ? 'success' : ($jobType == 'Hybrid' ? 'warning' : 'danger') ?> btn-sm mx-4"><?php echo $jobType ?></button>
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