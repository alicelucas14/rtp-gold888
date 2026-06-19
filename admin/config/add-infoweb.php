<?php 
require '../assets/config-data.php';

$infos = mysqli_real_escape_string($data, $_REQUEST['infoweb-text']);

$updatestatus = "UPDATE web_setting SET web_infos = '$infos'";
$bindstats = mysqli_query($data, $updatestatus);

if($bindstats) {
    echo '
    <script>
        alert("Info Web add Successfully!!");
        window.location.href = "../dashboard.php";
    </script>
    ';

} else {

    echo '
    <script>
        alert("ERROR on Adding Contacts!!");
        window.location.href = "../dashboard.php";
    </script>
    ';

}

?>