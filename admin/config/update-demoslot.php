<?php 
require '../assets/config-data.php';

$newlink = mysqli_real_escape_string($data, $_REQUEST['newlink']);
$prov = mysqli_real_escape_string($data, $_REQUEST['hprov']);
$idgame = mysqli_real_escape_string($data, $_GET['row']);

$updatestatus = "UPDATE demo_games SET demo_gamelink = '$newlink' WHERE id = '$idgame'";
$bindstats = mysqli_query($data, $updatestatus);

if($bindstats) {
    echo '
    <script>
        alert("Link Demo Successfully Change!!");
        window.location.href = "../dashboard.php?hal=gameimg&game='.$prov.'";
    </script>
    ';

} else {

    echo '
    <script>
        alert("ERROR on Change!!");
        window.location.href = "../dashboard.php?hal=gameimg&game='.$prov.'";
    </script>
    ';

}

?>