<?php

//Include our HTML Page Class
require_once("oo_page.inc.php");

class MasterPage
{
    //-------FIELD MEMBERS----------------------------------------
    private $_htmlpage;     //Holds our Custom Instance of an HTML Page
    private $_dynamic_1;    //Field Representing our Dynamic Content #1
    private $_dynamic_2;    //Field Representing our Dynamic Content #2
    private $_dynamic_3;    //Field Representing our Dynamic Content #3
    private $_game_ids;
    
    //-------CONSTRUCTORS-----------------------------------------
    function __construct($ptitle)
    {
        $this->_htmlpage = new HTMLPage($ptitle);
        $this->setPageDefaults();
        $this->setDynamicDefaults(); 
        $this->_game_ids = [1,2,3,4,5,6,7,8,9,10];
    }
    
    //-------GETTER/SETTER FUNCTIONS------------------------------
    public function getDynamic1() { return $this->_dynamic_1; }
    public function getDynamic2() { return $this->_dynamic_2; } 
    public function getDynamic3() { return $this->_dynamic_3; }
    public function setDynamic1($phtml) { $this->_dynamic_1 = $phtml; }
    public function setDynamic2($phtml) { $this->_dynamic_2 = $phtml; } 
    public function setDynamic3($phtml) { $this->_dynamic_3 = $phtml; }
    public function getPage(): HTMLPage { return $this->_htmlpage; } 
    
    //-------PUBLIC FUNCTIONS-------------------------------------
                   
    public function createPage()
    {
       //Create our Dynamic Injected Master Page
       $this->setMasterContent();
       //Return the HTML Page..
       return $this->_htmlpage->createPage();
    }
    
    public function renderPage()
    {
       //Create our Dynamic Injected Master Page
       $this->setMasterContent();
       //Echo the page immediately.
       $this->_htmlpage->renderPage();
    }
    
    public function addCSSFile($pcssfile)
    {
        $this->_htmlpage->addCSSFile($pcssfile);
    }
    
    public function addScriptFile($pjsfile)
    {
        $this->_htmlpage->addScriptFile($pjsfile);
    }
    
    //-------PRIVATE FUNCTIONS-----------------------------------    
    private function setPageDefaults()
    {
        $this->_htmlpage->setMediaDirectory("css","js","fonts","img","data");
        $this->addCSSFile("bootstrap.css");
        $this->addCSSFile("site.css");
        $this->addScriptFile("jquery-2.2.4.js");
        $this->addScriptFile("bootstrap.js");
        $this->addScriptFile("holder.js");        
    }
    
    private function setDynamicDefaults()
    {
        $tcurryear = date("Y");
        //Set the Three Dynamic Points to Empty By Default.
        $this->_dynamic_1 = <<<JUMBO
<h1>Welcome To The Play Zone</h1>
JUMBO;
        $this->_dynamic_2 = "";
        $this->_dynamic_3 = <<<FOOTER
<p>Website by Radoslaw Dorociak. Build with the help of tutorial 14/15 code by Dr Tom Hughes-Roberts - LJMU &copy; {$tcurryear}</p>
<p>All game images belong to their respective authors and have been taken from official game pages.</p>
FOOTER;
    }
    
    private function setMasterContent()
    {
    
        $tlogin = "app_entry.php"; 
        $tlogout = "app_exit.php"; 
        $tentryhtml = <<<FORM
        <form id="signin" action="{$tlogin}" method="post" class="navbar-form navbar-right" role="form"> 
        <div class="input-group">
        
        <input id="name" type="default" class="form-control limit-width" name="myname" value="" placeholder="name">
        <input id="pass" type="password" class="form-control limit-width" name="mypassword" value="" placeholder="password">
        <button type="submit" class="btn btn-primary"> Enter </button>
        </div>
        
        </form>

FORM;
$texithtml = "";
if(isset($_SESSION["myuser"])){
    $texithtml = <<<EXIT
    
    
    <a class = "btn btn-info navbar-right"
    href="{$tlogout}?action=exit">Log Out</a>
    <a class = "btn  navbar-right"
    href="profile.php?name={$_SESSION["myuser"]}">Logged in as {$_SESSION["myuser"]}</a>
    
EXIT;
}
        $tauth = "";
        $register = "";
        if(isset($_SESSION["myuser"])) 
        {
            $tauth = $texithtml; 
        }
        else
        {
            $tauth = $tentryhtml;
            $register = "<li role=\"presentation\"><a href=\"register.php\">Register</a></li>";
        }
        $tid = $this->_game_ids[array_rand($this->_game_ids,1)];        
        $tmasterpage = <<<MASTER
<div class="container">
	<div class="header clearfix">
		<nav>
		    {$tauth}
			<ul class="nav nav-pills pull-right">
				<li role="presentation"><a href="games.php">Games</a></li>
				<li role="presentation"><a href="toplist.php">Top List</a></li>
				<li role="presentation"><a href="consoleinfo.php">Console</a></li>
				<li role="presentation"><a href="game.php?id={$tid}">Random</a></li>
                <li role="presentation"><a href="seasonal.php">Seasonal</a></li>
                {$register}
			</ul>
			<h3 class="text-muted"><a href="index.php">Play Zone</a></h3>
		</nav>
	</div>
	<div class="jumbotron">
		{$this->_dynamic_1}
    </div>
	<div class="row details">
		{$this->_dynamic_2}
    </div>
    <footer class="footer">
		{$this->_dynamic_3}
	</footer>
</div>        
MASTER;
        $this->_htmlpage->setBodyContent($tmasterpage); 
    }
}

?>