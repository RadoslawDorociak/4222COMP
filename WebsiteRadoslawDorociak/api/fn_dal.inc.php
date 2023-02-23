<?php
//Include the Other Layers Class Definitions
require_once("oo_bll.inc.php");
require_once("oo_pl.inc.php");

//---------JSON HELPER FUNCTIONS-------------------------------------------------------

function jsonOne($pfile,$pid)
{
    $tsplfile = new SplFileObject($pfile);
    $tsplfile->seek($pid-1);
    $tdata = json_decode($tsplfile->current());
    return $tdata;
}

function jsonAll($pfile)
{
    $tentries = file($pfile);
    $tarray = [];
    foreach($tentries as $tentry)
    {
        $tarray[] = json_decode($tentry);
    }
    return $tarray;
}

function jsonNextID($pfile)
{
    $tsplfile = new SplFileObject($pfile);
    $tsplfile->seek(PHP_INT_MAX);
    return $tsplfile->key() + 1;
}

//---------ID GENERATION FUNCTIONS-------------------------------------------------------

function jsonNextPlayerID()
{
    return jsonNextID("data/json/players.json");
}
function jsonNextUserID()
{
    return jsonNextID("data/json/users.json");
}
function jsonNextReviewID()
{
    return jsonNextID("data/json/reviews.json");
}
//---------JSON-DRIVEN OBJECT CREATION FUNCTIONS-----------------------------------------


function jsonLoadOneUser($pid) : BLLUser
{
    $tplayer = new BLLUser();
    $tplayer->fromArray(jsonOne("data/json/users.json",$pid));
    return $tplayer;
}

function jsonLoadOneGame($pid) : BLLGame
{
    $tgame = new BLLGame();
    $tgame->fromArray(jsonOne("data/json/games.json",$pid));
    return $tgame;
}



function jsonLoadOneCommercial($pid) : BLLCommercial
{
    $tcommercial = new BLLCommercial();
    $tcommercial->fromArray(jsonOne("data/json/commercials.json",$pid));
    return $tcommercial;
}
function jsonLoadOneReview($pid) : BLLReview
{
    $treview = new BLLReview();
    $treview->fromArray(jsonOne("data/json/reviews.json",$pid));
    return $treview;
}



//--------------MANY OBJECT IMPLEMENTATION--------------------------------------------------------


function jsonLoadAllReview() : array
{
    $tarray = jsonAll("data/json/reviews.json");
    return array_map(function($a){ $tc = new BLLReview(); $tc->fromArray($a); return $tc; },$tarray);
}
function jsonLoadAllUser() : array
{
    $tarray = jsonAll("data/json/users.json");
    return array_map(function($a){ $tc = new BLLUser(); $tc->fromArray($a); return $tc; },$tarray);
}
function jsonLoadAllCommercial() : array
{
    $tarray = jsonAll("data/json/commercials.json");
    return array_map(function($a){ $tc = new BLLCommercial(); $tc->fromArray($a); return $tc; },$tarray);
}
function jsonLoadAllGame() : array
{
    $tarray = jsonAll("data/json/games.json");
    return array_map(function($a){ $tc = new BLLGame(); $tc->fromArray($a); return $tc; },$tarray);
}

//---------XML HELPER FUNCTIONS--------------------------------------------------------

function xmlLoadAll($pxmlfile,$pclassname,$parrayname)
{
    $txmldata = simplexml_load_file($pxmlfile,$pclassname);
    $tarray = [];
    foreach($txmldata->{$parrayname} as $telement)
    {
        $tarray[] = $telement;
    }
    return $tarray;
}

function xmlLoadOne($pxmlfile,$pclassname)
{
    $txmldata = simplexml_load_file($pxmlfile,$pclassname);
    return $txmldata;
}

?>