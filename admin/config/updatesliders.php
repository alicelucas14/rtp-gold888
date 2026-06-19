<?php 
require '../assets/config-data.php';

$newlink = mysqli_real_escape_string($data, $_REQUEST['update-img']);
$idimg = mysqli_real_escape_string($data, $_GET['row']);

$updatestatus = "UPDATE img_sliders SET sliders = '$newlink' WHERE id = '$idimg'";
$bindstats = mysqli_query($data, $updatestatus);

if($bindstats) {
    echo '
    <script>
        alert("Slider Successfully Change!!");
        window.location.href = "../dashboard.php?hal=sliders";
    </script>
    ';

} else {

    echo '
    <script>
        alert("ERROR on Change!!");
        window.location.href = "../dashboard.php?hal=sliders";
    </script>
    ';

}

?>