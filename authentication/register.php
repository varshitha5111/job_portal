<?php require "../include/nav.php";  ?>

<?php
$emptyForm = 0;
$emailRegistered = 0;
$wrngConfirmPass = 0;
if (isset($_SESSION['userId'])) {
    echo '<script> window.location.href="../mainPage/index.php" </script>';
} else if (isset($_POST['registerSubmit'])) {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $userType = $_POST['userType'];

    if (empty($name) || empty($email) || empty($password) || empty($confirmPassword)  || empty($userType)) {
        $emptyForm = 1;
    } else {
        $checkPresentOrNot = $conn->prepare("select count(user_id) as count from users where email=?");
        $checkPresentOrNot->execute(array($email));
        $get = $checkPresentOrNot->fetch(PDO::FETCH_ASSOC);
        if ($get['count'] == 1) {
            $emailRegistered = 1;
        } else {
            if ($password != $confirmPassword) {
                $wrngConfirmPass = 1;
            } else {
                $password = password_hash($password, PASSWORD_DEFAULT);

                $insert = $conn->prepare("insert into users(name,email,password,userType) values (?,?,?,?)");
                $insert->execute(array(
                    $name,
                    $email,
                    $password,
                    $userType
                ));
                header("location:login.php");
            }
        }
    }
}

?>

<div class="d-flex" style="height: 150px;">
    <div class="vr"></div>
</div>
<div class="container lead fw-bolder" style="color: white;">
    <h2>Register</h2>
    <small><a href="../mainPage/index.php" id="link1" style="color:greenyellow">Home/</a><a href="../authentication/register.php" id="link2" style="color: white;">Register</a></small>
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
                        <div class=" my-1 card-body">
                            <?php if ($wrngConfirmPass == 1) {
                                echo "<div class='my-1 alert alert-danger alert-dismissible fade show'>Wrong Confirm Password
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div><hr>";
                            } else if ($emailRegistered == 1) {
                                echo "<div class='my-1 alert alert-danger alert-dismissible fade show'>This Email Id Is Already Registered 
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div><hr>";
                            } else if ($emptyForm == 1) {
                                echo  "<div class='my-1 alert alert-danger alert-dismissible fade show'>Please Fill The Complete Form
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div><hr>";
                            }
                            ?>
                            <form method="POST" action="register.php" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Name</label>
                                    <input type="text" class="form-control" name="name" id="exampleFormControlInput1" placeholder="Enter Your Name">
                                </div>
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Email address</label>
                                    <input type="email" class="form-control" name="email" id="exampleFormControlInput1" placeholder="Enter Your Email  Address">
                                </div>
                                <div class="mb-3 form-group">
                                    <label>User Type</label>
                                    <select class="my-1 form-control" name="userType">
                                        <option>worker</option>
                                        <option>Company</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Password</label>
                                    <input type="password" class="form-control" name="password" id="exampleFormControlInput1" placeholder="Enter A Password">
                                </div>
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Confirm password</label>
                                    <input type="text" class="form-control" name="confirmPassword" id="exampleFormControlInput1" placeholder="Enter Password Again">
                                </div>
                                <div class="mb-3">
                                    <button type="submit" name="registerSubmit" class="btn" id="button">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require "../include/footer.php"; ?>