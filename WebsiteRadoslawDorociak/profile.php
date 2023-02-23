<?php
// ----INCLUDE APIS------------------------------------
include ("api/api.inc.php");

// ----PAGE GENERATION LOGIC---------------------------
function createPage($pusers,  $previews)
{
    $tuserprofile = "";
    
    $treviewsection = "";
    foreach($pusers as $tg)
    {
        $tuserprofile .= renderUserOverview($tg);
    }
    
    
    
    foreach($previews as $tr)
    {
        $treviewsection .= renderProfileReview($tr);
    }
    
    $tcontent = <<<PAGE
      {$tuserprofile}
      
      
<div class="commentSection commentinnersection row">
<h2> This User's Reviews: </h2>
      {$treviewsection}
      
    
      </div>
PAGE;
    return $tcontent;
}

// ----BUSINESS LOGIC---------------------------------
// Start up a PHP Session for this user.
session_start();


$tusers = [];
$treviews = [];
$tname = $_REQUEST["name"] ?? "";



if (!empty($tname)) 
{
    //Filter the name
    $tname = appFormProcessData($tname);
    $tuserlist = jsonLoadAllUser();
    foreach ($tuserlist as $tp)
    {
        if (strtolower($tp->name) === strtolower($tname)) 
        {
            $tusers[] = $tp;
        }
    }
}




$treviewlist = jsonLoadAllReview();
    foreach ($treviewlist as $tr)
    {
        if($tr->name === $tname)
            $treviews[] = $tr;
        
    }


    
 


if (count($tusers)===0) 
{
    appGoToError();
} 
else
{
    
    $tpagecontent = createPage($tusers,  $treviews);
    $tpagetitle = "Profile Page";

    // ----BUILD OUR HTML PAGE----------------------------
    // Create an instance of our Page class
    $tpage = new MasterPage($tpagetitle);
    $tpage->setDynamic2($tpagecontent);
    $tpage->renderPage();
}
?>