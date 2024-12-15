<?php require "../include/nav.php" ;

?>

<!-- vertical gap -->
<div class="d-flex" style="height: 200px;">
      <div class="vr">
      </div>
</div>

<!-- start of index page title on the image -->
<div class="container">
      <div class="lead fw-bolder text-center" style="color: white;">
            <h2 class="display-5">
                  The Easiest Way To Get Your Dream Job
            </h2>
            <small>Lorem ipsum dolor sit amet, consectetur adipisicing elit</small>
      </div>
      <div class="row my-3 mx-4 justify-content-center">
            <form method="POST" action="searchPage.php">
                  <div class="row mx-5 g-2 justify-content-center">
                        <div class="mx-1 my-1 col-sm-2  col-md-2  col-lg-2 p-2">
                              <input type="text" class="form-control" placeholder="job_title" name="job_title" aria-label="First name">
                        </div>
                        <div class="mx-1 my-1 col-sm-2  col-md-2  col-lg-2  p-2">
                              <select name="type" class="form-select" id="floatingSelectGrid">
                                    <option selected>Job Type</option>
                                    <option>Hybrid</option>
                                    <option>Part Time</option>
                                    <option>Full Time</option>
                              </select>
                        </div>
                        <div class="mx-1 my-1 col-sm-2  col-md-2  col-lg-2  p-2">
                              <select name="region" class="form-select" id="floatingSelectGrid">
                                    <option selected>Job Region</option>
                                    <?php
                                    $getRegions = $conn->prepare("select job_region from jobs");
                                    $getRegions->execute();
                                    while ($getRegionsDetails = $getRegions->fetch(PDO::FETCH_ASSOC)) {
                                          $region = $getRegionsDetails['job_region'];
                                    ?>
                                          <option><?php echo $region ?></option> <?php } ?>
                              </select>
                        </div>
                        <div class="mx-1 my-1 col-sm-2  col-md-2  col-lg-2 p-2">
                              <input type="submit" id="button" class="btn btn-rounded p-2  form-control" placeholder="" value="search" name="search" aria-label="First name">
                        </div>
                  </div>
            </form>
      </div>
      <div class="row mx-2 my-1 justify-content-center">
            <div class="mx-1 my-1 col-sm-8  col-md-8  col-lg-8">
                  <small class="mx-2" style="color: white;">Trending Keywords : </small>
                  <?php
                  $trendingKeys = $conn->prepare("select name from trendingkey order by id desc limit 5");
                  $trendingKeys->execute();
                  while ($trendingKeysDetails = $trendingKeys->fetch(PDO::FETCH_ASSOC)) {
                        $name = $trendingKeysDetails['name']; ?>
                        <button class="btn btn-rounded btn-sm btn-outline-light"><?php echo $name ?></button>
                  <?php } ?>
            </div>
      </div>
</div>

<!-- vertical gap -->
<div class="d-flex" style="height: 100px;">
      <div class="vr">
      </div>
</div>

<!-- about job portal heading  and no of candidates -->
<div class="p-5" style="background-color: rgba(173,255,47,0.7);">
      <div class="lead text-center" style="color: white;"><br>
            <h4 class="my-3">
                  JobBoard Site Stats
            </h4>
            <h4>
                  Lorem ipsum dolor sit amet consectetur adipisicing elit. Expedita unde
            </h4>
            <h4>
                  officiis recusandae sequi excepturi corrupti.
            </h4>
      </div><br>
      <div class="container">
            <div class="row mx-2 my-1 justify-content-center fw-bolder" style="color: white; font-weight: bold;">
                  <?php
                  $getNumber = $conn->prepare("select count(distinct(users.user_id)),count(distinct(jobs.job_id)),count(distinct(appliedJobs.applicationNo)),count(distinct(company.company_id)) from users,jobs,appliedJobs,company;");
                  $getNumber->execute();
                  while ($getNumberDetails = $getNumber->fetch(PDO::FETCH_ASSOC)) {
                        $candidates = $getNumberDetails['count(distinct(users.user_id))'];
                        $jobPosted = $getNumberDetails['count(distinct(jobs.job_id))'];
                        $jobFilled = $getNumberDetails['count(distinct(appliedJobs.applicationNo))'];
                        $company = $getNumberDetails['count(distinct(company.company_id))'];
                  }

                  ?>
                  <div class="mx-4 my-1 col-sm-2  col-md-2  col-lg-2">
                        <figure>
                              <blockquote class="blockquote">
                                    <p class="display-5"><?php echo $candidates ?></p>
                              </blockquote>
                              <figcaption class="footer">
                                    <cite title="Source Title">Candidates</cite>
                              </figcaption>
                        </figure>
                  </div>
                  <div class="mx-4 my-1 col-sm-2  col-md-2  col-lg-2">
                        <figure>
                              <blockquote class="blockquote">
                                    <p class="display-5"><?php echo $jobPosted ?></p>
                              </blockquote>
                              <figcaption class="footer">
                                    <cite title="Source Title">Jobs Posted</cite>
                              </figcaption>
                        </figure>
                  </div>
                  <div class="mx-4 my-1 col-sm-2  col-md-2  col-lg-2">
                        <figure>
                              <blockquote class="blockquote">
                                    <p class="display-5"><?php echo $jobFilled; ?></p>
                              </blockquote>
                              <figcaption class="footer">
                                    <cite title="Source Title">Job Filled</cite>
                              </figcaption>
                        </figure>
                  </div>
                  <div class="mx-4 my-1 col-sm-2  col-md-2  col-lg-2">
                        <figure>
                              <blockquote class="blockquote">
                                    <p class="display-5"><?php echo $company ?></p>
                              </blockquote>
                              <figcaption class="footer">
                                    <cite title="Source Title">Companies</cite>
                              </figcaption>
                        </figure>
                  </div>
            </div>
      </div>
</div>

<!-- showing jobs -->
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
                  $i = 0;
                  while (($showJobDetails = $showJob->fetch(PDO::FETCH_ASSOC)) && $i < 5) {
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
                  <?php $i++;
                  } ?>
            </div>
      </div>
</div>

<!-- sign up button -->
<div class="p-5" style="background-color: rgba(173,255,47,0.7);">
      <div class="lead container" style="color: white;">
            <div class="clear-fix">
                  <div class="float-start">
                        <h2 class="my-1">
                              Looking For Jobs?
                        </h2>
                  </div>
                  <div class="float-end d-grid gap-2 col-2 mx-1">
                        <?php if (isset($_SESSION['userId'])) { ?>
                              <a href="../authentication/register.php" class="btn btn-warning btn-lg" value="Sign up">Sign up</a>
                        <?php } ?>
                  </div>
                  <div class="float-end d-grid gap-2 col-2 mx-1">
                        <a href="../jobs/allJobs.php" class="btn btn-warning btn-lg" value="">More Jobs</a>
                  </div>
            </div>
      </div><br>
</div>

<!-- another heading -->
<div class="p-5" style="background-color: white;">

      <div class="container text-center justify-content-center">
            <h2 class="">
                  Company We've Helped
            </h2><br>
            <h4 style="color: grey;">
                  Porro error reiciendis commodi beatae omnis similique
            </h4>
            <h4 style="color: grey;">
                  voluptate rerum ipsam fugit mollitia ipsum facilis expedita tempora suscipit iste
            </h4>
      </div>
</div>


<?php require "../include/footer.php" ?>