<?php 
require '../assets/config-data.php';

$contacts = mysqli_real_escape_string($data, $_REQUEST['contact-text']);

$updatestatus = "UPDATE contact_kami SET isi_kontak = '$contacts'";
$bindstats = mysqli_query($data, $updatestatus);

if($bindstats) {
    echo '
    <script>
        alert("Contacts add Successfully!!");
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