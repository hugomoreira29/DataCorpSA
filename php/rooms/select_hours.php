<?php
// Verbindung mit datenbank
include "../connect.php";

// den datum den der benutzer gewählt hat in eine variabel
$date = $_POST['date'];

// Liste der reservierten Stunden für das ausgewählte Datum abrufen
$query = "SELECT Startzeit, Dauer FROM reservieren WHERE Datum = '$date'";
$result = $mysqli->query($query);

// mache ein array mit gewählten Uhrzeiten
$selected_hours = array();

// wenn den query geht und den resultat ist grösser als 0, dann...
if($result->num_rows > 0){
  // setze den query in ein assosiatives array und den array in eine variabel
    while($row = $result->fetch_assoc()){
      // setze die daten von der datenbank in eine variabel
        $start_time = $row["Startzeit"];
        $duration = $row["Dauer"];
        for($i=0; $i<$duration; $i++){ //for loop von 0 bis den nummer der dauer
            $selected_hours[] = date("H:i", strtotime($start_time) + $i * 3600); //macht eine rechnung startzeit + $i und mal 3600 um das in stunden danach in ein array $selected_hours hinzuzufugen
        }
    }
}

// macht eine tabelle
echo '<table>';

// Erstellen eine for loop um eine Zeile in der tabelle für jede Stunde von 8:00 bis 16:00 zu erstellen
for ($hour = 8; $hour <= 16; $hour++) {
  $time = sprintf("%02d:00", $hour); //formatiert die Uhrzeiten in 00.00
  $disabled = in_array($time, $selected_hours) ? "disabled" : ""; // disabled die Uhrzeiten(checkboxen) die schon reserviert sind
  echo '<tr><td>' . $time . '</td>'; // die Uhrzeiten in eien Kolonne
  echo '<td><input type="checkbox" name="hours[]" value="' . $time . '" ' . $disabled . '></td>'; // die checkboxen mit den Uhrzeiten als value, und wenn sie reserviert sind dann, sind sie disabled
  echo '</tr>';
}
?>
