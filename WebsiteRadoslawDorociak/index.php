<?php
// ----INCLUDE APIS------------------------------------
include ("api/api.inc.php");

// ----PAGE GENERATION LOGIC---------------------------
function createPage($pgames)
{
    $tgameprofile = "";
    foreach($pgames as $tp)
    {
        $tgameprofile .= renderGameAsPanel($tp);
    }
    $tcontent = <<<PAGE
    <h2> See our <a href="toplist.php">top rated</a> games! </h2>
    <div class="gameBg gameList row">
    
      {$tgameprofile}
      </div>
      <h2> Explore our <a href="consoleinfo.php">Console Info</a> page! </h2>
      <h2> ...or visit <a href="https://www.playstation.com/en-us/ps5/">The official PlayStation 5 Website</a> for even more info! </h2>
PAGE;
    return $tcontent;
}

// ----BUSINESS LOGIC---------------------------------
// Start up a PHP Session for this user.
session_start();

$tgames = [];
$tcurscore = 10;
$tcounter = 0;
//Handle our Requests and Search for games using different methods
$tgamelist = jsonLoadAllGame();
foreach ($tgamelist as $tp)
{
    
    foreach ($tgamelist as $tg){
        if($tg->score == $tcurscore && $tcounter < 3){
            $tgames[] = $tg;
            $tcounter ++;
        } 
        
    }
        
    $tcurscore --;
}


//Page Decision Logic - Have we found a game?  
//Doesn't matter the route of finding them
if (count($tgames)===0) 
{
    appGoToError();
} 
else
{
    //We've found our game
    $tpagecontent = createPage($tgames);
    $tpagetitle = "Index Page";

    // ----BUILD OUR HTML PAGE----------------------------
    // Create an instance of our Page class
    $tpage = new MasterPage($tpagetitle);
    $tpage->setDynamic2($tpagecontent);
    $tpage->renderPage();
}
?>