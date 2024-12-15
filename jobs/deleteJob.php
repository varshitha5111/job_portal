<?php
require "../include/checkSession.php";
require "../include/config.php";
if (isset($_SESSION['userId'])) {
    if (isset($_GET['deleteJobId'])) {
        $deleteJobId = $_GET['deleteJobId'];
        $delete = $conn->prepare("delete from jobs where job_id=?");
        $delete->execute(array(
            $deleteJobId
        ));
        echo '<script>
            window.location.href = "../mainPage/index.php";
        </script>';
    }
}
