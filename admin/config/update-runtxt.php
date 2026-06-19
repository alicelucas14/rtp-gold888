<?php 
require '../assets/config-data.php';

$runningtext = mysqli_real_escape_string($data, $_REQUEST['runningtxt']);
$speed = mysqli_real_escape_string($data, $_REQUEST['speedtxt']);

$updatestatus = "UPDATE change_text SET running_text = '$runningtext', scroll_amount = '$speed'";
$bindstats = mysqli_query($data, $updatestatus);

if($bindstats) {
    echo '
    <script>
        alert("Running Text Successfully Change!!");
        window.location.href = "../dashboard.php?hal=changetext";
    </script>
    ';

} else {

    echo '
    <script>
        alert("ERROR on Change!!");
        window.location.href = "../dashboard.php?hal=changetext";
    </script>
    ';

}

?>