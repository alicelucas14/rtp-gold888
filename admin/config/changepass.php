<?php
require '../assets/config-data.php';
$newpass = mysqli_real_escape_string($data, $_REQUEST['changepass']);
$pass = password_hash($newpass, PASSWORD_DEFAULT);
$user = mysqli_real_escape_string($data, $_REQUEST['userchange']);


$updatestatus = "UPDATE lomba_credential SET password = '$pass' WHERE id = '$user'";
$bindstats = mysqli_query($data, $updatestatus);

if($bindstats) {
    echo '
    <script>
        alert("Password Successfully Change!!");
        window.location.href = "../dashboard.php";
    </script>
    ';

} else {

    echo '
    <script>
        alert("ERROR on Change!!");
        window.location.href = "../dashboard.php";
    </script>
    ';

}



?>