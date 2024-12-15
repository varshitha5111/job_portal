<?php require "../include/config.php";
require "../include/checkSession.php";

if (isset($_SESSION['userId'])) { ?>

    <div class="text-center justify-content-center">
        <?php
        $get = $conn->prepare("SELECT
        user_id AS user_id,
        image AS image,
        about AS about,
        skilled_at AS role
    FROM
        users 
    WHERE user_id=?");
        $get->execute(array(
            $_SESSION['userId']
        ));
        if ($get->rowCount() > 0) {
            while ($getDetails = $get->fetch(PDO::FETCH_ASSOC)) {
                $public_user_id = $getDetails['user_id'];
                $image = $getDetails['image'];
                $about = $getDetails['about'];
                $role = $getDetails['role'];
            }

        ?>
            <img src="../images/<?php echo $image ?>" class="rounded mx-auto my-1 d-block" alt="" style="width: 100px;">
            <h4 class="my-1" style="text-transform: uppercase;"><?php echo $_SESSION['username']; ?></h4>
            <h4 class="my-1" style="text-transform: uppercase;"><?php echo $role ?></h4>
            <small>
                <?php echo $about; ?>
            </small>
    </div>
<?php } else {
            echo $_SESSION['username'];
            echo '<br>';
            echo '<small>Profile Yet To Be Updated <a href="update.php" class="btn btn-link" style="text-decoration:none;">Update Profile</a></small>';
        }
    }
?>

