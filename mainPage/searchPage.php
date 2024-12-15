<?php
require "../include/nav.php";
if (isset($_POST['search'])) {
    $job_title = $_POST['job_title'];
    $job_type = $_POST['type'];
    $region = $_POST['region'];

    $search = $conn->prepare('SELECT
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
                  where jobs.job_title like "%'.$job_title.'%" && jobs.job_type=? && jobs.job_region=? 
                  GROUP BY
                  jobs.job_id
                  ORDER BY
                  jobs.created_at desc;
        ');
    $search->execute(array(
        $job_type,
        $region
    ));
    $jobListed=$search->rowCount();
    $done=0;
    $checkWhetherKeyPresent=$conn->prepare('select count(name) as "count of names" from trendingkey where name like "%'.$job_title.'%"');
    $checkWhetherKeyPresent->execute();
    while($count=$checkWhetherKeyPresent->fetch(PDO::FETCH_ASSOC)){
        $countOfName=$count['count of names'];
        if($countOfName==0){
            $insert=$conn->prepare("insert into trendingkey(name) values(?)");
            $insert->execute(array($job_title));
            $done=1;
        }
    }
}
?>

<div class="d-flex" style="height: 150px;">
    <div class="vr"></div>
</div>
<div class="container lead fw-bolder" style="color: white;">
    <h2>Search Result</h2>
    <small><a href="../mainPage/index.php" id="link1" style="color:greenyellow">Home/</a><a href="" id="link2" style="color: white;">Search</a></small>
</div>
<div class="d-flex" style="height: 100px;">
    <div class="vr"></div>
</div>
<div class="p-20" style="background-color: white; width: 100%;" ?>
    <div class="container">
        <h2 class="text-center justify-content-center"><?php echo $jobListed ?> Search Results</h2>
        <div class="mx-5 my-4 row row-cols-1 row-cols-md-2 g-3">
            <?php
            while ($searchDetails = $search->fetch(PDO::FETCH_ASSOC)) {
                $jobTitle = $searchDetails['jobTitle'];
                $cName = $searchDetails['cName'];
                $cUserId = $searchDetails['cUserId'];
                $jobRegion = $searchDetails['jobRegion'];
                $cImage = $searchDetails['cImage'];
                $jobType = $searchDetails['jobType'];
                $jobId = $searchDetails['jobId'];
                $cId = $searchDetails['cId'];
                $no_user_id = $searchDetails['no_user_id']; ?>
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
                                                <button class="my-2 btn btn-<?php echo $jobType == 'Full Time' ? 'success' : 'danger' ?> btn-sm mx-4"><?php echo $jobType ?></button>
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
            <?php } ?>
        </div>
    </div>