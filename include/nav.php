<?php require "header.php";
require "../include/checkSession.php"; ?>

<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <h4 style="color: white;">JOBPORTAL</h4>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon" style="color: white; "></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
            <ul class="navbar-nav nav-pills mt-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="../mainPage/index.php" style="color:white;">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../include/contact.php" style="color: white;">Contact</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../include/about.php" style="color: white;">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../workerAndCompanies/showingWorkers.php" style="color: white;">Workers</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../workerAndCompanies/showingCompany.php" style="color: white;">Companies</a>
                </li>
                <?php if (isset($_SESSION['userId']) && $_SESSION['type'] == 'Company') { ?>
                    <li class="nav-item my-1 mx-1">
                        <a class="btn btn-outline-light" href="../jobs/postJob.php?postJobUserId=<?php echo $_SESSION['userId'] ?>">+ Post Job</a>
                    </li>
                <?php } ?>
                <?php if (!isset($_SESSION['userId'])) { ?>

                    <li class="nav-item my-1 mx-1">
                        <a id="button" class="btn btn-rounded" href="../authentication/register.php">Register</a>
                    </li>
                    <li class="nav-item my-1 mx-1">
                        <a id="button" class="btn btn-rounded" href="../authentication/login.php">Login</a>
                    </li>
                <?php } else { ?>
                    <li class="nav-item dropdown mx-1">
                        <a class="nav-link dropdown-toggle" href="#" role="button" style="color: white;" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php echo $_SESSION['email']; ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="../authentication/logout.php">Log Out</a></li>
                            <?php if (isset($_SESSION['type']) && $_SESSION['type'] == 'worker') { ?>
                                <li><a class="dropdown-item" href="../jobs/savedJob.php?user_id=<?php echo $_SESSION['userId']; ?>">Saved Jobs</a></li>
                                <li><a class="dropdown-item" href="../jobs/applyJob.php?user_id=<?php echo $_SESSION['userId']; ?>">Applied Jobs</a></li>
                            <?php } else if (isset($_SESSION['type']) && $_SESSION['type'] == 'Company') { ?>
                                <li><a class="dropdown-item" href="../userPage/companyJobs.php?publicCompanyUserId=<?php echo $_SESSION['userId']; ?>">Jobs Posted</a></li>
                                <form method="POST" action="../jobs/allJobApplication.php">
                                    <input type="hidden" value="<?php echo $_SESSION['userId']; ?>" name="company_user_id" />
                                    <li><input type="submit" value="  Job Application" name="submit" style="background-color: white; border: white;"></li>
                                </form>
                            <?php } ?>
                            <li><a class="dropdown-item" href="../userPage/update.php?updateProfile=<?php echo $_SESSION['userId'] ?>">Update Profile</a></li>
                        </ul>
                    </li>
                    <li>
                        <!-- Button trigger modal -->
                        <button id="button" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                            Public Profile
                        </button>
                        <!-- Modal -->
                        <!-- Vertically centered modal -->
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-body">
                                            <?php require "../userPage/public.php"; ?>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- company type -->
                        <?php //if($_SESSION['type']=='Company'){ 
                        ?>
                        <!-- <a class="btn" id="button" href="../userPage/public.php?publicCompanyUserId=<?php //echo $_SESSION['userId']; 
                                                                                                            ?>" >Public Profile</a> -->
                        <?php //} 
                        ?>
                    </li>
                <?php   } ?>
            </ul>
        </div>
    </div>
</nav>