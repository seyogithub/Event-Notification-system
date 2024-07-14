<?php
        include "../Equip/Connection.php";

    $id = $_GET["user_id"];
    

    ?>
    <script>
        var del=confirm('Are You Shure You Want Delete this User');

        if(del){
            <?php
            mysqli_query($conn,"delete from user where user_id = $id");
            ?>
            alert("You Have Successfully Deleted user.");
        window.location="Edit_user.php";
        }else{
            alert("User Not Deleted.");

            window.location="Edit_user.php";
        }
        
    </script>
    <?php
?>
