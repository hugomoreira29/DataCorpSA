<?php
  $email = $_SESSION['email'];

  echo '<div class="rooms">';
  // Definieren Sie ein Array mit den Kopfzeilennamen:
  $header['idKonferenzraum'] = 'idKonferenzraum';
  $header['Name'] = 'Name';
  $header['Beschreibung'] = 'Beschreibung';

  // $leere variable am anfang
  $sort_string = "";
  $sort_direction = "";

      // Überprüfen ob ein BUTTON_sort_by_ im Post fibt
      foreach($_POST as $key=>$value){
          if(substr($key,0,15)=="BUTTON_sort_by_"){

          //setze den rest vom Button in die variable
          $sort_field = substr($key,15);

          // Überprüfen Sie den Wert, um festzustellen, ob er ASC oder DESC sein muss:
          if (strpos($value, "▲") !== false) // wenn der zeichen gefunden wird dann..
            {
            $sort_direction = " DESC"; //absteigend sortieren
            $header[$sort_field] = $sort_field . " &nbsp; ▼"; //setze den header mit dem $sort_field und den zeuchen nach unten
            }
          else // wenn das zeichen nicht dort ist, dann
            {
            $sort_direction = " ASC"; //aufsteigend sortieren
            $header[$sort_field] = $sort_field . " &nbsp; ▲"; //setze den header mit dem $sort_field und den zeuchen nach oben
            }

          // sort by query mit denen variaben von oben.
          $sort_string = " ORDER BY " . $sort_field . $sort_direction;
          }
      }

  // alles von der tabelle konferenzraum mit dem $sort_string
  $query  = "SELECT * FROM konferenzraum" . $sort_string;

  //mache den query
  $result = $mysqli->query($query);

  // wenn den query funktioniert dann..
  if( $result)
      {
        // zeige den header mit wieviel einträge in der datenbank sind
        echo "<h3> Wir haben " . $result->num_rows . " Räume</h3>";
        echo '<form action="' . $_SERVER['SCRIPT_NAME'] . '" method="post"> ' . "\n";

        // eine tabelle mit dem header
        echo "<table class='room_table'>";
        echo "<thead>";
        echo "<tr>";
        echo '<th>';
        echo '<input class="room_header" type="submit" name="BUTTON_sort_by_Name" value="Raum '.$header['Name'].'" />';
        echo '</th>';

        echo '<th>';
        echo '<input class="description_header" type="submit" name="BUTTON_sort_by_Beschreibung" value="'.$header['Beschreibung'].'" />';
        echo '</th>';

        echo "</tr>";
        echo "</thead>";
        // Assoziatives Array abrufen und die Tabelle füllen mit alle Daten von der tabelle
        while ($row = $result->fetch_assoc()){
            echo "<tbody>";
            echo "<tr>";
            echo "<td>".$row["Name"]."</td>";// namen von denen räumen
            echo "<td>".$row["Beschreibung"]."</td>";// beschreibung von alle räume
            echo "</tr>";
            echo "</tbody>";
            }
        echo "</table>";

        echo "<div class='table_buttons'>";
        // ein button mit eine funktion
        echo '<input type="submit" class= "BUTTON_reserve" name="BUTTON_reserve" value="Reserviere hier" onclick="openReservationWindow()"/>';
        // ein normales button um reservierungen zu sehen
        echo '<input type="submit" class= "BUTTON_reserve" name="BUTTON_show_reservations" value="Reservierungen"/>';
        echo "</div>";
        echo "</form><br><br>";
        echo "</div>";

        // wenn button geklickt wird dann leitet er den user in show_reservations.php
        if (isset($_POST["BUTTON_show_reservations"])){
            header('Location:rooms/show_reservations.php');
        };

        // funktion der ein pop-up öffnen
        echo "<script>";
        echo "function openReservationWindow() {
        window.open('rooms/create_reservation.php', '_blank', 'width=600,height=650');"; //ööfnen den create_reservation in eine leere seite, mit folgende länge uns breite
        echo "}";
        echo "</script>";


        echo "<div class='welcome_msg'>";
          echo "Eingeloggt mit " . $_SESSION['email']; // eine message am ende mit welchem account der user eingeloggt ist
        echo "</div>";
        // die Ergebnisvariable freigeben:
        $result->free();
      }
  // Werbindung trennern
  $mysqli->close();
?>
