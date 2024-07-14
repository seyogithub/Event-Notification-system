<?php
echo ' <div id="profile">' ;

 $select_all="SELECT *  FROM admin WHERE Admin_ID='$admin_id'";
$select_all_qurey=mysqli_query($conn,$select_all);

$data=mysqli_fetch_assoc($select_all_qurey);
if($data['image']=="Not Photo"){
    if($data['Gender']=="male"){
        echo '<img src="\WCU EVENT NOTIFICATION SYSTEM\images\profileImage\pic-6.jpg" alt="">';
    }else{
        echo '<img src="\WCU EVENT NOTIFICATION SYSTEM\images\profileImage\pic-2.jpg" alt="">';
    }
}else{
    
  echo'  <img src="\WCU EVENT NOTIFICATION SYSTEM\images\profileImage'.$data['image'].'" alt="">';
}

echo '
</div>
<div id="profile-menu"> ';
    if ($data['image'] == "Not Photo") {
        if ($data['Gender'] == "male") {
            echo '<img id="preview" src="\ethio elite\Exit Exam/user_uploade_photo/pic-6.jpg" alt="">';
        } else {
            echo '<img id="preview" src="\ethio elite\Exit Exam/user_uploade_photo/pic-2.jpg" alt="">';
        }
    } else {
        echo '<img id="preview" src="\ethio elite\Exit Exam\user_uploade_photo/' . $data['image'] . '" alt="">';
    }
echo '
<h3>'. $data['Fname'].'</h3>
<span>Admin</span>
<a href="\ethio elite\Exit Exam\admin\viewAdminProfile.php" class="btn">View Your Profile</a>
<a href="\ethio elite\Exit Exam\admin\updateAdminProfile.php" class="btn">Update Your Profile</a>
<a href="\ethio elite\Exit Exam\admin\selectOtherAdminProfile.php" class="btn">Update Other\'s Profile</a>
<a href="\ethio elite\Exit Exam\admin\changeProfilePicture.php" class="btn">Change Profile Picture</a>
<a href="\ethio elite\Exit Exam\admin\registerNewAdmin.php" class="btn">Register New Admin</a>



<a href="\ethio elite\Exit Exam\register and login\logout.php" 
    class="delete-btn btn">logout</a>

</div>
';
echo '
<style>
.input-style{

    border: .1px #0a3b5c solid;
    width: 60%;
    outline: none;
    border-radius: 5px;
    font-size: 1.1rem;
    padding:.8%;

}

#input-short-note select {
    width: 60%;
    border-width: .1rem;
    outline: none;
    border-radius: 5px;
    font-size: 1.1rem;
    border: .1px #0a3b5c solid;
    padding:.8%;

}


.update {
    padding:5%;
    border-radius: 6px;
    border: 1px solid rgb(0, 128, 0);
    color: #0a3b5c;
    background-color: var(--first-color--);
}

.update:hover a {
    border: none;
    background-color: #ffff;
    color: var(--third-color--);
    
}

.delete-update-status a {
    color: var(--third-color--);
}

.Status {
    padding:5%;
border-radius: 6px;

    border: 1px solid rgb(134, 77, 239);
    color: var(--third-color--);
    background-color: var(--first-color--);
}

.Status:hover a {
    background-color: #ffff;
    color: var(--third-color--);
    border: none;
}

.delete {
    
    padding:5%;
    border-radius: 6px;
    border: 1px solid rgb(255, 0, 0);
    cursor: pointer;
    background-color: var(--first-color--);

}

.delete:hover {
    background-color: #ffff;
    color: var(--third-color--);
    border: 1px solid rgb(255, 0, 0);

}


#open-close {
    display: none;
}

#profile{
    border:3px #fff solid ;
    height: 7vh;
    aspect-ratio: 1/1;
    border-radius: 50%;
}
img{
    width: 100%;
    height: 100%;
    border-radius: 50%;
    cursor: pointer;
}

#profile-menu{
position: absolute;
top: 110%;
right: 1rem;
background-color: var(--forth-color--);
border-radius: .5rem;
padding: 1rem;
text-align: center;
width: 15rem;
transform: scale(1);
transform-origin: top right;
background: rgba(255, 255, 255, 0.95);
box-shadow: 0 0px 15px rgba(0, 0, 0, 0.15);
display:none;
}

#department-select{
    display: inline-block;
    width: 60%;
    font-size: 1.1rem;
    border-radius: 5px;
    outline:none;

}


