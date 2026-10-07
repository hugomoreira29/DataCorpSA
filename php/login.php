<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Login-Seite</title>
    <link rel="shortcut icon" href="../images/Logo/favicon.ico" type="image/x-icon"/>
    <link rel="stylesheet" href="../styles/login.css">
  </head>
  <body>
    <?php
    $passhash="";
      // Verbindung mit Datenbank
      include "connect.php";


    echo '<div class="formular">';
    echo '<img class="user" src="../images/Logo/logo_banner2.png" alt="User">';
    echo "<h1>Einloggen</h1>";
    //Formular wo den User seine Daten muss eingeben
    echo '<form action="' . $_SERVER['SCRIPT_NAME'] . '" method="post">' . "\n";
    echo '<div class="login_values">';
    echo '<input type="text" name="DATA_Email" value="" required/>';
    echo "<span></span>";
    echo '<label>EMail</label>';
    echo '</div>';

    echo '<div class="login_values">';
    echo '<input type="password" name="DATA_password" value="" required/>';
    echo "<span></span>";
    echo '<label>Passwort</label>';
    echo '</div>';

    // Button_confirm
    echo '<input class="smallButton" type="submit" name="BUTTON_confirm" value="Anmelden" /> <br>';

    echo '<p class="center"><br/>
      Haben Sie noch kein Konto? <a href="register.php">Hier registrieren!</a>
    </p>';

  echo "</form>";
  echo "</div>";

  // Wenn Button geklickt wird mache das...
  if (isset($_POST["BUTTON_confirm"])){
  $Email = $_POST["DATA_Email"];
  $password = $_POST["DATA_password"];

  //geht Passwort von dem Email den der User eingegeben hat von der datenbank suchen
  $query= "SELECT Passwort FROM benutzer WHERE Email = '$Email'";

  $result = mysqli_query($mysqli, $query);
  //wenn das query geht dann mache den passwort(hashed) von der datenbank in eine variabel
  if( $result)
    {
        while ($row = $result->fetch_assoc())
        {
               $passhash = $row["Passwort"];
        }
        // resultat freigeben
        $result->free();
    }
    // prüft, ob der Passwort, den der Benutzer eingegeben hat, mit dem in der Datenbank übereinstimmt
  if(password_verify($password,$passhash)){
    //wenn ja dann setze den $email den der user angegeben hat in der session name
    $_SESSION['email'] = $Email;

    $_SESSION['show_login'] = "0";
    echo $_SESSION['show_login'];

    // Und gehe auf "main.php"
    header('Location: main.php');
  }
  else{
    // Wenn es nicht stimmt dann kommt eine alert box
    echo ("<script LANGUAGE='JavaScript'>
      window.alert('Ungültige E-Mail oder Passwort');
        </script>");
  }
}

  // Verbindung mit der datenbank trennen
  $mysqli->close();
  ?>
  </body>
</html>
