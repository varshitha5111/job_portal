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
    echo '<script>window.location.href="../mainPage/index.php"</script>';
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
        if (isset($_POST['submit'])) {
            $showJob = $conn->prepare("
            select 
            company.company_name as cName,
            jobs.job_title as job_title,
            COALESCE(users.user_id,0) as user_id,
            users.name as name,
            users.email as email,
            users.image as image,
            users.cv as cv,
            jobs.company_id as cId,
            jobs.job_id as job_id,
            appliedjobs.status as status,
            appliedjobs.applicationNo as applicationNo
        from 
            company
        left JOIN
            jobs on company.company_id=jobs.company_id
        left JOIN
             appliedjobs on appliedjobs.job_id=jobs.job_id
        left join 
             users on users.user_id=appliedjobs.user_id
        where company.company_user_id=?
        order by 
              jobs.job_id;");
            $showJob->execute(array($_POST['company_user_id']));
            $jobListed = $showJob->rowCount();
        ?>
            <div class="mx-5 my-4 row row-cols-1 row-cols-md-2 g-3">
                <?php
                $previous = 0;
                while ($showJobDetails = $showJob->fetch(PDO::FETCH_ASSOC)) {
                    $user_id = $showJobDetails['user_id'];
                    $cId = $showJobDetails['cId'];
                    $cName = $showJobDetails['cName'];
                    $job_id = $showJobDetails['job_id'];
                    $job_title = $showJobDetails['job_title'];
                    $status = $showJobDetails['status'];
                    $applicationNo = $showJobDetails['applicationNo'];
                    $name = $showJobDetails['name'];
                    $email = $showJobDetails['email'];
                    $img = $showJobDetails['image'];
                    $cv = $showJobDetails['cv'];
                    $currentJobId = $job_id;
                ?>
                    <?php if ($currentJobId != $previous) { ?>
                        <h4>
                            <span style="text-transform: uppercase;"><?php echo $cName ?></span>
                            >>
                            <a href="../jobs/singleJob.php?singleJobId=<?php echo $job_id ?>" style="text-decoration: none;"><?php echo $job_title ?></a>
                        </h4>
                    <?php } ?>
                    <div class="mx-3 my-1 col-sm-12  col-md-12  col-lg-12">
                        <hr>
                        <div class="card mb-3 w-300">
                            <?php if ($user_id != 0) { ?>
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
                                                    <div class="d-grid col-6 ">
                                                        <a role="button" class="btn rounded" id="button" href="../images/<?php echo $cv; ?>">Download Cv</a><br>
                                                    </div>
                                                    <form method="POST" action="allJobApplication.php">
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
                            <?php } else if ($user_id == 0) { ?>
                                <h4 class="text-center">No Application To This Post Yet!</h4>
                            <?php } ?>
                        </div>
                    </div>
                <?php
                    $previous = $currentJobId;
                } ?>
            </div>
    </div>
</div>
<?php } ?>