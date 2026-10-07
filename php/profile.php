<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Profil</title>
    <link rel="shortcut icon" href="../images/Logo/favicon.ico" type="image/x-icon"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"><!-- Link, um ein Symbol in meinem Button-Profil zu haben -->
    <link rel="stylesheet" href="../styles/profile.css">
  </head>
  <body>
    <header>
      <?php
      //Header von Page Image mit Link zur Startseite
      echo '<a href="main.php"><img class="logo" src="../images/Logo/logo_banner2.png" alt="Logo"></a><br>';
      ?>
    </header>
    <?php
      // Verbindung mit Datenbank
        include "connect.php";

        //setze die session in die variable
        $Email = $_SESSION['email'];

        //die Datei update_profile.php einbinden
        include "update_profile/update_profile.php";
    ?>
  </body>
</html>
