<?php 
require '../assets/config-data.php';

$seo = mysqli_real_escape_string($data, $_REQUEST['seo-text']);
$seoid = mysqli_real_escape_string($data, $_GET['seo']);

$updatestatus = "UPDATE web_setting SET content_seo = '$seo' WHERE id = '$seoid'";
$bindstats = mysqli_query($data, $updatestatus);

if($bindstats) {
    echo '
    <script>
        alert("Seo Successfully Change!!");
        window.location.href = "../dashboard.php?hal=setting";
    </script>
    ';

} else {

    echo '
    <script>
        alert("ERROR on Change!!");
        window.location.href = "../dashboard.php?hal=setting";
    </script>
    ';

}

?>