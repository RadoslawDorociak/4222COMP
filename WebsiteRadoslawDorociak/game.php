<?php
// ----INCLUDE APIS------------------------------------
include ("api/api.inc.php");

// ----PAGE GENERATION LOGIC---------------------------
function createPage($pgames, $pcommercials, $previews, $tid)
{
    $tgameprofile = "";
    $tcommercialsection = "";
    $treviewsection = "";
    foreach($pgames as $tg)
    {
        $tgameprofile .= renderGameOverview($tg);
    }
    foreach($pcommercials as $tc)
    {
        $tcommercialsection .= renderCommercialSection($tc);
    }
    
    if(!empty($previews)){
        foreach($previews as $tr)
        {
            $treviewsection .= renderReviewAsPanel($tr);
        }
    }
    else{
        $treviewsection = <<<PAGE
        <h3> No reviews yet! </h3>
PAGE;
    }
    
    $tcontent = <<<PAGE
      {$tgameprofile}
      {$tcommercialsection}
      
<div class="commentSection commentinnersection row">
<h2> Comment Section </h2>
      {$treviewsection}
      <a class = "btn btn-info navbar-right"
    href="write_review.php?id={$tid}">Add a Review</a>
      </div>
PAGE;
    return $tcontent;
}

// ----BUSINESS LOGIC---------------------------------
// Start up a PHP Session for this user.
session_start();

$tgames = [];
$treviews = [];
$tcommercials = [];
$treviews = [];
$tid = $_REQUEST["id"] ?? -1;


//Handle our Requests and Search for Players using different methods
if (is_numeric($tid) && $tid > 0) 
{
    $tgame = jsonLoadOneGame($tid);
    $tcommercial = jsonLoadOneCommercial($tid);
    $tgames[] = $tgame;
    $tcommercials[] = $tcommercial;

} 

$treviewlist = jsonLoadAllReview();
    foreach ($treviewlist as $tr)
    {
        if($tr->game === $tgame->title)
            $treviews[] = $tr;
        
    }


    
 


if (count($tgames)===0) 
{
    appGoToError();
} 
else
{
    //We've found our player
    $tpagecontent = createPage($tgames, $tcommercials, $treviews, $tid);
    $tpagetitle = "Game Page";

    // ----BUILD OUR HTML PAGE----------------------------
    // Create an instance of our Page class
    $tpage = new MasterPage($tpagetitle);
    $tpage->setDynamic2($tpagecontent);
    $tpage->renderPage();
}
?>