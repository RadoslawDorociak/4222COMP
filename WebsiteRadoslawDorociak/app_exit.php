<?php 
//----INCLUDE APIS------------------------------------
include("api/api.inc.php");

//----BUSINESS LOGIC---------------------------------

//Get Existing Session
session_start();

$taction = $_REQUEST["action"] ?? "";
if($taction == "exit" && appSessionLoginExists())
{
    appSessionDestroy();
    appGoToHome();
}
else 
{
    appGoToError();
}