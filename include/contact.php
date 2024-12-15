<?php

include("nav.php");
?>
<?php
$success = 0;
if (isset($_POST['send'])) {
    if (!isset($_SESSION['userId'])) {
        header("location:../mainPage/index.php");
    }
    $name = $_POST['name'];
    $email = $_POST['email'];
    $msg = $_POST['msg'];
    $insertHelp = $conn->prepare("insert into help(user_id,name,email,message)  values(?,?,?,?)");
    $insertHelp->execute(array(
        $_SESSION['userId'],
        $name,
        $email,
        $msg
    ));
    $success = 1;
}

?>

<div class="d-flex" style="height: 150px;">
    <div class="vr"></div>
</div>
<div class="container lead fw-bolder" style="color: white;">
    <h2>Contact Us</h2>
    <small><a href="../mainPage/index.php" id="link1" style="color:greenyellow">Home/</a><a href="" id="link2" style="color: white;">Contact</a></small>
</div>
<div class="d-flex" style="height: 100px;">
    <div class="vr"></div>
</div>

<div class="jumbotron">
    <div class="container">
        <h1 class="display-4">Contact Us</h1>
        <p class="lead">We're here to help you with any questions or concerns</p>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-6">
            <h2 style="color: white;">Get in Touch</h2>
            <p style="color: white;">If you're having trouble using our website or have any questions, please don't hesitate to reach out to us using the contact information below:</p>
            <ul class="list-unstyled" style="color: white;">
                <li><strong>Email:</strong> support@jobportal.com</li>
                <li><strong>Phone:</strong> +91 1234567890 </li>
                <li><strong>Address:</strong> Bangalore Institute of Technology, Bangalore</li>
            </ul>
        </div>
        <div class="col-md-6">
            <h2 style="color: white;">Contact Form</h2>
            <?php if ($success == 1) {
                echo "<div class='alert alert-success alert-dismissible fade show'>Successfuly Help Sent!
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>";
            }
            ?>
            <form method="post" action="contact.php" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="name" class="form-label" style="color: white;">Name</label>
                    <input type="text" class="form-control" name="name" id="name" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label" style="color: white;">Email address</label>
                    <input type="email" class="form-control" name="email" id="email" required>
                </div>
                <div class="mb-3">
                    <label for="message" class="form-label" style="color: white;">Message</label>
                    <textarea class="form-control" id="message" name="msg" rows="3" required></textarea>
                </div>
                <button type="submit" name="send" class="btn btn-custom-green" style="color: black;">Send Message</button>
            </form>
        </div>
    </div>
</div>

<?php include("footer.php"); ?>