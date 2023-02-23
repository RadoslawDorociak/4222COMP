<?php
// ----INCLUDE APIS------------------------------------
include ("api/api.inc.php");

// ----PAGE GENERATION LOGIC---------------------------
function createPage($pgames)
{
    $tgameprofile = "";
    foreach($pgames as $tp)
    {
        $tgameprofile .= renderGameAsListItem($tp);
    }
    $tcontent = <<<PAGE
    <div class="gameBg gameList row">
<table class="table table-striped table-hover ">
  <thead>
    <tr>
      
      <th>Image</th>
      <th>Game Title</th>
      <th>Personal Score</th>
      
    </tr>
  </thead>
  <tbody>
      {$tgameprofile}
      </tbody>
      </table> 
      </div>
PAGE;
    return $tcontent;
}

// ----BUSINESS LOGIC---------------------------------
// Start up a PHP Session for this user.
session_start();

$tgames = [];

$tcurscore = 10;

//Handle our Requests and Search for games using different methods
$tgamelist = jsonLoadAllGame();
    foreach ($tgamelist as $tp)
    {
        
        foreach ($tgamelist as $tg){
            if($tg->score == $tcurscore) 
            $tgames[] = $tg;
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
    $tpagetitle = "List Page";

    // ----BUILD OUR HTML PAGE----------------------------
    // Create an instance of our Page class
    $tpage = new MasterPage($tpagetitle);
    $tpage->setDynamic2($tpagecontent);
    $tpage->renderPage();
}
?>