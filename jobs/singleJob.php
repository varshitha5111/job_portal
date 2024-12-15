<?php require "../include/nav.php";  ?>

<?php
if (isset($_GET['singleJobId'])) {
    $jobId = $_GET['singleJobId'];
    $_SESSION['jobId'] = $jobId;
    $checkWhetherJobPresent = $conn->prepare("select count(*) as 'num' from jobs where job_id=?");
    $checkWhetherJobPresent->execute(array(
        $jobId
    ));
    while ($check = $checkWhetherJobPresent->fetch(PDO::FETCH_ASSOC)) {
        $noOfJobs = $check['num'];
    }
    if ($noOfJobs == 0) {
        echo '<script>window.location.href="../mainPage/index.php"</script>';
    }
    $getSingleJob = $conn->prepare("SELECT
    jobs.job_id as job_id,
    jobs.job_title as job_title,
    jobs.job_region as job_region,
    jobs.job_type as job_type,
    jobs.vacancy as vacancy,
    jobs.experince as experince,
    jobs.gender as gender,
    jobs.salary as salary,
    jobs.job_cat_id as job_cat_id,
    jobs.app_deadline as app_deadline,
    jobs.des as des,
    jobs.responsibility as responsibility,
    jobs.created_at as created_at,
    jobs.company_id as company_id,
    company.company_name as company_name,
    company.company_image as company_image,
    company.company_user_id as company_user_id,
    jobs.benifits as benifits
    FROM
    jobs
    INNER JOIN
    company
    ON
    jobs.company_id=company.company_id
    WHERE
    jobs.job_id = ?");

    $getSingleJob->execute(array($jobId));
    while ($getSingleJobDetails = $getSingleJob->fetch(PDO::FETCH_ASSOC)) {
        $job_title = $getSingleJobDetails['job_title'];
        $job_region = $getSingleJobDetails['job_region'];
        $job_type = $getSingleJobDetails['job_type'];
        $vacancy = $getSingleJobDetails['vacancy'];
        $experince = $getSingleJobDetails['experince'];
        $_SESSION['job_cat_id'] = $getSingleJobDetails['job_cat_id'];
        $gender = $getSingleJobDetails['gender'];
        $salary = $getSingleJobDetails['salary'];
        $app_deadline = $getSingleJobDetails['app_deadline'];
        $des = $getSingleJobDetails['des'];
        $responsibility = $getSingleJobDetails['responsibility'];
        $created_at = $getSingleJobDetails['created_at'];
        $company_id = $getSingleJobDetails['company_id'];
        $company_name = $getSingleJobDetails['company_name'];
        $company_image = $getSingleJobDetails['company_image'];
        $company_user_id = $getSingleJobDetails['company_user_id'];
        $benifits = $getSingleJobDetails['benifits'];
    }
}
if (isset($_SESSION['userId'])) {

    $checkWhetherApplied = $conn->prepare("select applicationNo from appliedjobs where job_id=? and user_id=?");
    $checkWhetherApplied->execute(array($jobId, $_SESSION['userId']));
    $haveTheUserApplied = $checkWhetherApplied->rowCount();

    $checkWhetherSaved = $conn->prepare("select savedAppNo from savedjobs where job_id=? and user_id=?");
    $checkWhetherSaved->execute(array($jobId, $_SESSION['userId']));
    $haveTheUserSaved = $checkWhetherSaved->rowCount();
}
?>

<div class="d-flex" style="height: 150px;">
    <div class="vr"></div>
</div>

<div class="container lead fw-bolder" style="color: white;">
    <h2>Post Job</h2>
    <small><a href="../mainPage/index.php" id="link1" style="color:greenyellow">Home/</a><a href="" id="link2" style="color: white;">JOB</a></small>
</div>

<div class="d-flex" style="height: 100px;">
    <div class="vr"></div>
</div>

<!-- single post by the company card -->
<div class="p-5" style="background-color: white;">
    <div class="container ">
        <div class="mx-5 my-4 row row-cols-1 row-cols-md-2 g-3">
            <div class="mx-3 my-1 col-sm-12  col-md-12  col-lg-12">
                <div class="card mb-2 w-290">
                    <div class="row g-0">
                        <div class="col-md-2  d-flex align-item-start flex-column">
                            <img src="../images/<?php echo $company_image ?>" class="img-fluid rounded-start" alt="...">
                        </div>
                        <div class="col-md-10">
                            <div class="card-body text-center"><br>
                                <div class="row">
                                    <div class="col">
                                        <figure>
                                            <blockquote class="blockquote">
                                                <p> <?php echo $job_title ?> </p>
                                            </blockquote>
                                            <figcaption class="blockquote-footer">
                                                <a href="../jobs/companyJobs.php?company_id=<?php echo $company_id ?>"><?php echo $company_name ?></a>
                                            </figcaption>
                                        </figure>
                                    </div>
                                    <div class="col ">
                                        <script src="https://unpkg.com/feather-icons"></script>
                                        <span class="fs-6"><?php echo $job_region ?></span>
                                    </div>
                                    <div class="col ">
                                        <span class="btn btn-<?php echo $job_type == 'Full Time' ? 'success' : ($job_type == 'Hybrid' ? 'warning' : 'danger') ?>"><?php echo $job_type ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- job details -->
<div class="p-5" style="background-color: white;">
    <div class="container">
        <div class="row row-cols-1 row-cols-md-2 g-4">
            <div class="col">
                <div class="card">
                    <img src="../images/hero_1.jpg" class="card-img" alt="...">
                </div>
            </div>
            <div class="col">
                <div class="col">
                    <div class="card">
                        <div class="card-body" style="background-color: #D0D0D0 !important;">
                            <h5 class="card-title text-center">Job Details</h5>
                            <p class="card-text">
                            <p class="fw-medium">Published On:December : <span class="fw-normal"><?php echo $created_at ?></span></p>
                            <p class="fw-medium">Vacancy : <span class="fw-normal"><?php echo $vacancy ?></span></p>
                            <p class="fw-medium">Job Type : <span class="fw-normal"><?php echo $job_type ?></span></p>
                            <p class="fw-medium">Experince : <span class="fw-normal"><?php echo $experince ?></span></p>
                            <p class="fw-medium">Job Location : <span class="fw-normal"><?php echo $job_region ?></span></p>
                            <p class="fw-medium">Salary : <span class="fw-normal"><?php echo $salary ?></span></p>
                            <p class="fw-medium">Gender : <span class="fw-normal"><?php echo $gender ?></span></p>
                            <p class="fw-medium">Application Deadline : <span class="fw-normal"><?php echo $app_deadline ?></span></p>
                            <p class="fw-medium">More Jobs By <?php echo $company_name ?>:<a href="../jobs/companyJobs.php?company_id=<?php echo $company_id ?>">Click Here</a></p>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col my-2">
                    <div class="card">
                        <div class="card-body" style="background-color: #D0D0D0 !important;">
                            <h5 class="card-title text-center">Job Category</h5>
                            <?php
                            $getCat = $conn->prepare("select cat_id,cat_title from categories");
                            $getCat->execute();
                            while ($getCatDetails = $getCat->fetch(PDO::FETCH_ASSOC)) {
                                $cat_title = $getCatDetails['cat_title'];
                                $cat_id = $getCatDetails['cat_id']; ?>
                                <p class="card-text mx-3">
                                    <a href="../jobs/relatedJobs.php?job_id=<?php echo 0 ?>&cat_id=<?php echo $cat_id; ?>" style="text-transform: capitalize; color: black; text-decoration: none; font-weight: 700;"><?php echo $cat_title ?></a><br>

                                </p>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- job responsibility and description -->
        <div class="col col-7 my-3">

            <!-- Responsibility -->
            <span class="mx-2 fs-4 fw-bold" style="color: greenyellow;">Job Responsibility</span>
            <p class="my-3 lead fw-medium" style="color: grey;">
                <?php echo $responsibility ?>
            </p>

            <!-- Description -->
            <span class="mx-2 my-3 fs-4 fw-bold" style="color: greenyellow;">Job Description</span>
            <p class="my-3 lead fw-medium" style="color: grey;">
                <?php echo $des ?>
            </p>

            <span class="mx-2 my-3 fs-4 fw-bold" style="color: greenyellow;">Job Benifts</span>
            <p class="my-3 lead fw-medium" style="color: grey;">
                <?php echo $benifits ?>
            </p>
            <?php if (isset($_SESSION['userId']) && $_SESSION['type'] != 'Company') { ?>

                <?php if (date("Y-m-d", strtotime($app_deadline)) > date('Y-m-d')) { ?>
                    <?php if ($haveTheUserSaved == 1) { ?>
                        <form method="POST" action="savedJob.php">
                            <input type="hidden" value="<?php echo $jobId ?>" name="job_id">
                            <input type="submit" class="mx-5 btn btn-secondary btn-lg" value="Saved" name="unSave">
                        </form>
                    <?php } else { ?>
                        <form method="POST" action="savedJob.php">
                            <input type="hidden" value="<?php echo $jobId ?>" name="job_id">
                            <input type="submit" class="mx-5 btn btn-secondary btn-lg" value="Save Job" name="saveJob">
                        </form>
                    <?php } ?>
                    <?php if ($haveTheUserApplied == 1) { ?>
                        <button id="button" type="button" class="btn  mx-2 my-1 btn-lg">
                            Applied Job
                        </button>
                    <?php } else { ?>
                        <button id="button" type="button" class="btn  mx-5 my-2 btn-lg" data-bs-toggle="modal" data-bs-target="#applyJobModel">
                            Apply Job
                        </button>
                    <?php } ?>
                <?php } else { ?>
                    <button class="btn btn-danger mx-5 my-2 btn-lg" data-bs-toggle="modal" data-bs-target="#applyJobModel" disabled>
                        Application Closed
                    </button>
                <?php  } ?>
                <!-- modal -->
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal fade" id="applyJobModel" tabindex="-1" aria-labelledby="applyJobModelLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <div class="row ">
                                        <div class="card" style="border: 0px solid white;">
                                            <div class="card-header">
                                                <h5>User Profile</h5>
                                            </div>
                                            <div class="card-body">
                                                <?php
                                                if (empty($_SESSION['cv'])) {
                                                    $updateButtonPresnt = 1;
                                                    echo '<h4>Update The Profile</h4><br>'; ?>
                                                    <div class="d-grid gap-4 col-6 mx-auto">
                                                        <a role="button" class="btn rounded" id="button" href="../userPage/update.php?updateProfile=<?php echo $_SESSION["userId"] ?>">Update Profile/Cv</a><br><br>
                                                    </div>
                                                <?php    } else {
                                                    $updateButtonPresnt = 0; ?>
                                                    <form method="POST" action="applyJob.php">

                                                        <div class="mb-3">
                                                            <label for="exampleFormControlInput1" class="form-label">Name</label>
                                                            <input name="uName" type="text" class="form-control" id="exampleFormControlInput1" value="<?php echo $_SESSION['username']; ?>"  readonly="true">
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="exampleFormControlInput1" class="form-label">Email</label>
                                                            <input name="email" type="email" class="form-control" id="exampleFormControlInput1" value="<?php echo $_SESSION['email'] ?>" readonly="true">
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="exampleFormControlInput1" class="form-label">Company</label>
                                                            <input name="cName" type="text" class="form-control" id="exampleFormControlInput1" value="<?php echo $company_name ?>" readonly="true">
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="exampleFormControlInput1" class="form-label">Cv</label>
                                                            <input name="cv" type="text" class="form-control" id="exampleFormControlInput1" value="<?php echo $_SESSION['cv'] ?>" readonly="true">
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="exampleFormControlInput1" class="form-label">Job Roll</label>
                                                            <input name="job_title" type="text" class="form-control" id="exampleFormControlInput1" value="<?php echo $job_title ?>" readonly="true">
                                                        </div>

                                                        <input name="job_id" type="hidden" class="form-control" id="exampleFormControlInput1" value="<?php echo $jobId; ?>">

                                                        <div class="mb-3">
                                                            <input type="submit" name="appliedUserId" class="btn" id="button" value="Apply Now">
                                                        </div>
                                                    </form>
                                                <?php } ?>
                                                <?php if($updateButtonPresnt==0){ ?>
                                                <div class="d-grid col-6 ">
                                                    <a role="button" class="btn rounded" id="button" href="../userPage/update.php?updateProfile=<?php echo $_SESSION["userId"] ?>">Update Profile/Cv</a><br>
                                                </div>
                                                <div class="d-grid col-6 ">
                                                    <a role="button" class="btn rounded" id="button" href="../images/<?php echo $_SESSION["cv"] ?>">Download Cv</a><br><br>
                                                </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <?php if (isset($_SESSION['userId']) && $_SESSION['userId'] == $company_user_id) { ?>
                <a class="mx-5 btn btn-secondary btn-lg" href="updateJob.php?jobId=<?php echo $jobId ?>&company_user_id=<?php echo $company_user_id ?>">
                    Update
                </a>
                <a class="btn mx-1 my-2 btn-danger btn-lg" href="deleteJob.php?deleteJobId=<?php echo $jobId ?>">Delete</a>
            <?php } ?>
        </div>
    </div>
</div>

<!-- display related jobs -->
<div class="p-5" style="background-color: white;">
    <?php require "../jobs/relatedJobs.php"; ?>
</div>

<?php require "../include/footer.php"; ?>