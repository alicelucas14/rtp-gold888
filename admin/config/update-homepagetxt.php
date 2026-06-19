<?php 
require '../assets/config-data.php';

$hometxt = mysqli_real_escape_string($data, $_REQUEST['homepage-text']);

$updatestatus = "UPDATE change_text SET homepage_text = '$hometxt' ";
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