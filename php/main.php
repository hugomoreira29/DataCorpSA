<?php
session_start();
setcookie("email", true, time() + (60 * 1)); // 60 Sekunden ( 1 Minute) * 1 = 1 Minute
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Data Corp S.A</title>
    <link rel="shortcut icon" href="../images/Logo/favicon.ico" type="image/x-icon"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> <!-- Link, um ein Symbol in meinem Button-Profil zu haben -->
    <link rel="stylesheet" href="../styles/main.css">
  </head>
  <body>
    <header>
      <?php
      // Wenn Sie auf Abmelden Button klicken
      if (isset($_POST["BUTTON_logout"])){
        session_destroy(); //Die Session zerstören
        header('Location:login.php'); // leitet den user in die login page
      }

      //wenn button profile geklickt wird, dann wird der user in den profile.php geleitet
      if (isset($_POST["BUTTON_profile"])){
        header('Location:profile.php');
      }

      //header
        echo '<img class="logo" src="../images/Logo/logo_banner2.png" alt="Logo">';
          echo '<form action="' . $_SERVER['SCRIPT_NAME'] . '" method="post">' . "\n";
          //profile button und logout button mit dem logo
          echo '<div class="buttons">';
          echo '<button name="BUTTON_profile"><i class="fa fa-fw fa-user"></i>Profil</button>';
          echo '<button class="logout_Button" name="BUTTON_logout"><i class="fa fa-sign-out" aria-hidden="true"></i></button>';
          echo "</div>";
      echo "</form>";
      ?>
    </header>
    <?php
    // Verbindung mit Datenbank
		include "connect.php";

    //wenn eine session email existiert dann...
		if (isset($_SESSION['email'])){
			include "rooms/rooms.php"; // Tabelle mit Zimmern zur Reservierung einbeziehen
		}else{
      //wenn nicht dann zeige alert und leitet den user in die login page
      echo ("<script LANGUAGE='JavaScript'>
              window.alert('Du bist nicht eingeloggt. Bitte logge dich ein oder registriere dich!');
              window.location.href='login.php';
            </script>");
				}
    ?>
    <footer>
      <?php
      //footer mit copyright
      echo "© 2022-2023 | Webseite wurde von Pereira Moreira Hugo gemacht | 1TPIF";
      ?>
    </footer>
  </body>
</html>
