<?php 
require '../assets/config-data.php';

$promotxt = mysqli_real_escape_string($data, $_REQUEST['games-page-text']);

$updatestatus = "UPDATE change_text SET listgame_text = '$promotxt'";
$bindstats = mysqli_query($data, $updatestatus);

if($bindstats) {
    echo '
    <script>
        alert("Game Text Successfully Change!!");
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