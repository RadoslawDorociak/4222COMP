<?php
// ----INCLUDE APIS------------------------------------
include ("api/api.inc.php");

// ----PAGE GENERATION LOGIC---------------------------
function createFormPage($tgame)
{ 
	
	$tmethod = appFormMethod();
	$taction = appFormActionSelf();
    if(isset($_SESSION["myuser"])){
		$username = $_SESSION["myuser"];
		$maincontent =  <<<PAGE
		<form class="form-horizontal" method="{$tmethod}" action="{$taction}">
		<fieldset>
			<!-- Form Name -->
			<legend>Writing a review for {$tgame->title} as {$username}</legend>
			
			<div class="form-group">
				<label class="col-md-4 control-label" for="review">Write Review</label>
				<div class="col-md-4">
					<textarea class="form-control" id="rev" name="rev"></textarea>
					<span class="help-block">Enter your review in this field.</span>
				</div>
			</div>
			
		<!-- Select Basic -->
		<div class="form-group">
			<label class="col-md-4 control-label" for="scr">Score</label>
			<div class="col-md-4">
				<select id="score" name="score" class="form-control">
					<option value="1">1</option>
					<option value="2">2</option>
					<option value="3">3</option>
					<option value="4">4</option>
					<option value="5">5</option>
					
				</select>
                <span class="help-block">Select your rating.</span>
			</div>
		</div>

			<div class="form-group">
				<label class="col-md-4 control-label" for="form-sub">Press to submit</label>
				<div class="col-md-4">
					<button id="form-sub" type="submit" class="btn btn-danger"> Submit
				</button>
			</div>
			</div>
			</fieldset>
			
		</form>
PAGE;
	}
	else{
		$maincontent =  <<<PAGE
		<h1> Log in to review!</h1>
	PAGE;
	}
    return $maincontent;
}

// ----BUSINESS LOGIC---------------------------------

session_start();
$maincontent = "";
$username = "";

$tpagecontent = $maincontent;
$gametitle = "";
$tid = $_REQUEST["id"] ?? -1;

//Handle our Requests and Search for games using different methods
if (is_numeric($tid) && $tid > 0) 
{
    $tgame = jsonLoadOneGame($tid);
	$gametitle = $tgame->title;
	$_SESSION["curgame"] = $gametitle;
} 
else
{
	$tgame = jsonLoadOneGame(1);
	
    
} 

if(appFormMethodIsPost())
{
    
    $treview = new BLLReview(); 
	$treview->name = $_SESSION["myuser"]; 
	$treview->game = $_SESSION["curgame"]; 
	$treview->review = appFormProcessData($_REQUEST["rev"] ??""); 
	$treview->score = intval(appFormProcessData($_REQUEST["score"] ??""));
    $tid = jsonNextreviewID();
	$treview->id = $tid;
	$tvalid = true;
   
	
    if($tvalid)
    {
        $tsavedata = json_encode($treview).PHP_EOL;
		$tfilecontent = file_get_contents("data/json/reviews.json");
		$tfilecontent .= $tsavedata;
		file_put_contents("data/json/reviews.json",$tfilecontent);
		$tpagecontent = "<h1>Review for {$treview->game} has been submitted</h1>";
		
    } 
    else 
    {
        $tdest = appFormActionSelf();
        $tpagecontent = <<<ERROR
                         <div class="well">
                            <h1>Form was Invalid</h1>
                            <a class="btn btn-warning" href="{$tdest}">Try Again</a>
                         </div>
ERROR;
    }
}
else
{
    //This page will be created by default.
    $tpagecontent = createFormPage($tgame);
}
$tpagetitle = "Review Entry Page";
$tpagelead = "";
$tpagefooter = "";

// ----BUILD OUR HTML PAGE----------------------------
// Create an instance of our Page class
$tpage = new MasterPage($tpagetitle);
// Set the Three Dynamic Areas (1 and 3 have defaults)
if (! empty($tpagelead))
    $tpage->setDynamic1($tpagelead);
$tpage->setDynamic2($tpagecontent);
if (! empty($tpagefooter))
    $tpage->setDynamic3($tpagefooter);
    // Return the Dynamic Page to the review.
$tpage->renderPage();

?>