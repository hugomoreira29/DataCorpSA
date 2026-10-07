<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Ihre Reservierungen</title>
  <link rel="shortcut icon" href="../../images/Logo/favicon.ico" type="image/x-icon"/>
  <link rel="stylesheet" href="../../styles/show_reservations.css">
</head>
<body>
  <header>
    <?php
  // Verbindung mit datenbank
  include "../connect.php";
  //Header von Page Image mit Link zur Startseite
  echo '<a href="../main.php"><img class="logo" src="../../images/Logo/logo_banner2.png" alt="Logo"></a><br>';
  ?>
  </header>
  <?php

  // variabel mit der E-Mail des angemeldeten Benutzers
  $email = $_SESSION["email"];

  // query um dass nur die reservierung vom angemeldeten Benutzer zeigen aufsteigend sortiert
  $query = "SELECT * FROM reservieren WHERE Email='$email' ORDER BY Datum ASC";

  // Schicke den query ins datenbank
  $result = $mysqli->query($query);

  // Wenn query funktioniert dann...
  if ($result) {
    // Erstellen Sie eine Tabelle zur Anzeige der Reservierungen des Benutzers
    echo "<table>";
    echo "<tr>
          <th>Raum</th>
          <th>Datum</th>
          <th>Startzeit</th>
          <th>Dauer</th>
          <th>Reservierungszweck</th>
          <th></th>
          </tr>";

      // Assoziatives Array abrufen
    while ($row = $result->fetch_assoc()) {
      // Abrufen des Namens des Konferenzraums
      $room_query = "SELECT * FROM konferenzraum WHERE IDKonferenzraum='" . $row["IDKonferenzraum"] . "'";
      $room_result = $mysqli->query($room_query);
      //setze eine leere variabel
      $room_name = "";
      if ($room_result) {
        $room_row = $room_result->fetch_assoc();//assoziatives Array in der Variablen $room_row gespeichert
        $room_name = $room_row["Name"];//array in $room_name  gespeichert, so dass die Tabelle Namen anstelle von IDs enthält
      }

      // Umwandlung des Zeitstempels in ein formatiertes Datum und eine Uhrzeit
      $date = $row["Datum"];
      $formatted_date = date("d.m.Y", strtotime($date));
      $time = $row["Dauer"];

      //alle informationen von der datenbank ins tabelle
       echo '<form method="post">';
       // verstoppte daten um dass der user diese löschen kann, weil in der datenbank keine id gibt
       echo "<input type='hidden' name='email' value='" . $row["Email"] . "'>";
       echo "<input type='hidden' name='date' value='" . $row["Datum"] . "'>";
       echo "<input type='hidden' name='time' value='" . $row["Dauer"] . "'>";
       echo "<tr>";
       // tabelle mit alle reservierungen vom benutzer
       echo '<td>' . $room_name . '</td>';
       echo '<td>' . $formatted_date . '</td>';
       echo '<td>' . $row["Startzeit"] . '</td>';
       echo '<td>' . $time . '</td>';
       echo '<td>' . $row["Reservierungszweck"] . '</td>';
       //button zum löschen von reservationen
       echo '<td>' . '<button type="submit" name="delete" onclick="return confirm(\'Sind Sie sicher, dass Sie diese Reservierung löschen möchten?\')">Löschen</button>' . '</td>';
       echo "</tr>";
       echo '</form>';
     }
     echo "</table>";
   }

   //wenn button geklickt wird, dann
   if(isset($_POST["delete"])){
     //setze die verstoppte daten von der reservierung der den Benutzer löschen will in eine variabel
     $email = $_POST["email"];
     $date = $_POST["date"];
     $time = $_POST["time"];
     // query zum löschen der reservation
    $delete = $mysqli->query("DELETE FROM reservieren WHERE Email='$email' AND Datum='$date' AND Dauer='$time'");
    // wenn query funktioniert dann...
    if($delete){
        echo "Reservierung erfolgreich gelöscht";
    }else{
        echo "Fehler beim Löschen der Reservierung";
    }
}

  ?>
  <body>
    <footer>
      <?php
      echo "© 2022-2023 | Webseite wurde von Pereira Moreira Hugo gemacht | 1TPIF";
      ?>
    </footer>
  </html>
