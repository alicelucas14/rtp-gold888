<?php 
require '../assets/config-data.php';

$imglink = mysqli_real_escape_string($data, $_REQUEST['addsliders']);

$updatestatus = "INSERT INTO img_sliders (sliders)  VALUES ('$imglink')";
$bindstats = mysqli_query($data, $updatestatus);

if($bindstats) {
    echo '
    <script>
        alert("Image Slider add Successfully!!");
        window.location.href = "../dashboard.php?hal=sliders";
    </script>
    ';

} else {

    echo '
    <script>
        alert("ERROR on Adding!!");
        window.location.href = "../dashboard.php?hal=sliders";
    </script>
    ';

}

?>