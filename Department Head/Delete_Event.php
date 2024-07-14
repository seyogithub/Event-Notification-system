<?php
include("../Equip/Connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['event_id'])) {
    $event_id = $_POST['event_id'];

    $sql = "DELETE FROM event WHERE event_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $event_id);

    if ($stmt->execute()) {
        ?>

    alert("You Have Successfully Deleted the Event");
  

        <?php
       
    } else {

        ?>
        <script>
            alert("Error deleting event");
            window.location.href="Create_Event.php";
        </script>
                <?php
        
    }

}

?>

