<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Reservierungen</title>
    <link rel="shortcut icon" href="../../images/Logo/favicon.ico" type="image/x-icon"/>
    <link rel="stylesheet" href="../../styles/show_reservations.css">
  </head>
  <body>
    <header>
      <?php
    // Webindung mit datenbank
    include "../connect.php";
    //Header von Page Image mit Link zu Home
    echo '<a href="../main.php"><img class="logo" src="../../images/Logo/logo_banner2.png" alt="Logo"></a><br>';
    ?>
    </header>
    <?php
    // query um alles von tabelle reservieren aufsteigend sortiert
     $query = "SELECT * FROM reservieren ORDER BY Datum ASC";

     //mache den query
     $result = $mysqli->query($query);

     // wenn query funktioniert dann...
     if( $result)
     {
       // wenn button geklickt wird dann, wird der user nach your_reservations.php geleitet
       if (isset($_POST["BUTTON_reservations"])){
         header('Location:your_reservations.php');
       }

        // wenn button geklickt wird dann, wird der user nach authorization.php geleitet
       if (isset($_POST["BUTTON_authorization"])){
         header('Location:../authorization/authorization_rooms.php');
        }

        // form mit beiden Buttons
      echo '<form action="' . $_SERVER['SCRIPT_NAME'] . '" method="post">' . "\n";
       echo "<div class='button_reservation'>";
       echo "<button name='BUTTON_authorization' id='authorization'>Controller</button>";
       echo "<button name='BUTTON_reservations' id='your_reservations'>Ihre Reservierungen</button>";
       echo "</div>";
      echo "</form>";
     // Tabelle mit folgendem Header
     echo "<table>";
     echo "<tr>
     <th>Email</th>
     <th>Raum</th>
     <th>Datum</th>
     <th>Startzeit</th>
     <th>Dauer</th>
     <th>Reservierungszweck</th>
     </tr>";

     // Assoziatives Array abrufen und die Tabelle füllen mit alle Daten von der tabelle
     while ($row = $result->fetch_assoc()){
       // den Namen des Konferenzraums von alle IDs herausfinden, um dass in der tabelle die namen stehen durch IDs
       $room_query = "SELECT * FROM konferenzraum WHERE IDKonferenzraum='" . $row["IDKonferenzraum"] . "'";
       $room_result = $mysqli->query($room_query);
       //setze eine leere variabel
       $room_name = "";
       //wenn query funktioniert danm...
       if ($room_result) {
         $room_row = $room_result->fetch_assoc(); //assoziatives Array in der Variablen $room_row gespeichert
         $room_name = $room_row["Name"]; //array in $room_name  gespeichert, so dass die Tabelle Namen anstelle von IDs enthält
       }
        // Umwandlung des Zeitstempels in ein formatiertes Datum und eine Uhrzeit
       $date = $row["Datum"];
       $formatted_date = date("d.m.Y", strtotime($date));
       $time = $row["Dauer"];
       // setze alle informationen von reserierungen von der datenbank ins tabelle
       echo "<tr>";
       echo "<td>" . $row["Email"] . "</td>";
       echo "<td>" . $room_name . "</td>";
       echo "<td>" . $formatted_date . "</td>";
       echo "<td>" . $row["Startzeit"] . "</td>";
       echo "<td>" . $time . "</td>";
       echo "<td>" . $row["Reservierungszweck"] . "</td>";
       echo "</tr>";
     }
     echo "</table>";
   }
?>
  </body>
  <footer>
    <?php
    echo "© 2022-2023 | Webseite wurde von Pereira Moreira Hugo gemacht | 1TPIF";
    ?>
  </footer>
</html>
