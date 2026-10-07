<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Registriere dich!</title>
    <link rel="shortcut icon" href="../images/Logo/favicon.ico" type="image/x-icon"/>
    <link rel="stylesheet" href="../styles/register.css">
  </head>
  <body>
    <?php
      // Verbindung mit Datenbank
      include "connect.php";

      // Wenn button geklickt wird
      if (isset($_POST["BUTTON_create_confirm"])){
      // alle Daten des Formulars abrufen
      $email = $_POST["DATA_email"];
      $firstname = $_POST["DATA_firstname"];
      $name = $_POST["DATA_lastname"];
      $passwd = $_POST["DATA_password"];

      // E-Mail Adresse überprüfen
      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        //wenn kein richtiger email ist dann kommt ein alert box
        echo ("<script LANGUAGE='JavaScript'>
                  window.alert('Dies ist keine E-Mail, bitte verwenden Sie das E-Mail-Format.\\nBeispiel: myemail@DataCorp.lu');
                  window.location.href='register.php';
                </script>");

        exit(); // das Skript beenden
      }
      //hier wird das passwort den der user eingetippt hat gehashed
      $passhash = password_hash($passwd, PASSWORD_DEFAULT);

      // query für daten ins datenbank zu speichern
      $create_query = "INSERT INTO benutzer (Email, Vorname, Name, Passwort, IDBenutzergruppe) VALUES
      (\"$email\", \"$firstname\", \"$name\", \"$passhash\", 1);";

      // den query durchführen:
      $result = $mysqli->query($create_query);



      // wenn der query funktioniert dann zeige den alert und und leitet den Benutzer auf die Anmeldeseite weiter
      if($result){
      echo ("<script LANGUAGE='JavaScript'>
              window.alert('Ihr Konto wurde erstellt. Bitte anmelden');
              window.location.href='login.php';
            </script>");
      }
      //wenn den query falsch ist dann zeigt auch ein alert
      else{
      echo ("<script LANGUAGE='JavaScript'>
              window.alert('Fehler beim Erstellen Ihres Kontos');
            </script>");
          }
      }

      //Erstellen eines Registrierungsformulars
      echo '<div class="formular">';
      echo '<img class="user" src="../images/Logo/logo_banner2.png" alt="User">';
      echo "<h1>Erstellen Sie ein neues Konto</h1>";
      echo '<form action="' . $_SERVER['SCRIPT_NAME'] . '" method="post">' . "\n";

      echo '<div class="register_values">';
      echo '<input type="text" name="DATA_email" value="" required/>';
      echo "<span></span>";
      echo '<label>Email</label>';
      echo '</div>';

      echo '<div class="register_values">';
      echo '<input type="text" name="DATA_firstname" value="" required/>';
      echo "<span></span>";
      echo '<label>Vorname</label>';
      echo '</div>';

      echo '<div class="register_values">';
      echo '<input type="text" name="DATA_lastname" value="" required/>';
      echo "<span></span>";
      echo '<label>Nachname</label>';
      echo '</div>';

      echo '<div class="register_values">';
      echo '<input type="password" name="DATA_password" value="" required/>';
      echo "<span></span>";
      echo '<label>Passwort</label>';
      echo '</div>';
      // den button anzeigen:
      echo '<input class="smallButton" type="submit" name="BUTTON_create_confirm" value="Konto erstellen" /> <br>';

      //eine nachricht, wenn der user schon einen account hat 
      echo '<p class="center"><br/>
        Sie haben bereits ein Konto? <a href="login.php">Hier anmelden</a>
      </p>';
    echo "</form>";
    echo "</div>";

    ?>
  </body>
</html>
