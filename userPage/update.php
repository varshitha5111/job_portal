<?php require "../include/nav.php";  ?>

<?php
$successPass = 0;
$wrngConfirmPass = 0;
$wrngOldPass = 0;
$successProfile = 0;
$emptyField = 0;
// sessions
if (!isset($_SESSION['errors'])) {
    echo "hello";
    $_SESSION['errors'] = 0;
    $_SESSION['errormsg'] = '';
    $_SESSION['pass'] = 0;
    $_SESSION['profile'] = 0;
}

// session userid
if (!isset($_SESSION['userId'])) {
    header("location:../mainPage/index.php");
} else

    // update profile
    if (isset($_GET['updateProfile'])) {
        $userId = $_GET['updateProfile'];
        if ($userId != $_SESSION['userId']) { ?>
        <script>
            window.location.href = "../mainPage/index.php";
        </script>
<?php }
        $name = $_SESSION['username'];
        $email = $_SESSION['email'];
        $getUser = $conn->prepare("select image,cv,about,skilled_at from users where user_id=?");
        $getUser->execute(array($userId));
        while ($getUserDetails = $getUser->fetch(PDO::FETCH_ASSOC)) {
            $about = $getUserDetails['about'];
            $avatar = $getUserDetails['image'];
            $resume = $getUserDetails['cv'];
            $role = $getUserDetails['skilled_at'];
        }
    } else
        // post update submit profile
        if (isset($_POST['updateSubmit'])) {
            $cv = '';
            $skilled_at = '';
            $name = $_POST['name'];
            $bio = $_POST['about'];
            $email = $_POST['email'];
            $oldImg = $_POST['hiddenImage'];
            if ($_SESSION['type'] == 'worker') {
                $oldCv = $_POST['hiddencv'];
                $skilled_at = $_POST['skilled_at'];
                $cv = $_FILES['cv']['name'];
                if (empty($cv)) {
                    $cv = $oldCv;
                }
                $cv_temp_name = $_FILES['cv']['tmp_name'];
                move_uploaded_file("$cv_temp_name", "C:/xampp/htdocs/varshithaPhp/jobportal/images/$cv");
            }
            $image = $_FILES['image']['name'];
            if (empty($image)) {
                $image = $oldImg;
            }
            $image_temp_name = $_FILES['image']['tmp_name'];
            move_uploaded_file("$image_temp_name", "C:/xampp/htdocs/varshithaPhp/jobportal/images/$image");

            if (empty($name) || empty($email)) {
                $_SESSION['errors'] = 1;
                $_SESSION['errormsg'] = "<div class='alert alert-danger alert-dismissible fade show'>Please Fill The Complete Form
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>";
                $_SESSION['profile'] = 1;
                $_SESSION['pass'] = 0;
            } else {
                $_SESSION['errors'] = 0;
                $_SESSION['errormsg'] = '';
                $_SESSION['profile'] = 0;
                $_SESSION['pass'] = 0;
                $update = $conn->prepare("update  users set name=?,email=?,image=?,cv=?,skilled_at=?,about=? where user_id=?");
                $update->execute(array(
                    $name,
                    $email,
                    $image,
                    $cv,
                    $skilled_at,
                    $bio,
                    $_SESSION['userId']
                ));
                $_SESSION['username'] = $name;
                $_SESSION['email'] = $email;
                $_SESSION['cv'] = $cv;
                $successProfile = 1;
            }
            echo '<script>window.location.href="../userPage/update.php?updateProfile=' . $_SESSION['userId'] . '"</script>';
        } else if (isset($_POST['updatePassSubmit'])) {
            if (empty($_POST['oldPass']) || empty($_POST['newPass']) || empty($_POST['confirmPass'])) {
                $emptyField = 1;
                $_SESSION['errors'] = 1;
                $_SESSION['errormsg'] = "<div class='my-1 alert alert-danger alert-dismissible fade show'>Please fill all fields
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div><hr>";
                $_SESSION['pass'] = 1;
                $_SESSION['profile'] = 0;
            } else {

                $getUserPassword = $conn->prepare('select password from users where user_id=?');
                $getUserPassword->execute(array($_SESSION['userId']));
                while ($passwordDetails = $getUserPassword->fetch(PDO::FETCH_ASSOC)) {
                    $currentPass = $passwordDetails['password'];
                }
                if (!empty($_POST['oldPass'])) {
                    if (password_verify($_POST['oldPass'], $currentPass)) {
                        if ($_POST['newPass'] == $_POST['confirmPass']) {
                            $_SESSION['errors'] = 0;
                            $_SESSION['errormsg'] = '';
                            $_SESSION['pass'] = 0;
                            $_SESSION['profile'] = 0;
                            $updatePass = $conn->prepare('update users set password=? where user_id=?');
                            $updatePass->execute(array(
                                password_hash($_POST['newPass'], PASSWORD_DEFAULT),
                                $_SESSION['userId']
                            ));
                            $successPass = 1;
                        } else {
                            $_SESSION['errors'] = 1;
                            $_SESSION['errormsg'] = "<div class='my-1 alert alert-danger alert-dismissible fade show'>Wrong Confirm Password
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div><hr>";
                            $_SESSION['pass'] = 1;
                            $_SESSION['profile'] = 0;

                            $wrngConfirmPass = 1;
                        }
                    } else {
                        $_SESSION['errors'] = 1;
                        $_SESSION['errormsg'] = "<div class='my-1 alert alert-danger alert-dismissible fade show'>Wrong Password
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div><hr>";
                        $_SESSION['pass'] = 1;
                        $_SESSION['profile'] = 0;
                        $wrngOldPass = 1;
                    }
                }
            }
            echo '<script>window.location.href="../userPage/update.php?updateProfile=' . $_SESSION['userId'] . '"</script>';
        } else {
            echo '<script>window.location.href="../mainPage/index.php"</script>';
        }


