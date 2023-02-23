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
    <div class="gameBg gameList row">
      {$tgameprofile}
      </div>
PAGE;
    return $tcontent;
}

// ----BUSINESS LOGIC---------------------------------
// Start up a PHP Session for this user.
session_start();

$tgames = [];


//Handle our Requests and Search for games using different methods
$tgamelist = jsonLoadAllGame();
    foreach ($tgamelist as $tp)
    {
        
            $tgames[] = $tp;
        
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
    $tpagetitle = "game Page";

    // ----BUILD OUR HTML PAGE----------------------------
    // Create an instance of our Page class
    $tpage = new MasterPage($tpagetitle);
    $tpage->setDynamic2($tpagecontent);
    $tpage->renderPage();
}
?>