<?php require "../include/nav.php";  ?>

<?php
// update jobs 
if (!isset($_SESSION['userId'])) {
    header("location:../mainPage/index.php");
}
$categories = $conn->prepare('select cat_id,cat_title from categories');
$categories->execute();
if (isset($_GET['jobId']) && $_GET['company_user_id'] == $_SESSION['userId']) {
    $jobId = $_GET['jobId'];
    $getJob = $conn->prepare("SELECT
    jobs.job_id AS job_id,
    jobs.job_title AS job_title,
    jobs.job_region AS job_region,
    jobs.job_type AS job_type,
    jobs.vacancy AS vacancy,
    jobs.experince AS experince,
    jobs.gender AS gender,
    jobs.salary AS salary,
    jobs.app_deadline AS app_deadline,
    jobs.des AS des,
    jobs.responsibility AS responsibility,
    jobs.created_at AS created_at,
    jobs.company_id AS company_id,
    jobs.benifits AS benifits,
    jobs.job_cat_id AS job_cat_id,
    company.company_user_id AS company_user_id,
    company.company_name AS cName,
    company.company_email AS cEmail,
    company.company_image AS cImage
FROM
    jobs
INNER JOIN company ON company.company_id = jobs.company_id
WHERE
    jobs.job_id = ?;");
    $getJob->execute(array($_GET['jobId']));
    $noRows = $getJob->rowCount();
    while ($getJobDetails = $getJob->fetch(PDO::FETCH_ASSOC)) {
        $jobId = $getJobDetails['job_id'];
        $job_title = $getJobDetails['job_title'];
        $job_region = $getJobDetails['job_region'];
        $job_type = $getJobDetails['job_type'];
        $vacancy = $getJobDetails['vacancy'];
        $exp = $getJobDetails['experince'];
        $gender = $getJobDetails['gender'];
        $salary = $getJobDetails['salary'];
        $app_deadline = $getJobDetails['app_deadline'];
        $des = $getJobDetails['des'];
        $responsibility = $getJobDetails['responsibility'];
        $created_at = $getJobDetails['created_at'];
        $company_id = $getJobDetails['company_id'];
        $benifits = $getJobDetails['benifits'];
        $job_cat_id = $getJobDetails['job_cat_id'];
        $cEmail = $getJobDetails['cEmail'];
        $cName = $getJobDetails['cName'];
        $avatar = $getJobDetails['cImage'];
    }
} else if (isset($_POST['updateJobSubmit'])) {
    $title = $_POST['title'];
    $jobId = $_POST['jobId'];
    $cName = $_POST['cName'];
    $cEmail = $_POST['cEmail'];
    $jobCat = $_POST['jobCat'];
    $userId = $_POST['userId'];
    $region = $_POST['region'];
    $salary = $_POST['salary'];
    $jobType = $_POST['jobType'];
    $company_id = $_POST['company_id'];
    $vacancy = $_POST['vacancy'];
    $jobExp = $_POST['jobExp'];
    $gender = $_POST['gender'];
    $deadline = $_POST['deadline'];
    $desc = $_POST['desc'];
    $responsiblity = $_POST['responsibility'];
    $benifits = $_POST['benifits'];
    $cEmail = $_POST['cEmail'];
    $cName = $_POST['cName'];
    $oldImg = $_POST['oldImg'];
    $cImage = $_FILES['cImage']['name'];
    if (empty($cImage)) {
        $cImage = $oldImg;
    }
    $image_temp_name = $_FILES['cImage']['tmp_name'];
    move_uploaded_file("$image_temp_name", "C:/xampp/htdocs/varshithaPhp/jobportal/images/$cImage");
    if (
        empty($title) || empty($region) || empty($salary) || empty($jobType) || empty($vacancy) ||
        empty($jobExp) || empty($gender) || empty($deadline) || empty($desc) || empty($responsiblity)
        || empty($benifits) || empty($cEmail) || empty($cName) || empty($cImage)
    ) {
        echo "<div class='alert alert-danger alert-dismissible fade show'>Please Fill The Complete Form
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>";
    } else {

        $updateJob = $conn->prepare("update  jobs set
            job_title = ?,
            job_region = ?,
            job_type = ?,
            job_cat_id=?,
            vacancy = ?,
            experince = ?,
            gender = ?,
            salary = ?,
            app_deadline = ?,
            des = ?,
            responsibility = ?,
            benifits = ?
        WHERE
           job_id=?");
        $updateJob->execute(array(
            $title,
            $region,
            $jobType,
            $jobCat,
            $vacancy,
            $jobExp,
            $gender,
            $salary,
            $deadline,
            $desc,
            $responsiblity,
            $benifits,
            $jobId
        ));

        $updateCompany = $conn->prepare("
        update company set company_name=?,company_email=?,company_image=? where company_id=?");
        $updateCompany->execute(array(
            $cName,
            $cEmail,
            $cImage,
            $company_id
        ));

        echo '<script>window.location.href="../jobs/updateJob.php?jobId=' . $jobId . '&company_user_id=' . $_SESSION['userId'] . '"</script>';
    }
} else {
    echo '<script>window.location.href="../mainPage/index.php"</script>';
}

?>

<div class="d-flex" style="height: 150px;">
    <div class="vr"></div>
</div>
<div class="container lead fw-bolder" style="color: white;">
    <h2>Update Job</h2>
    <small><a href="../mainPage/index.php" id="link1" style="color:greenyellow">Home/</a><a href="../jobs/singleJob.php?singleJobId=<?php echo $jobId; ?>" id="link2" style="color: white;">Job</a></small>
</div>
<div class="d-flex" style="height: 100px;">
    <div class="vr"></div>
</div>
<div class="p-20" style="background-color: white; width: 100%;" ?>

    <div class="my-5 container">
        <!-- Stack the columns on mobile by making one full-width and the other half-width -->
        <div class="container-sm-7">
            <div class="row">
                <div class="col-10">
                    <div class="my-5 card">
                        <div class="card-body">
                            <form method="POST" action="updateJob.php" enctype="multipart/form-data">
                                <br>
                                <input type="hidden" value="<?php echo $jobId ?>" name="jobId">
                                <input type="hidden" value="<?php echo $company_id ?>" name="company_id">
                                <h3>Job Details</h3><br>
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Job Title</label>
                                    <input type="text" class="form-control" name="title" id="exampleFormControlInput1" placeholder="Enter Job Title" value="<?php echo $job_title ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Job Region</label>
                                    <input type="text" class="form-control" name="region" id="exampleFormControlInput1" placeholder="Enter Job Region" value="<?php echo $job_region ?>">
                                </div>
                                <div class="mb-3 form-group">
                                    <label>Job Type</label>
                                    <select class="my-1 form-control" name="jobType">
                                        <option <?php echo $job_type == 'Part Time' ? 'selected' : ''; ?>>Part Time</option>
                                        <option <?php echo $job_type == 'Full Time' ? 'selected' : ''; ?>>Full Time</option>
                                        <option <?php echo $job_type == 'Hybrid' ? 'selected' : ''; ?>>Hybrid</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Vacancy</label>
                                    <input type="text" class="form-control" name="vacancy" id="exampleFormControlInput1" placeholder="Enter Job Vacancy" value="<?php echo $vacancy ?>">
                                </div>
                                <div class="mb-3 form-group">
                                    <label>Experince</label>
                                    <select class="my-1 form-control" name="jobExp" value="<?php echo $exp ?>">
                                        <option <?php echo $exp == 'Freshers' ? 'selected' : ''; ?>>Freshers</option>
                                        <option <?php echo $exp == '1-2 yrs' ? 'selected' : ''; ?>>1-2 yrs</option>
                                        <option <?php echo $exp == '3-5 yrs' ? 'selected' : ''; ?>>3-5 yrs</option>
                                        <option <?php echo $exp == '6-8 yrs' ? 'selected' : ''; ?>>6-8 yrs</option>
                                        <option <?php echo $exp == '9-10 yrs' ? 'selected' : ''; ?>>9-10 yrs</option>
                                        <option <?php echo $exp == 'more than 10 yrs' ? 'selected' : ''; ?>>more than 10 yrs</option>
                                    </select>
                                </div>
                                <div class="mb-3 form-group">
                                    <label>Job Category</label>
                                    <select class="my-1 form-control" name="jobCat" id="category" onchange="createCat()">
                                        <?php while ($category = $categories->fetch(PDO::FETCH_ASSOC)) { ?>
                                            <option value="<?php echo $category['cat_id'] ?>" <?php echo $category['cat_id'] == $job_cat_id ? 'selected' : '' ?>><?php echo $category['cat_title'] ?></option>
                                        <?php } ?>
                                        <option value="1">Create Category</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Salary</label>
                                    <input type="text" class="form-control" name="salary" id="exampleFormControlInput1" placeholder="Enter Job Salary" value="<?php echo $salary ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Gender</label><br>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="inlineRadio1" value="male" <?php echo $gender == 'male' ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="inlineRadio1">Male</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="inlineRadio2" value="female" <?php echo $gender == 'female' ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="inlineRadio2">Female</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="inlineRadio3" value="any" <?php echo $gender == 'any' ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="inlineRadio3">Any</label>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Application DeadLine</label>
                                    <input type="date" class="form-control" name="deadline" min="<?php echo date("Y-m-d"); ?>" value="<?php echo date("Y-m-d", strtotime($app_deadline)); ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="exampleFormControlTextarea1" class="form-label">Job Description</label>
                                    <textarea class="summernote" name="desc" id="exampleFormControlTextarea1" rows="3"><?php echo $des ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="exampleFormControlTextarea1" class="form-label">Job Responsibility</label>
                                    <textarea class="summernote" name="responsibility" id="exampleFormControlTextarea1" rows="3"><?php echo $responsibility ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="exampleFormControlTextarea1" class="form-label">other Benifits</label>
                                    <textarea class="summernote" name="benifits" id="exampleFormControlTextarea1" rows="3"><?php echo $benifits ?></textarea>
                                </div>
                                <br>
                                <h3 class="my-1">Company Details</h3><br>
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Company Email</label>
                                    <input type="email" class="form-control" name="cEmail" id="exampleFormControlInput1" placeholder="Enter Company Email" value="<?php echo empty($cEmail) ? "" : $cEmail ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Company Name</label>
                                    <input type="text" class="form-control" name="cName" id="exampleFormControlInput1" placeholder="Enter Company Name" value="<?php echo empty($cName) ? "" : $cName ?>">
                                </div>
                                <input name="userId" type="hidden" value="<?php echo $userId; ?>">
                                <div class="mb-3">
                                    <label for="post-image">Company Image</label><br>
                                    <input name="oldImg" type="hidden" value="<?php echo empty($avatar) ? "" : $avatar; ?>">
                                    <img src="../images/<?php echo $avatar ?>" width="50">
                                    <input name="cImage" type="file" class="form-control-file" id="image">
                                </div>

                                <div class="mb-3">
                                    <button type="submit" name="updateJobSubmit" class="btn" id="button">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require "../include/footer.php"; ?>