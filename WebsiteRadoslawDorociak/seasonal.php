<?php
//This page is the additional view
// ----INCLUDE APIS------------------------------------
include ("api/api.inc.php");

// ----PAGE GENERATION LOGIC---------------------------
function createPage($pgames, $sid)
{
    $tgameprofile = "";
    $theader = "";
    foreach($pgames as $tg)
    {
        $tgameprofile .= renderSeasonalGame($tg);
    }
    //different text depending on season
    if($sid == "easter"){
        $theader = <<<HEAD
        <h2>Easter Time is Here!</h2>
        <h4>But what does it mean? If there is one thing that's important for us in this special time, it's a time of rebirth. Grass is green once again, nature is waking up
        and it's the perfect time to plant your crops. For this season, our recommended game is: </h4>
HEAD;

    }
    else if($sid == "summer"){
        $theader = <<<HEAD
        <h2>Summer Time!</h2>
        <h4>Nothing says summer like enjoying a warm vacation in a tropical place, bathed in sun away from your worries. The ocean is closer than you may think!
        Our recommended game for this season is:  </h4>
HEAD;
    }
    else if($sid == "halloween"){
        $theader = <<<HEAD
        <h2>Spooky month soon (Or now)!</h2>
        <h4>With halloween upon us, we begin to appreciate the spookier side of things. While most horror games are rated 18 (And for a good reason), so we don't have them on our site
        , we still have something that involves hiding in fear. Trick or Treating or not, put on your costume in our recommended seasonal game:  </h4>
HEAD;
    }
    else if($sid == "christmas"){
        $theader = <<<HEAD
        <h2>Ho Ho Ho!</h2>
        <h4>Christmas time is always right behind the corner, but since it's at least november already, it's time to bring out the decorations and the same set of 3 songs you'll
        hear in every single supermarket just trying to get your shopping done. Let us not forget what this time is truly about, family. This is why our recommended game of the season
        is targeted towards the littlest of us:  </h4>
HEAD;
    }
    
    $tcontent = <<<PAGE
        {$theader}
      {$tgameprofile}
      
      

    
      </div>
PAGE;
    return $tcontent;
}

// ----BUSINESS LOGIC---------------------------------
// Start up a PHP Session for this user.
session_start();

$tgames = [];
$sid = "";

//Get current month as an integer
$curmonth = idate("m");


//This line overwrites the current month value so that you may see all the versions of the page without changing the date. Uncomment and change the number to see different results
/////////////////
//$curmonth = 1;
/////////////////


if($curmonth < 5){
    //Easter
    $tid = 8;
    $sid = "easter";
}
else if($curmonth < 9){
    //Summer
    $tid = 2;
    $sid = "summer";
}
else if($curmonth < 11){
    //halloween
    $tid = 3;
    $sid = "halloween";
}
else{
    //Christmas
    $tid = 9;
    $sid = "christmas";
}
//Each season has its own game recommendation. Rather than loading the id from the url, it is set by the current month
if (is_numeric($tid) && $tid > 0) 
{
    $tgame = jsonLoadOneGame($tid);
    
    $tgames[] = $tgame;
    

} 

if (count($tgames)===0) 
{
    appGoToError();
} 
else
{
    //We've found our player
    $tpagecontent = createPage($tgames, $sid);
    $tpagetitle = "Season Page";

    // ----BUILD OUR HTML PAGE----------------------------
    // Create an instance of our Page class
    $tpage = new MasterPage($tpagetitle);
    $tpage->setDynamic2($tpagecontent);
    $tpage->renderPage();
}
?>