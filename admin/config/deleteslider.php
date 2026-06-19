<?php 
require '../assets/config-data.php';

$hapusid = mysqli_real_escape_string($data, $_GET['row']);

$hapushalaman = "DELETE FROM img_sliders WHERE id='$hapusid'";
$queryhapus = mysqli_query($data, $hapushalaman);

if($queryhapus) {
    
    echo '
    <script>
        alert("Slider Successfully Delete!!");
        window.location.href = "../dashboard.php?hal=sliders";
    </script>
    ';

} else {

    echo '
    <script>
        alert("ERROR on Change!!");
        window.location.href = "../dashboard.php?hal=sliders";
    </script>
    ';

}

?>