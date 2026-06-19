<?php 
require '../assets/config-data.php';

$fooweb = mysqli_real_escape_string($data, $_REQUEST['footerweb']);

$updatestatus = "UPDATE change_text SET footer_web = '$fooweb'";
$bindstats = mysqli_query($data, $updatestatus);

if($bindstats) {
    echo '
    <script>
        alert("Footer Web add Successfully!!");
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