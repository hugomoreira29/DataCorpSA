<?php
session_start();
?>
<?php
    include "../connect.php";
    // formulate the select-query into a string variable:
    $query  = "SELECT * FROM konferenzraum";

    //send query
    $result = $mysqli->query($query);

    // if successful, then display data
    if( $result)
    {
      $Email = $_SESSION['email'];

      echo '<img class="user" src="../../images/Logo/logo_banner2.png" alt="User">';
      echo "<h1>Reservation for Room</h1>";
      //Create a registration form
      echo '<form action="' . $_SERVER['SCRIPT_NAME'] . '" method="post">' . "\n";
      //Formular

      //EMAIL
      echo '<div class="reservation_values">';
      echo '<label>E-Mail: </label>';
      echo '<input type="text" name="DATA_Email" value="'.$Email.'" required/>';
      echo '</div>';

      // fetch all ID from the Rooms
      echo '<div class="reservation_values">';
      echo '<label>Konferenzraum: </label>';
      echo '<select name="DATA_IDKonferenzraum">';
        while ($row = $result->fetch_assoc()){
          echo '<option value="'.$row["IDKonferenzraum"].'">'.$row["Name"].'</option>';
        }
      echo "</select>";
      echo '</div>';


      //DATE
      echo '<div class="reservation_values">';
      echo '<label for="date">Datum: </label>';
      echo '<input type="date" id="date" name="DATA_Datum"><br>';
      echo '</div>';

      //TIME START
      echo '<div class="reservation_values">';
      echo '<label for="time">Startzeit: </label>';
      echo '<input type="time" name="DATA_Startzeit" id="time">';
      echo '</div>';

      //reason for the reservation
      echo '<div class="reservation_values">';
      echo '<label>Reservierungszweck: </label>';
      echo '<input type="text" name="DATA_Reservierungszweck" value="" required/>';
      echo '</div>';

      // show the confirm button:
      echo '<input class="smallButton" type="submit" name="BUTTON_confirm" value="Reserve" /> <br>';

      echo "</form>";
      // free the result variable:
      $result->free();
    }
?>
