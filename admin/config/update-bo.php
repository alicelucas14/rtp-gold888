<?php 
require '../assets/config-data.php';

$botxt = mysqli_real_escape_string($data, $_REQUEST['bo-page-text']);

$updatestatus = "UPDATE change_text SET list_botext = '$botxt'";
$bindstats = mysqli_query($data, $updatestatus);

if($bindstats) {
    echo '
    <script>
        alert("BO Slot Text Successfully Change!!");
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