?>





<div class="d-flex" style="height: 150px;">
    <div class="vr"></div>
</div>
<div class="container lead fw-bolder" style="color: white;">
    <h2>Update Profile</h2>
    <small><a href="../mainPage/index.php" id="link1" style="color:greenyellow">Home/</a><a href="../userPage/update.php?updateProfile=<?php echo $_SESSION['userId'] ?>" id="link2" style="color: white;">Update Profile</a></small>
</div>
<div class="d-flex" style="height: 100px;">
    <div class="vr"></div>
</div>
<div class="p-20" style="background-color: white; width: 100%;" ?>

    <div class="my-5 container">
        <!-- Stack the columns on mobile by making one full-width and the other half-width -->
        <div class="container-sm-7">
            <div class="row">
                <div class="col-7">
                    <div class="my-5 card">
                        <div class="card-header" id="button">
                            <h5>Update Profile</h5>
                        </div>
                        <?php if ($successProfile == 1) {
                            echo "<div class='my-1 alert alert-success alert-dismissible fade show'>Successfully Profile Update! 
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        </div><hr>";
                        } else if ($_SESSION['profile'] == 1 && $_SESSION['errors'] == 1) {
                            echo $_SESSION['errormsg'];
                        }
                        ?>
                        <div class="card-body">
                            <form method="POST" action="update.php" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Name</label>
                                    <input type="text" class="form-control" name="name" id="exampleFormControlInput1" placeholder="Enter Your Name" value="<?php echo $_SESSION['username'] ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Email address</label>
                                    <input type="email" class="form-control" name="email" id="exampleFormControlInput1" placeholder="Enter Your Email  Address" value="<?php echo $_SESSION['email'] ?>">
                                </div>
                                <?php if (isset($_SESSION['userId']) && $_SESSION['type'] === 'worker') { ?>
                                    <div class="mb-3">
                                        <label for="exampleFormControlInput1" class="form-label">Skilled At</label>
                                        <input type="skilled_at" class="form-control" name="skilled_at" id="exampleFormControlInput1" placeholder="Enter your role" value="<?php echo $role ?>">
                                    </div>
                                <?php } ?>
                                <div class="mb-3">
                                    <label for="post-image">Avatar</label><br>
                                    <img src="../images/<?php echo $avatar ?>" width="50">
                                    <input name="image" type="file" class="form-control-file" id="image">
                                    <input type="hidden" value="<?php echo $avatar ?>" name="hiddenImage">
                                </div>
                                <?php if (isset($_SESSION['userId']) && $_SESSION['type'] == 'worker') { ?>
                                    <div class="mb-3">
                                        <label for="post-image">Resume</label><br>
                                        <input name="hiddencv" type="text" value="<?php echo $resume ?>">
                                        <input name="cv" type="file" class="form-control-file" id="cv">
                                    </div>
                                <?php } ?>
                                <div class="mb-3">
                                    <label for="inputPassword4" class="form-label">About Me</label><br>
                                    <textarea class="summernote" name="about"><?php echo $about ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <button type="submit" name="updateSubmit" class="btn" id="button">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- update password -->
                <div class="col-5">
                    <div class="my-5 card">
                        <div class="card-header" id="button">
                            <h5>Update Password</h5>
                        </div>
                        <div class="card-body">
                            <?php
                            if ($successPass == 1) {
                                echo "<div class='my-1 alert alert-success alert-dismissible fade show'>Successfully Password Updated!
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div><hr>";
                            } else {
                                if (isset($_SESSION['errors'])) {
                                    if ($_SESSION['pass'] == 1 && $_SESSION['errors'] == 1) {
                                        echo $_SESSION['errormsg'];
                                    }
                                }
                            }
                            //             } else if ($wrngConfirmPass == 1) {
                            //                 echo "<div class='my-1 alert alert-danger alert-dismissible fade show'>Wrong Confirm Password
                            // <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                            // </div><hr>";
                            //             } else if ($wrngOldPass == 1) {
                            //                 echo "<div class='my-1 alert alert-danger alert-dismissible fade show'>Wrong Current Password
                            // <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                            // </div><hr>";
                            //             } else if ($emptyField == 1) {
                            //                 echo "<div class='my-1 alert alert-danger alert-dismissible fade show'>Please fill all fields
                            // <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                            // </div><hr>";
                            //             }
                            ?>
                            <form method="post" action="update.php" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Current Password</label>
                                    <input type="text" class="form-control" name="oldPass" id="exampleFormControlInput1" placeholder="Enter Current Password">
                                </div>
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">New Password</label>
                                    <input type="password" class="form-control" name="newPass" id="exampleFormControlInput1" placeholder="Enter New Password">
                                </div>
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Confirm Password</label>
                                    <input type="text" class="form-control" name="confirmPass" id="exampleFormControlInput1" placeholder="Enter Confirm Password">
                                </div>

                                <div class="mb-3">
                                    <button type="submit" name="updatePassSubmit" class="btn" id="button">Submit</button>
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