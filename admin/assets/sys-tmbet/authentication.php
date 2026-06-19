<?php
session_start();
require_once '../config-data.php';

if($stmt  = $data->prepare('SELECT id, password FROM lomba_credential WHERE nama_pengguna = ?')) {
    $stmt->bind_param('s', $_POST['username']);
    $stmt->execute();
    $stmt->store_result();

    if($stmt->num_rows > 0) {
        $stmt->bind_result($id, $password);
        $stmt->fetch();

        if(password_verify($_POST['password'], $password)) {
            session_regenerate_id();
            $_SESSION['sukseslogin'] = TRUE;
            $_SESSION['nama_pengguna'] = $_POST['username'];
            $_SESSION['id'] = $id;
            header("Location: ../../dashboard.php");
            exit;

        } else {
            echo '<script>alert("Incorrect Password!!");</script> ';
            echo '<script>
                    window.location.href= "../../index.php";
                </script>';
        }
    } else {
        echo '<script>alert("Wrong Username Please check back!!");</script> ';
        echo '<script>
                window.location.href= "../../index.php";
            </script>';
    }

    $stmt ->close();
}

?>