<?php 
require '../assets/config-data.php';

$gameimg = mysqli_real_escape_string($data, $_REQUEST['logolink']);

$updatestatus = "UPDATE web_setting SET logo_rtp = '$gameimg'";
$bindstats = mysqli_query($data, $updatestatus);

if($bindstats) {
    echo '
    <script>
        alert("Logo Image add Successfully!!");
        window.location.href = "../dashboard.php";
    </script>
    ';

} else {

    echo '
    <script>
        alert("ERROR on Adding!!");
        window.location.href = "../dashboard.php";
    </script>
    ';

}

?>