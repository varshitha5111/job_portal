<?php require "../include/nav.php";  ?>

<?php
if (!isset($_SESSION['userId'])) {
    header("location:../mainPage/index.php");
}
if(!isset($_SESSION['errors'])){
    $_SESSION['errors']=0;
    $_SESSION['errormsg']='';
}
if (isset($_GET['postJobUserId'])) {
    $userId = $_GET['postJobUserId'];
    if ($userId != $_SESSION['userId']) {
        echo '<script>
            window.location.href = "../mainPage/index.php";
        </script>';
    }
    $getCompany = $conn->prepare("select company_name,company_email,company_image from company where company_user_id=?");
    $getCompany->execute(array($_GET['postJobUserId']));
    $noRows = $getCompany->rowCount();
    while ($getCompanyDetails = $getCompany->fetch(PDO::FETCH_ASSOC)) {
        $avatar = $getCompanyDetails['company_image'];
        $cEmail = $getCompanyDetails['company_email'];
        $cName = $getCompanyDetails['company_name'];
    }
    $categories = $conn->prepare('select cat_id,cat_title from categories');
    $categories->execute();
} else if (isset($_POST['postJobSubmit'])) {
    echo "submit";
    $title = $_POST['title'];
    $checkRowsNo = $_POST['checkRowsNo'];
    $cName = $_POST['cName'];
    $cEmail = $_POST['cEmail'];
    $jobCat = $_POST['jobCat'];
    $userId = $_POST['userId'];
    $region = $_POST['region'];
    $salary = $_POST['salary'];
    $jobType = $_POST['jobType'];
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
        || empty($benifits)  || empty($cEmail) || empty($cName) || empty($cImage)
    ) {
        $_SESSION['errors']=1;
        $_SESSION['errormsg']= "<div class='my-1 alert alert-danger alert-dismissible fade show'>Please Fill The Complete Form
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div><hr>";
    } else {
        $_SESSION['errors']=0;
        $_SESSION['errormsg']='';
        echo $checkRowsNo;
        if ($checkRowsNo == 0) {
            $insertCompany = $conn->prepare("insert into company(company_user_id,company_name,company_email,company_image) values (?,?,?,?);");
            $insertCompany->execute(array(
                $_SESSION['userId'],
                $cName,
                $cEmail,
                $cImage
            ));
        }

        $insertJob = $conn->prepare("INSERT INTO jobs(
            job_title,
            job_region,
            job_type,
            job_cat_id,
            vacancy,
            experince,
            gender,
            salary,
            app_deadline,
            des,
            responsibility,
            benifits,
            company_id
        )
        VALUES(?,?,?,?,?,?,?,?,?,?,?,?,(select company_id from company where company_user_id=?))");
        $insertJob->execute(array(
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
            $_SESSION['userId']
        ));
    }
    // echo "hii".$_SESSION['errors'];
    echo '<script>window.location.href="../jobs/postJob.php?postJobUserId=' . $_SESSION['userId'] . '"</script>';
} else {
    echo '<script>window.location.href="../mainPage/index.php"</script>';
}

?>



<div class="d-flex" style="height: 150px;">
    <div class="vr"></div>
</div>
<div class="container lead fw-bolder" style="color: white;">
    <h2>Post Job</h2>
    <small><a href="../mainPage/index.php" id="link1" style="color:greenyellow">Home/</a><a href="../jobs/postJob.php?postJobUserId=<?php echo $_SESSION['userId']; ?>" id="link2" style="color: white;">Post Job</a></small>
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
                        <div class="card-body" id="card-body">
                            <div id="errors-msg">
                                <?php if(isset($_SESSION['errors'])){
                                    if($_SESSION['errors']==1){
                                        echo $_SESSION['errormsg'];
                                    }
                                }
                                ?>
                            </div>
                            <form method="POST" action="postJob.php" enctype="multipart/form-data">
                                <br>
                                <input type="hidden" value="<?php echo $noRows ?>" name="checkRowsNo">
                                <h3>Job Details</h3><br>
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Job Title</label>
                                    <input type="text" class="form-control" name="title" id="exampleFormControlInput1" placeholder="Enter Job Title" value="">
                                </div>
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Job Region</label>
                                    <input type="text" class="form-control" name="region" id="exampleFormControlInput1" placeholder="Enter Job Region" value="">
                                </div>
                                <div class="mb-3 form-group">
                                    <label>Job Type</label>
                                    <select class="my-1 form-control" name="jobType">
                                        <option>Part Time</option>
                                        <option>Full Time</option>
                                        <option>Hybrid</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Vacancy</label>
                                    <input type="text" class="form-control" name="vacancy" id="exampleFormControlInput1" placeholder="Enter Job Vacancy" value="">
                                </div>
                                <div class="mb-3 form-group">
                                    <label>Job Experince</label>
                                    <select class="my-1 form-control" name="jobExp">
                                        <option>Freshers</option>
                                        <option>1-2 yrs</option>
                                        <option>3-5 yrs</option>
                                        <option>6-8 yrs</option>
                                        <option>9-10 yrs</option>
                                        <option>more than 10 yrs</option>
                                    </select>
                                </div>
                                <div class="mb-3 form-group">
                                    <label>Job Category</label>
                                    <select class="my-1 form-control" name="jobCat" id="category" onchange="createCat()">
                                        <?php while ($category = $categories->fetch(PDO::FETCH_ASSOC)) { ?>
                                            <option value="<?php echo $category['cat_id'] ?>"><?php echo $category['cat_title'] ?></option>
                                        <?php } ?>
                                        <option value="1">Create Category</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Salary</label>
                                    <input type="text" class="form-control" name="salary" id="exampleFormControlInput1" placeholder="Enter Job Salary" value="">
                                </div>

                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Gender</label><br>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="inlineRadio1" value="male">
                                        <label class="form-check-label" for="inlineRadio1">Male</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="inlineRadio2" value="female">
                                        <label class="form-check-label" for="inlineRadio2">Female</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="inlineRadio3" value="any">
                                        <label class="form-check-label" for="inlineRadio3">Any</label>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Application DeadLine</label>
                                    <input type="date" class="form-control" name="deadline" min="<?php echo date("Y-m-d"); ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="inputPassword4" class="form-label">Description</label><br>
                                    <textarea class="summernote" name="desc"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="inputPassword4" class="form-label">Job Responsibility</label>
                                    <textarea class="summernote" name="responsibility" rows="3" style="display: none;"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="inputPassword4" class="form-label">other Benifits</label>
                                    <textarea class="summernote" name="benifits" rows="3" style="display: none;"></textarea>
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
                                    <img src="../images/<?php echo $avatar; ?>" width="50">
                                    <input name="oldImg" type="hidden" value="<?php echo empty($avatar) ? "" : $avatar; ?>">
                                    <input name="cImage" type="file" class="form-control-file" id="image">
                                </div>

                                <div class="mb-3">
                                    <button type="submit" name="postJobSubmit" class="btn" id="button">Submit</button>
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