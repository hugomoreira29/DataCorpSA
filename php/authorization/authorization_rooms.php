<?php
session_start();
?>
<?php
  // Verbindung mit datenbank
  include "../connect.php";
    // query um alle daten von tabelle konferenzraum zu haben
    $query  = "SELECT * FROM konferenzraum";
    //mache den query
    $result = $mysqli->query($query);

    // wenn query funktioniert dann,...
    if( $result)
      {
        //Form erstellen
        echo '<form action="' . $_SERVER['SCRIPT_NAME'] . '" method="post">' . "\n";
        // Select box mit alle Konferenzräume zu wählen
        echo '<select name="DATA_IDKonferenzraum">';
        // setze den query in ein assosiatives array, und dann auf eine variabel
          while ($row = $result->fetch_assoc()){
            echo '<option value="'.$row["IDKonferenzraum"].'">'.$row["Name"].'</option>'; // optionen von der select box mit alle konferenzraume als Name aber den value ist den id
          }
        echo "</select>";
        // ein button zum sehen ob es allow ist oder nicht
        echo '<input class="smallButton" type="submit" name="BUTTON_confirm" value="Check" /> <br>';
        echo "</form>";

        //wenn button geklickt wird dann...
        if (isset($_POST["BUTTON_confirm"])){
          // setzt die session in eine variabel, und den konferenzraum auch in eine variabel id
          $Email = $_SESSION["email"];
          $id = $_POST["DATA_IDKonferenzraum"];
          // Prüfen, ob der den konferenzraum den der User gewählt hat ist gerade für ihn aktiv ist
          $authorizationsquery = "SELECT * FROM reservieren WHERE Email = '$Email' AND IDKonferenzraum = '$id' AND Datum = CURDATE() AND Startzeit <= CURTIME() AND Dauer > 0";
          $result = mysqli_query($mysqli, $authorizationsquery);

          //wenn query grösser als 0 ist dann..
          if (mysqli_num_rows($result) > 0) {
          // Dieses raum ist gerade aktiv, dann macht er ein json mit den allowed als true
          echo json_encode(array('allowed' => true));
          } else {
          // Dieses raum ist gerade nicht aktiv, dann macht er ein json mit den allowed als false
          echo json_encode(array('allowed' => false));
          }
        }
      }
?>
