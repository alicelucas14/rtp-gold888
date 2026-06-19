<?php 
require '../assets/config-data.php';

$newlink = mysqli_real_escape_string($data, $_REQUEST['update-promoimg']);
$idimg = mysqli_real_escape_string($data, $_GET['row']);

$updatestatus = "UPDATE img_promo SET promo_img = '$newlink' WHERE id = '$idimg'";
$bindstats = mysqli_query($data, $updatestatus);

if($bindstats) {
    echo '
    <script>
        alert("Image Promotion Successfully Change!!");
        window.location.href = "../dashboard.php?hal=promoimg";
    </script>
    ';

} else {

    echo '
    <script>
        alert("ERROR on Change!!");
        window.location.href = "../dashboard.php?hal=promoimg";
    </script>
    ';

}

?>