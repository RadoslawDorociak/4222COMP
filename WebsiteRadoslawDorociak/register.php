<?php
// ----INCLUDE APIS------------------------------------
include ("api/api.inc.php");

// ----PAGE GENERATION LOGIC---------------------------
function createFormPage()
{ 
	$tmethod = appFormMethod();
	$taction = appFormActionSelf();
    $tcontent = <<<PAGE
    <form class="form-horizontal" method="{$tmethod}" action="{$taction}">
	<fieldset>
		<!-- Form Name -->
		<legend>Create an Account</legend>

		<!-- Text input-->
		<div class="form-group">
			<label class="col-md-4 control-label" for="name">Name</label>
			<div class="col-md-4">
				<input id="name" name="name" type="text" placeholder="name"
					class="form-control input-md" required=""> <span class="help-block">Enter your own unique nickname!</span>
			</div>
		</div>

		<!-- Text input-->
		<div class="form-group">
			<label class="col-md-4 control-label" for="email">Email</label>
			<div class="col-md-4">
				<input id="email" name="email" type="email" placeholder="email"
					class="form-control input-md" required=""> <span class="help-block">Enter your Email address.</span>
			</div>
		</div>

		<!-- Text input-->
		<div class="form-group">
			<label class="col-md-4 control-label" for="password">Password</label>
			<div class="col-md-4">
				<input id="password" name="password" type="password" placeholder="password"
					class="form-control input-md" required=""> <span class="help-block">Enter your password</span>
			</div>
		</div>
        
		<!-- Text input-->
		<div class="form-group">
			<label class="col-md-4 control-label" for="rpassword">Repeat Password</label>
			<div class="col-md-4">
				<input id="rpassword" name="rpassword" type="password" placeholder="password"
					class="form-control input-md" required=""> <span class="help-block">Enter your password again</span>
			</div>
		</div>
        
        
		<div class="form-group">
			<label class="col-md-4 control-label" for="form-sub">Submit Form</label>
			<div class="col-md-4">
				<button id="form-sub" type="submit" class="btn btn-danger"> Add New 
			</button>
		</div>
		</div>
		</fieldset>
    </form>
PAGE;
    return $tcontent;
}

// ----BUSINESS LOGIC---------------------------------

session_start();
$tpagecontent = "";

if(appFormMethodIsPost())
{
    
    $tuser = new BLLUser(); 
	$tuser->name = appFormProcessData($_REQUEST["name"] ?? ""); 
	$tuser->email = appFormProcessData($_REQUEST["email"] ?? ""); 
	$tuser->password = appFormProcessData($_REQUEST["password"] ??""); 
	$rpass = appFormProcessData($_REQUEST["rpassword"] ??"");
    $tid = jsonNextUserID();
	$tuser->id = $tid;
	$tvalid = true;
	$terrortype = "";
    //TODO:  PUT SERVER-SIDE VALIDATION HERE
    $tuserlist = jsonLoadAllUser();
    foreach ($tuserlist as $tp)
    {
        if (strtolower($tp->name) === strtolower($tuser->name)) 
        {
            $tvalid = false;
			$terrortype = "This username is already taken!";
        }
		if (strtolower($tp->email) === strtolower($tuser->email)) 
        {
            $tvalid = false;
			$terrortype = "There is already an account made with that Email!";
        }
		
    }
	if($rpass != $tuser->password) {
		$terrortype = "Passwords don't match up!";
		$tvalid = false;
	}
	if($tvalid)
    {
        $tsavedata = json_encode($tuser).PHP_EOL;
		$tfilecontent = file_get_contents("data/json/users.json");
		$tfilecontent .= $tsavedata;
		file_put_contents("data/json/users.json",$tfilecontent);
		$tpagecontent = "<h1>User with ID = {$tuser->id} has been saved</h1>";
    } 
    else 
    {
        $tdest = appFormActionSelf();
        $tpagecontent = <<<ERROR
                         <div class="well">
                            <h1>{$terrortype}</h1>
                            <a class="btn btn-warning" href="{$tdest}">Try Again</a>
                         </div>
ERROR;
    }
}
else
{
    //This page will be created by default.
    $tpagecontent = createFormPage();
}
$tpagetitle = "Register Page";
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
    // Return the Dynamic Page to the user.
$tpage->renderPage();

?>