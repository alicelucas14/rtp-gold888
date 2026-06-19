<?php 
require '../assets/config-data.php';

$botxt = mysqli_real_escape_string($data, $_REQUEST['bgweb']);

$updatestatus = "UPDATE web_setting SET bg_rtp = '$botxt'";
$bindstats = mysqli_query($data, $updatestatus);

if($bindstats) {
    echo '
    <script>
        alert("Background Successfully Change!!");
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