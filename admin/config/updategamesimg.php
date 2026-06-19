<?php 
require '../assets/config-data.php';

$newimg = mysqli_real_escape_string($data, $_REQUEST['update-gamesimg']);
$newlink = mysqli_real_escape_string($data, $_REQUEST['update-linkgame']);
$idgame = mysqli_real_escape_string($data, $_GET['row']);

$updatestatus = "UPDATE img_games SET games_img = '$newimg', game_link = '$newlink' WHERE id = '$idgame'";
$bindstats = mysqli_query($data, $updatestatus);

if($bindstats) {
    echo '
    <script>
        alert("Game Successfully Change!!");
        window.location.href = "../dashboard.php?hal=gameimg";
    </script>
    ';

} else {

    echo '
    <script>
        alert("ERROR on Change!!");
        window.location.href = "../dashboard.php?hal=gameimg";
    </script>
    ';

}

?>