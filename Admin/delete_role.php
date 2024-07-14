<?php
        include "../Equip/Connection.php";

    $id = $_GET["rol_id"];
    

    ?>
    <script>
        var del=confirm('Are You Shure You Want Delete this Role');

        if(del){
            <?php
            mysqli_query($conn,"delete from assRole where rol_id = $id");
            ?>
            alert("You Have Successfully Deleted one role.");
        window.location="Add_role.php";
        }else{
            alert("Role Not Deleted.");

            window.location="Add_role.php";
        }
        
    </script>
    <?php
?>
