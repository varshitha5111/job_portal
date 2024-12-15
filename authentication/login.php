<?php require "../include/nav.php"; ?>

<?php
$wrngPassword = 0;
$emptyForm = 0;
$wrngEmail = 0;
if (isset($_SESSION['userId'])) {
    header("location:../mainPage/index.php");
}
if (isset($_POST['loginSubmit'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $emptyForm = 1;
    } else {
        $login = $conn->prepare("select userType,email,password,name,user_id,cv from users where email=?");
        $login->execute(array(
            $email
        ));
        $data = $login->fetch(PDO::FETCH_ASSOC);


        if ($login->rowCount() > 0) {
            if (password_verify($password, $data['password'])) {
                $_SESSION['username'] = $data['name'];
                $_SESSION['userId'] = $data['user_id'];
                $_SESSION['email'] = $data['email'];
                $_SESSION['type'] = $data['userType'];
                $_SESSION['cv'] = $data['cv'];
                header("location:../mainPage/index.php");
            } else {
                $wrngPassword = 1;
            }
        } else {
            $wrngEmail = 1;
        }
    }
}


?>





<div class="d-flex" style="height: 150px;">
    <div class="vr"></div>
</div>
<div class="container lead fw-bolder" style="color: white;">
    <h2>Log In</h2>
    <small><a href="../mainPage/index.php" id="link1" style="color:greenyellow">Home/</a><a href="../authentication/register.php" id="link2" style="color: white;">Log In</a></small>
</div>
<div class="d-flex" style="height: 100px;">
    <div class="vr"></div>
</div>
<div class="p-10" style="background-color: white; width: 100%;" ?>

    <div class="container">
        <div class="row ">
            <div class="col-8">
                <div class=" my-4 card">
                    <div class="card-body">
                        <?php if ($wrngEmail == 1) {
                            echo "<div class='alert alert-danger alert-dismissible fade show'>Wrong Email
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div><hr>";
                        } else if ($wrngPassword == 1) {
                            echo "<div class='alert alert-danger alert-dismissible fade show'>Wrong Password 
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div><hr>";
                        } else if ($emptyForm == 1) {
                            echo "<div class='alert alert-danger alert-dismissible fade show'>Please Fill The Complete Form
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div><hr>";
                        }
                        ?>
                        <form method="POST" action="login.php">
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Email address</label>
                                <input name="email" type="email" class="form-control" id="exampleFormControlInput1" placeholder="Enter Your Email  Address">
                            </div>

                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Password</label>
                                <input name="password" type="password" class="form-control" id="exampleFormControlInput1" placeholder="Enter A Password">
                            </div>

                            <div class="mb-3">
                                <button type="submit" class="btn btn-lg" id="button" name="loginSubmit" value="submit">Log In</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?php require "../include/footer.php" ?>