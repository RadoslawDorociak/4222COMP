<?php 
//----INCLUDE APIS------------------------------------
include("api/api.inc.php");

//----PAGE GENERATION LOGIC---------------------------

function createPage()
{
    //Get the Data we need for this page
    $ttabs      = xmlLoadAll("data/xml/tabs-console.xml","PLTab","Tab");
    

    foreach($ttabs as $ttab){
        $ttab->content = file_get_contents("data/html/{$ttab->content_href}");
    }
    $ttabhtml = renderUITabs($ttabs,"console-content");
    
    //Construct the Page
$tcontent = <<<PAGE
<div class="sized-text">
PlayStation 5 first released on November 12, 2020. It's release may be considered a huge success, considering how quickly the initial stocks of the console dissapeared from online stores.
What makes it even more impressive is the daunting lack of games available on the console on launch date. While the selection still isn't impressive, more have been released over time.
</div>
<div class="sized-text">
But what about the specification, is it worth the price? Let's find out.
<ul>
<li>CPU: 3.5GHz, 8-core AMD Zen 2</li>
<li>GPU: 10.3 teraflop RDNA 2 GPU</li>
<li>RAM: 16GB GDDR6</li>
<li>Storage: Custom 825GB SSD</li>
</ul>
The hardware side of things is rather impressive. But is there more to it?
</div>
<section class="well sized-text">
    As you may know, there are two versions of the PS5 console available on the market. 
    Both digital and standard editions offer their own set of benefits, but which one is right for you? 
    Please take a look at the comparison below that will hopefully shed some light on the differences between the editions.
    </section>
    <section class="row details" id="Console-overview">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">Console Overview:</h3>
        </div>
        <div class="panel-body">
        {$ttabhtml}
        </div>
    </div>
    
    
    
</section>
     
PAGE;

return $tcontent;
}

//----BUSINESS LOGIC---------------------------------
//Start up a PHP Session for this user.
session_start();

//Build up our Dynamic Content Items. 
$tpagetitle = "Console Information";
$tpagelead  = "";
$tpagecontent = createPage();
$tpagefooter = "";

//----BUILD OUR HTML PAGE----------------------------
//Create an instance of our Page class
$tpage = new MasterPage($tpagetitle);
//Set the Three Dynamic Areas (1 and 3 have defaults)
if(!empty($tpagelead))
    $tpage->setDynamic1($tpagelead);
$tpage->setDynamic2($tpagecontent);
if(!empty($tpagefooter))
    $tpage->setDynamic3($tpagefooter);
//Return the Dynamic Page to the user.    
$tpage->renderPage();
?>