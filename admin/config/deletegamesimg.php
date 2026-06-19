<?php 
require '../assets/config-data.php';

$hapusid = mysqli_real_escape_string($data, $_GET['row']);

$hapushalaman = "DELETE FROM img_games WHERE id='$hapusid'";
$queryhapus = mysqli_query($data, $hapushalaman);

if($queryhapus) {
    
    echo '
    <script>
        alert("Game Successfully Delete!!");
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