.department-form .label-style {
    display: inline-block;
    width: 35%;
    color: var(--forth-color--);
    font-size: 1.1rem;
    padding-left: 2%;
    margin-bottom: 1%;
}

.department-form{
    width: 80%;
    color:red;
}

#profile-menu img{
height: 6rem;
width: 6rem;
border-radius: 50%;
object-fit: cover;
margin-bottom: .5rem;
}
#profile-menu h3{
font-size: 1.3rem;
color: var(--black);
width:100%;
background-color:#fff;
}

#profile-menu span{
color: var(--light-color);
font-size: 1.1rem;
}

.btn,
.delete-btn{
border-radius: .5rem;
padding: .5rem .3rem;
font-size: 1.1rem;
color: #fff;
margin: 0 auto;
margin-top: .5rem;
text-transform: capitalize;
cursor: pointer;
text-align: center;
display: block;
width: 90%;
}

.btn{
background-color: var(--forth-color--);
}

.btn:hover{
background-color: var(--second-color--);

}
.delete-btn:hover{
background-color: red;
}

.logo {
    color: var(--first-color--);
    font-size: 1.8rem;
    font-weight: bolder;
    cursor: default;
}

.logo span {
    color: var(--fifth-color--);
    font-size: 1.8rem;
}

@media (max-width:1040px) {

    .tableFixHead thead tr th:nth-child(7),
    .tableFixHead thead tr th:nth-child(8),
    .tableFixHead tbody tr td:nth-child(7),
    .tableFixHead tbody tr td:nth-child(8){
    
display:none;

    }


 .side-bar-conti{
    width: 0%;
    clear:both;
    float:none;
 }
.logo{
display:none;
}

#logo{
    display:block;
    }
 #open-close {
    display: block;
}


#close-menu {
    display: none;
    z-index:19;

}
#open-menu,
#close-menu {
    color: #864def;
    width:3rem;
    height:3rem;
    justify-content:center;
    align-items:center;
    text-align:center;
    padding-top:21%;
}
 .nav-bar {
    position: fixed;
    right: 0;
    width: 100%;
    top: 0;
}
aside.side-bar-conti {
    position: fixed;
    top: 9vh;
    left: 0;
    height: 100vh;
    /* width: 20%; */
    max-width: 20%;
    background-color: var(--third-color--);
    color: var(--first-color--);
    font-size: large;
    font-weight: bolder;
    padding-top: 2%;
    /* float: left; */
     display: none; 
    overflow: scroll;
    overflow-x: hidden;
}

aside.side-bar-conti {
    position: absolute;
    top: 9vh;
    left: 0;
    height: 100vh;
     width: 100%; 
    max-width: 30%;
    background-color: var(--third-color--);
    color: var(--first-color--);
    font-size: large;
    font-weight: bolder;
    padding-top: 2%;
    /* float: left; */
    display: none; 
    overflow: scroll;
    z-index:11;
    overflow-x: hidden;
}

.main-part {
    float: right;
    width: 100%;
    /* background-color: var(--first-color--); */
    color: var(--first-color--);
    background: #f4f4f4;
    margin-left: .1%;
    height: 91vh;
    /* overflow: scroll; */
    /* position: relative; */
    overflow: scroll;
    overflow-x: hidden;
    margin-top: 9vh;
}
}

@media (max-width:850px) {
    .form {
        width: 70%;
        margin: 0 auto;
        background: #f4f4f4;
        background-color: var(--first-color--);
        box-shadow: 0 0px 15px rgba(0, 0, 0, 0.15);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 3%;
        border-radius: 20px;
    }

    .tableFixHead thead tr th:nth-child(4),
    .tableFixHead thead tr th:nth-child(3),
    .tableFixHead tbody tr td:nth-child(4),
    .tableFixHead tbody tr td:nth-child(3){
    
display:none;

    }

    
#payable-ppt-resources-all{
    display: grid;
    grid-template-columns: repeat(auto-fit,32.5%);
    gap: .66%;
    row-gap:.2%;
    grid-auto-flow: row;
    justify-content: start;
    align-content: start;
    transition: .5s;
    /* margin-bottom: 10px;
    margin-top: 10px; */
    padding:1%;
}
}

