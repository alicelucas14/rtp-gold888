<?php 
require '../assets/config-data.php';

$script = mysqli_real_escape_string($data, $_REQUEST['script-text2']);
$scriptid = mysqli_real_escape_string($data, $_GET['row']);

$updatestatus = "UPDATE web_setting SET content_script2 = '$script' WHERE id = '$scriptid'";
$bindstats = mysqli_query($data, $updatestatus);

if($bindstats) {
    echo '
    <script>
        alert("Script Successfully Change!!");
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