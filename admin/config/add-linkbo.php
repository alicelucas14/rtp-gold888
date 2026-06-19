<?php 
require '../assets/config-data.php';

$daftarbo = mysqli_real_escape_string($data, $_REQUEST['daftarlink']);
$loginbo = mysqli_real_escape_string($data, $_REQUEST['loginlink']);

$updatestatus = "UPDATE web_setting SET link_daftarbo = '$daftarbo', link_masukbo = '$loginbo'";
$bindstats = mysqli_query($data, $updatestatus);

if($bindstats) {
    echo '
    <script>
        alert("Link Bo add Successfully!!");
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