@media (max-width:700px) {

    .form {
        width: 80%;
        margin: 0 auto;
        background: #f4f4f4;
        background-color: var(--first-color--);
        box-shadow: 0 0px 15px rgba(0, 0, 0, 0.15);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 3%;
        border-radius: 20px;
    }

    aside.side-bar-conti {
        position: absolute;
        top: 9vh;
        left: 0;
        height: 100vh;
         width: 100%; 
        max-width: 40%;
        background-color: var(--third-color--);
        color: var(--first-color--);
        font-size: large;
        font-weight: bolder;
        padding-top: 2%;
        /* float: left; */
        display: none; 
        overflow: scroll;
        z-index:11;
        overflow-x: hidden;
    }

    .tableFixHead thead tr th:nth-child(6),
    .tableFixHead thead tr th:nth-child(5),
    .tableFixHead tbody tr td:nth-child(6),
    .tableFixHead tbody tr td:nth-child(5){
    
display:none;

    }
}

@media (max-width:600px) {

    .form {
        width: 90%;
        margin: 0 auto;
        background: #f4f4f4;
        background-color: var(--first-color--);
        box-shadow: 0 0px 15px rgba(0, 0, 0, 0.15);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 3%;
        border-radius: 20px;
    }
    aside.side-bar-conti {
        position: absolute;
        top: 9vh;
        left: 0;
        height: 100vh;
         width: 100%; 
        max-width: 50%;
        background-color: var(--third-color--);
        color: var(--first-color--);
        font-size: large;
        font-weight: bolder;
        padding-top: 2%;
        /* float: left; */
        display: none; 
        overflow: scroll;
        z-index:11;
        overflow-x: hidden;
    }
    
    
#payable-ppt-resources-all{
    display: grid;
    grid-template-columns: repeat(auto-fit,48.5%);
    gap: 1.66%;
    row-gap:.8%;
    grid-auto-flow: row;
    justify-content: start;
    align-content: start;
    transition: .5s;
    /* margin-bottom: 10px;
    margin-top: 10px; */
    padding:1%;
}
}

@media (max-width:530px) {

    .form {
        width: 100%;
        margin: 0 auto;
        background: #f4f4f4;
        background-color: var(--first-color--);
        box-shadow: 0 0px 15px rgba(0, 0, 0, 0.15);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 3%;
        border-radius: 20px;
    }
    aside.side-bar-conti {
        position: absolute;
        top: 9vh;
        left: 0;
        height: 100vh;
         width: 100%; 
        max-width: 60%;
        background-color: var(--third-color--);
        color: var(--first-color--);
        font-size: large;
        font-weight: bolder;
        padding-top: 2%;
        /* float: left; */
        display: none; 
        overflow: scroll;
        z-index:11;
        overflow-x: hidden;
    }
}
@media (max-width:500px) {
    #open-close {
        display: block;
    }
    #close-menu {
        display: none;
    }

    .navgation{
        display:none;
    }
    .logo{
        display:block;
        }
    .department-form .label-style,
    #input-short-note .label-style {
        display: inline-block;
        width: 100%;
        color: var(--forth-color--);
        font-size: 1.1rem;
        padding-left: 2%;
        margin-bottom: 1%;
    }
    #input-short-note input[type="file"],
    #input-short-note select,
    #department-select {
        display: inline-block;
        width: 70%;
        font-size: 1.1rem;
        border-radius: 5px;
        outline: none;
        margin-left:20%;
    }

    .side-bar-conti{
        width: 10%;
        clear:both;
        float:none;
     }

     aside.side-bar-conti {
        position: absolute;
        top: 9vh;
        left: 0;
        height: 100vh;
         width: 100%; 
        max-width: 100%;
        background-color: var(--third-color--);
        color: var(--first-color--);
        font-size: large;
        font-weight: bolder;
        padding-top: 2%;
        /* float: left; */
        display: none;
        overflow: scroll;
        z-index:11;
        overflow-x: hidden;
    }

    #payable-ppt-resources-all{
        display: grid;
        grid-template-columns: repeat(auto-fit,80%);
        gap: 1.66%;
        row-gap:.8%;
        grid-auto-flow: row;
        justify-content: center;
        align-content: start;
        transition: .5s;
        /* margin-bottom: 10px;
        margin-top: 10px; */
        padding:1%;
    }
.input-style {
        border: .1px #0a3b5c solid;
        width: 70%;
        outline: none;
        border-radius: 5px;
        font-size: 1.1rem;
        padding: .8%;
        margin-left: 20%;
    }
    
}


</style>

';
?>