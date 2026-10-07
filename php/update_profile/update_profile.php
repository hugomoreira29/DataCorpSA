<?php
  // Definieren Sie ein Array mit den Header:
  $header['RFID'] = 'RFID';
  $header['Email'] = 'Email';
  $header['Vorname'] = 'Vorname';
  $header['Name'] = 'Name';
  $header['Passwort'] = 'Passwort';

  // Definieren Sie eine Kontrollvariable, die angibt, ob der Inhalt der Datenbank angezeigt werden muss:
  $show_database = true;

  // Prüfen Sie das Vorhandensein eines UPDATE-BUTTON in der Struktur $_POST: ---------------------------------------
  foreach($_POST as $key=>$value){
    if(substr($key,0,14)=="BUTTON_update_"){

      // Extrahieren Sie den ID:
      $update_RFID = substr($key,14);

      // query um den user zu wählen
      $edit_query = "SELECT * FROM benutzer WHERE RFID = $update_RFID ;";
      $result = $mysqli->query($edit_query);

      // wenn den query erfolgreich, dann Daten anzeigen
      if($result)
      {
        echo "<div class='update_profile'>";
        //titel mit dem logo vom internet
        echo "<h2> Profil ändern <i class='fa fa-fw fa-user'></i></h2>";

        //daten von der datenbank in ein array setzen
        $row = $result->fetch_assoc();
        // Erstellen eines Formulars zur Bearbeitung der Einstellungen
        echo '<form action="' . $_SERVER['SCRIPT_NAME'] . '" method="post"> ' . "\n";
          echo '<div class="edit_values">';
              echo '<input type="input" name="DATA_newemail" value="' . $row["Email"] .'" />';
              echo "<span></span>";
            echo '<label>Email</label>';
          echo '</div>';

          echo '<div class="edit_values">';
              echo '<input type="input" name="DATA_newvorname" value="' . $row["Vorname"] .'" />';
            echo "<span></span>";
          echo '<label>Vorname</label>';
          echo '</div>';

          echo '<div class="edit_values">';
              echo '<input type="input" name="DATA_newname" value="' . $row["Name"] .'" />';
            echo "<span></span>";
          echo '<label>Name</label>';
          echo '</div>';

          echo '<div class="edit_values">';
              echo '<input type="password" name="DATA_newpasswort" value="*****" />';
            echo "<span></span>";
          echo '<label>Passwort</label>';
          echo '</div>';
          // Übertragen Sie die RFID als verstopptes Feld:
          echo '<input type="hidden" name="DATA_rfid" value="' . $row["RFID"] .'" /><br>';
          // update oder cancel Button
          //wenn den update button gedrückt wird dann kommt ein alert der fragt ob der user sicher ist.
          echo '<input class = "smallButton" type="submit" name="BUTTON_confirm" onclick="return confirm(\'Sind Sie sicher, dass Sie Ihr Profil ändern möchten??\')" value="Update bestätigen" /> <br>';
          echo '<input class = "smallButton" type="submit" name="BUTTON_cancel" value="Stornieren" /><br>';
        echo "</form>\n";
        echo "</div>";
      }
      // NICHT den Inhalt der Datenbank anzeigen:
      $show_database = false;
    }
  }

  // wenn button geklickt wird dann...
  if(isset($_POST["BUTTON_confirm"]))
  {
      // Abrufen von Daten den der user eingegeben hat
    $update_RFID = $_POST["DATA_rfid"];
    $newemail = $_POST["DATA_newemail"];
    $newvorname = $_POST["DATA_newvorname"];
    $newname = $_POST["DATA_newname"];
    $newpasswort = $_POST["DATA_newpasswort"];
    $passhash = password_hash($newpasswort, PASSWORD_DEFAULT);

    // query für die benutzereinstellungen zu machen
    $update_query = "UPDATE benutzer SET Email = \"$newemail\", Vorname = \"$newvorname\", Name = \"$newname\", Passwort = \"$passhash\" WHERE RFID = $update_RFID;";
    // mache den query
    $result = $mysqli->query($update_query);
    // wenn den query geht dann...
    if($result){
    echo ("<script LANGUAGE='JavaScript'>
            window.alert('Ihr Profil wurde geändert.');
          </script>");         // wenn aktualisiert, Alarm anzeigen
          $show_database = true;
        }
    else{
      echo "Fehler beim Ändern Ihres Profils!<br><br>"; // wenn nicht dann schreibe das.
    }
  }

  // wenn BUTTON_cancel zeige wieder die Benutzereinstellungen
  if(isset($_POST["BUTTON_cancel"]))
    $show_database = true;

  // wenn $show_database true ist, dann den Inhalt der Datenbank anzeigen:
  if($show_database === true)
  {
    // query um die benutzereinstellungen von $Email
    $query  = "select * from benutzer where Email = '$Email'";

    //mache den query
    $result = $mysqli->query($query);

    // wenn query funktionniert dann..
    if( $result)
    {
      echo "<div class='profile'>";
      // Titel mit icon
      echo "<h2> Mein Profil <i class='fa fa-fw fa-user'></i></h2>";
      echo '<form action="' . $_SERVER['SCRIPT_NAME'] . '" method="post"> ' . "\n";
      // mache eine tabelle
      echo "<table class='profile_table'>";
      // header von der tabelle mit denen header am anfang
      echo "<thead>";
        echo "<tr>";
          echo '<th>';
            echo $header['RFID'];
          echo '</th>';

          echo '<th>';
            echo $header['Email'];
          echo '</th>';

          echo '<th>';
            echo $header['Vorname'];
          echo '</th>';

          echo '<th>';
            echo $header['Name'];
          echo '</th>';

          echo '<th>';
            echo $header['Passwort'];
          echo '</th>';

        echo "</tr>";
      echo "</thead>";

          // Assoziatives Array abrufen und die Tabelle füllen:
          while ($row = $result->fetch_assoc())
          {
            echo "<tbody>";
            echo "<tr>";
              echo '<td>'.$row["RFID"]  . '</td>';
              echo '<td>'.$row["Email"] . '</td>';
              echo '<td>'.$row["Vorname"] . '</td>';
              echo '<td>'.$row["Name"]  . '</td>';
              echo '<td>'."*****" . '</td>';
            echo "</tr>";
            echo "</tbody>";
            echo "</table>";
            // Button mit dem RFID als ID
            echo '<input class = "update_button"  type="submit" name="BUTTON_update_'. $row["RFID"] . '" value="aktualisieren" />';
          }
      echo "</form><br><br>";

    echo "</div>";

      // Geben Sie die Ergebnisvariable frei:
      $result->free();
    }
    // Verbindung mit datenbank trennen
    $mysqli->close();
  }
?>
