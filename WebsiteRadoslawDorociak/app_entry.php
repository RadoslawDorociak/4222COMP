<?php 
//----INCLUDE APIS------------------------------------
include("api/api.inc.php");

//----BUSINESS LOGIC---------------------------------
//Start up a PHP Session for this user.
session_start();
$tmypass = $_REQUEST["mypassword"] ?? "";
$tmyname = $_REQUEST["myname"] ?? "";
$tlogintoken = $_REQUEST["myuser"] ?? "";
$tvalid = false;
if(empty($logintoken) && !empty($tmypass) && !empty($tmyname)){
    $tuserlist = jsonLoadAllUser();
    foreach ($tuserlist as $tp)
    {
        if ($tp->name == $tmyname ) 
        {
            if ($tp->password == $tmypass) 
            {
                $tvalid = true;
            }	
        }
    }
}



if($tvalid == true){
    $_SESSION["myuser"] = processRequest($tmyname);
    $_SESSION["entered"] = true;
    header("Location: index.php");
}
else{
    $terror = "app_error.php";
    header("Location: {$terror}");
}

?>