<?php 
//----INCLUDE APIS------------------------------------
include("api/api.inc.php");

//----PAGE GENERATION LOGIC---------------------------

function createPage()
{    
$tcontent = <<<PAGE
 <h1>Sorry, we couldn't find what you were looking for...</h1>
 <p><a href="index.php" class="btn btn-primary">Go Home</a></p>
PAGE;
return $tcontent;
}

//----BUSINESS LOGIC---------------------------------
//Start up a PHP Session for this user.
session_start();

//Build up our Dynamic Content Items. 
$tpagetitle = "404: Error Page";
$tpagecontent = createPage();

//----BUILD OUR HTML PAGE----------------------------
//Create an instance of our Page class
$tpage = new MasterPage($tpagetitle);
$tpage->setDynamic2($tpagecontent);    
$tpage->renderPage();
?>