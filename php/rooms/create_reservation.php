<?php
session_start();
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<link rel="stylesheet" href="../../styles/formular_reservation.css">
<!-- jquery -->
<script>
$(document).ready(function() {
    $('#date').change(function() {  //wenn datum sich ändern,d.h wenn user ein datum wählt
        var date = $(this).val(); // den gewählten datum im formular in eine variabel setzen
        $("#hours").load("select_hours.php", {date: date});  //table #hours mit daten den im select_hours.php gemacht wurden
    });
});
</script>
<?php
  // Verbindung mit datenbank
    include "../connect.php";
    //setze leere variablen
    $email="";
    $datum="";
    $hours="";
    // query um alle informationen von der tabelle konferenzraum zu suchen
    $query  = "SELECT * FROM konferenzraum";

    //mache den query
    $result = $mysqli->query($query);

    // wenn query funktioniert, dann
    if( $result)
    {
      //setze die session in eine Variabel
      $Email = $_SESSION['email'];

      echo "<div class='reservation_formular'>";
      echo '<img class="user" src="../../images/Logo/logo_banner2.png" alt="User">';
      echo "<h1>Reservierung für Raum</h1>";
      //mache ein formulär um die reservierung zu machen
      echo '<form action="' . $_SERVER['SCRIPT_NAME'] . '" method="post">' . "\n";

      //EMAIL mit dem Email vom angemeldeten Benutzer als standart Wert
      echo '<div class="reservation_values">';
      echo '<label>Email: </label>';
      echo '<input type="text" name="DATA_Email" value="'.$Email.'" required/>';
      echo '</div>';

      // Konferenzraum
      echo '<div class="reservation_values">';
      echo '<label>Konferenzraum: </label>';
      echo '<select name="DATA_IDKonferenzraum">';
      // setze den query in ein assosiatives array
        while ($row = $result->fetch_assoc()){
          //select-box mit alle konferenzräume aber mit dem namen vom Konferenzraum angezeigt.
          echo '<option value="'.$row["IDKonferenzraum"].'">'.$row["Name"].'</option>';
        }
      echo "</select>";
      echo '</div>';


      //DATUM
      echo '<div class="reservation_values">';
      echo '<label for="date">Datum: </label>';
      echo '<input type="date" id="date" name="DATA_Datum"><br>';
      echo '</div>';

      //tabelle mit alle stunden von 8.00 bis 17.00
      echo "<table id='hours'>";
        //Tabellenzeilen werden von jquery hinzugefügt
      echo "</table>";

      //RESERVIERUNGSZWECK
      echo '<div class="reservation_values">';
      echo '<label>Reservierungszweck: </label>';
      echo '<input type="text" name="DATA_Reservierungszweck" value="" required/>';
      echo '</div>';

      // Ein button zum bestätigen der Reservierung
      echo '<input class="smallButton" type="submit" name="BUTTON_confirm" value="Reserve" /> <br>';

      echo "</form>";
      echo "</div>";

      // wenn button geklickt wird dann...
      if (isset($_POST["BUTTON_confirm"])){

      // alle informationen die angegeben im Form in eine variabel setzen
      $email = $_POST["DATA_Email"];
      $idkonferenzraum = $_POST["DATA_IDKonferenzraum"];
      $datum = $_POST["DATA_Datum"];
      $reservierungszweck = $_POST["DATA_Reservierungszweck"];

      // wenn uhreziten gewählt wurden dann...
      if(isset($_POST['hours'])){
        // setze diese stunden auch in eine variabel
        $selected_hours = $_POST['hours'];

        //wenn eine eine Uhrzeit selektioniert wurde, dann..
        if(!empty($selected_hours)){
          // zähle wievile stunden der benutzer gewählt hat, und setze diese in eine variabel $dauer
          $duration = count($selected_hours);
          // setze den ersten wert den gewählt wurde in eine variabel $start_time
          $start_time = $selected_hours[0];
          // rechne die endzeit= $start_time + $duration und setzt den formatiertes resultar in H:i, d.h. 00.00 in eine variabel $end_time
          $end_time = date("H:i", strtotime("+" . $duration . " hours", strtotime($start_time)));
        }
    }else{ // wenn keine Stunde gewählt, zeige ein alert box
      echo ("<script LANGUAGE='JavaScript'>
              window.alert('Du hast keine Stunde gewählt.');
            </script>");
            exit(); // beendet den script
    }

      // das Datum in einen timestamp umwandeln
      $timestamp = strtotime($datum);

      // prüfen, ob es bereits eine Reservierung mit demselben Datum, Raum und Uhrzeit gibt
      $query = "SELECT * FROM reservieren WHERE Datum='$datum' AND IDKonferenzraum='$idkonferenzraum' AND Startzeit='$start_time'";
      $result = $mysqli->query($query);
      if ($result->num_rows > 0) {
    // es gibt bereits eine Reservierung mit demselben Datum, Raum und denselben Öffnungszeiten, dann zeige ein alert
    echo '<script>alert("Zu diesem Zeitpunkt gibt es bereits eine Reservierung in diesem Raum. Bitte wählen Sie ein anderes Datum oder eine andere Uhrzeit.");</script>';
    exit();// beendet den script
      }else{
        // Ermittelt den Wochentag als Zahl
        // Sonntag ist 0, Montag ist 1, usw.
      $day_of_week = date('w', $timestamp);
      // wenn tag 0 oder 6 ist dann ist es wochenende
      if ($day_of_week == 0 || $day_of_week == 6) {
        // das Datum ist ein Wochenendtag, wird eine Fehlermeldung angezeigt und das Skript angehalten
        echo ("<script LANGUAGE='JavaScript'>
                window.alert('An Wochenenden sind keine Reservierungen möglich.');
              </script>");
              exit();// beendet script
      }else{
            //formatiert das Datum in ein format den der phpmyadmin lesen kann
            $formatted_date = date("d.m.Y", strtotime($datum));

            // Namen von Konferenzräume
            $query = "SELECT Name FROM konferenzraum WHERE IDKonferenzraum = '$idkonferenzraum'";
            $result = $mysqli->query($query);
            $row = $result->fetch_assoc();
            // setzt die namen in eine variabel
            $konferenzraum_name = $row['Name'];

            // query wor er die reservierung macht und alle daten, den der user gewählt hat ins datebank hinzufügt
            $query = "INSERT INTO reservieren (Email, IDKonferenzraum, Datum, Startzeit, Reservierungszweck, Dauer) VALUES ('$email', '$idkonferenzraum', '$datum', '$start_time', '$reservierungszweck', '$duration')";
            $result = $mysqli->query($query);

            // wenn query funktioniert, dann...
            if($result){
            // zeigt eine alert box mit alle informationen den der Benutzer gewählt hat
            echo "<script>alert('Reservierung gemacht:\\nEmail: $email\\nDatum: $formatted_date\\nStartzeit: $start_time\\nDauer: $duration\\nEndzeit: $end_time\\nKonferenzraum: $konferenzraum_name\\nReservierungszweck: $reservierungszweck');</script>";
            // den pop-up wird dann geschlossen
            echo '<script>window.close();</script>';
          }
            else{ //wennn query nicht funktioniert, dann wird alert-box gezeigt
            echo ("<script LANGUAGE='JavaScript'>
                    window.alert('Fehler beim Erstellen Ihrer Reservierung!');
                  </script>");
            // den pop-up wird dann geschlossen
            echo '<script>window.close();</script>';
          }
          }
        }
      }
    }
?>
