<?php
require_once ("oo_bll.inc.php");
require_once ("oo_pl.inc.php");

//===========RENDER BUSINESS LOGIC OBJECTS=======================================================================

// ----------NEWS ITEM RENDERING------------------------------------------
function renderNewsItemAsList(BLLNewsItem $pitem)
{
    $titemsrc = !empty($pitem->thumb_href) ? $pitem->thumb_href : "blank-thumb.jpg";
    $tnewsitem = <<<NI
		    <section class="list-group-item clearfix">
		        <div class="media-left media-top">
                    <img src="img/news/{$titemsrc}" width="100" />
                </div>
                <div class="media-body">
				<h4 class="list-group-item-heading">{$pitem->heading}</h4>
				<p class="list-group-item-text">{$pitem->tagline}</p>
				<a class="btn btn-xs btn-default" href="news.php?storyid={$pitem->id}">Read...</a>
				</div>
			</section>
NI;
    return $tnewsitem;
}

function renderNewsItemAsSummary(BLLNewsItem $pitem)
{
    $titemsrc = !empty($pitem->thumb_href) ? $pitem->thumb_href : "blank-thumb.jpg";
    $tnewsitem = <<<NI
		    <section class="row details clearfix">
		    <div class="media-left media-top">
				<img src="img/news/{$titemsrc}" width="256" />
			</div>
			<div class="media-body">
				<h2>{$pitem->heading}</h2>
				
				<div class="ni-summary">
				<p>{$pitem->summary}</p>
				</div>
				<a class="btn btn-xs btn-default" href="news.php?storyid={$pitem->id}">Get the Full Story</a>
	        </div>
			</section>
NI;
    return $tnewsitem;
}

function renderNewsItemFull(BLLNewsItem $pitem)
{
    $titemsrc = !empty($pitem->img_href) ? $pitem->img_href : "blank-thumb.jpg";
    $tnewsitem = <<<NI
		    <section class="row details">
		        <div class="well">
		        <div class="media-left">
				    <img src="img/news/{$titemsrc}" />
				</div>	
				<div class="media-body">
				    <h1>{$pitem->heading}</h1>
				    <p id="news-tag">{$pitem->tagline}</p>
				    <p id="news-summ">{$pitem->summary}</p>
				    <p id="news-main">{$pitem->content}</p>
				</div>
				</div>
			</section>
NI;
    return $tnewsitem;
}


// ----------COACH RENDERING----------------------------------------------
function renderCoachingTable(array $pcoachlist)
{
    $trowdata = "";
    foreach ($pcoachlist as $tc) {
        $tlink = "<a class=\"btn btn-info\" href=\"staff.php?type=coach&id={$tc->id}\">More...</a>";
        $trowdata .= "<tr><td>{$tc->fname} {$tc->lname}</td><td>{$tc->role}</td><td>{$tlink}</td></tr>";
    }
    $ttable = <<<TABLE
<table class="table table-striped table-hover">
	<thead>
		<tr>
	       	<th>Name</th>
			<th>Role</th>
			<th> </th>
		</tr>
	</thead>
	<tbody>
	   {$trowdata}
	</tbody>
</table>
TABLE;
	   return $ttable;
}

function renderCoachOverview(BLLCoaching $pc)
{
    $tbio = !empty($pc->bio_href) ? file_get_contents("data/html/{$pc->bio_href}") : "There are no details on this staff member.";
    $toverview = <<<OV
    <article class="row marketing">
        <h2>Coaching Staff Details</h2>
        <div class="well">
            <h1>{$pc->fname} {$pc->lname}</h1>
            <h3>Role: <strong>{$pc->role}</strong></h3>
            <div class="col">
                {$tbio}
            </div>
        </div>
    </article>
OV;
    return $toverview;
}

// ----------MANAGER RENDERING--------------------------------------------
function renderManagerTable(BLLManager $pm)
{
    $ttable = <<<TABLE
<table class="table table-striped table-hover">
	<thead>
		<tr>
			<th>Name</th>
			<th>Role</th>
			<th>    </th>
			<th>    </th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>{$pm->fname} {$pm->lname}</td>
			<td>First Team Manager</td>
			<td>     </td>
			<td><a class="btn btn-info" href="staff.php?type=manager&id={$pm->id}">More...</a></td>
		</tr>
	</tbody>
</table>
TABLE;
   return $ttable;
}

function renderManagerOverview(BLLManager $pm)
{
    $turl = "img/staff/manager-{$pm->id}.jpg";
    $timg = file_exists($turl) ? "<img src=\"$turl\" width=\"512\" />" : "";
    $thonourslist = "<ul>";
    foreach ($pm->honours as $th) {
        $thonourslist .= "<li>{$th}</li>";
    }
    $thonourslist .= "</ul>";
    
    $tmanhtml = <<<OVERVIEW
    <section class="well">
        <div class="row">
            <div class="col-md-7">
            {$timg}
            </div>
            <div class="col-md-5">
                <h1>{$pm->fname} {$pm->lname}</h1>
            </div>
        </div>
    </section>
    <section class="panel">
    <div class="panel-body">
        <h3>Biography</h3>
       {$pm->bio}
    </div>
    </section>
    <section class="panel">
        <div class="panel-body">
        <h3>Club Honours</h3> 
        {$thonourslist}
        </div>
    </section>
    <section class="row">
        <h3>Management Statistics</h3>
        <ul class="list-group">
        <li class="list-group-item">
        Nationality: <strong>{$pm->nationality}</strong>
        </li>
        <li class="list-group-item">
        <span class="badge">{$pm->games_managed}</span>
        Games Managed
        </li>
        <li class="list-group-item">
        <span class="badge">{$pm->games_won}</span>
        Games Won
        </li>
        <li class="list-group-item">
        <span class="badge">{$pm->games_drawn}</span>
        Games Drawn
        </li>
        <li class="list-group-item">
        <span class="badge">{$pm->games_lost}</span>
        Games Lost
        </li>
        </ul>
    </section>
OVERVIEW;
    return $tmanhtml;
}

// ----------SQUAD/PLAYER RENDERING---------------------------------------
function renderPlayerTable(BLLSquad $psquad)
{
    $trowdata = "";
    foreach ($psquad->squadlist as $tp) {
        $tformat = $psquad->captainindex == $tp->squadno ? " class=\"success\"" : "";
        if (empty($tformat))
            $tformat = $psquad->starplayerindex == $tp->squadno ? " class=\"danger\"" : "";
            $trowdata .= <<<ROW
<tr{$tformat}>
   <td>{$tp->squadno}</td>
   <td>{$tp->position}</td>
   <td>{$tp->firstname} {$tp->lastname}</td>
   <td>{$tp->nationality}</td>
   <td><a class="btn btn-info" href="player.php?id={$tp->id}">More...</a></td>
</tr>
ROW;
    }
    $ttable = <<<TABLE
<table class="table table-striped table-hover">
	<thead>
		<tr>
			<th id="sort-sqno">#</th>
			<th id="sort-pos">Position</th>
			<th id="sort-name">Name</th>
			<th id="sort-nat">Nationality</th>
			<th> </th>
		</tr>
	</thead>
	<tbody>
	{$trowdata}
	</tbody>
</table>
TABLE;
	return $ttable;
}

function renderPlayerOverview(BLLPlayer $pp)
{
    $timgref = "img/player/{$pp->squadno}.jpg";
    $timg = file_exists($timgref) ? $timgref : "img/player/blank.jpg";
   
    $toverview = <<<OV
    <article class="row marketing">
        <h2>Player Details</h2>
        <div class="media-left">
            <img src="$timg" width="256" />
        </div>
        <div class="media-body">
            <div class="well">
                <h1>{$pp->firstname} {$pp->lastname}</h1>
            </div>
            <h2>Squad Number: {$pp->squadno}</h2>
            <h3>Position: {$pp->position}</h3>
            <h3>Nationality: {$pp->nationality}</h3>
        </div>
 
    </article>
OV;
    return $toverview;
}
function renderGameOverview(BLLGame $pg)
{
    $timgref = "img/game/{$pg->id}.jpg";
    $timg = file_exists($timgref) ? $timgref : "img/game/blank.jpg";
   
    $toverview = <<<OV
    <article class="row marketing">
        <h2>Game Details</h2>
        <div class="media-left">
            <img src="$timg" width="256" />
        </div>
        <div class="media-body">
            <div class="well">
                <h1>{$pg->title}</h1>
            </div>
            <h2>Release Date: {$pg->reldate}</h2>
            <h2>Score: {$pg->score}/10</h2>
            <h3>Review: </h3><div class="sized-text">{$pg->description}</div>
            <h3>Platforms: {$pg->platforms}</h3>
            <h3>Published by: {$pg->publisher}</h3>
            <h3>External Reviews:</h3> <h3><a href="{$pg->extrev1link}"> {$pg->extrev1name} </a></h3>
            <h3><a href="{$pg->extrev2link}"> {$pg->extrev2name} </a></h3>
            <h3><a href="{$pg->extrev2link}"> {$pg->extrev3name} </a></h3>
        </div>
 
    </article>
OV;
    return $toverview;
}
function renderSeasonalGame(BLLGame $pg)
{
    $timgref = "img/game/{$pg->id}.jpg";
    $timg = file_exists($timgref) ? $timgref : "img/game/blank.jpg";
   
    $toverview = <<<OV
    <article class="row marketing">
        
        <div class="media-left">
            <img src="$timg" width="256" />
        </div>
        <div class="media-body">
            <div class="well">
                <h1><a href="game.php?id={$pg->id}">$pg->title </a></h1>
            </div>
            <h2>Score: {$pg->score}/10</h2>
            
        </div>
 
    </article>
OV;
    return $toverview;
}
function renderUserOverview(BLLUser $pg)
{
    
   
    $toverview = <<<OV
    <article class="row marketing">
        <h2>User Profile</h2>
        
        <div class="media-body">
            <div class="well">
            <h4>Username:</h4>
                <h1>{$pg->name}</h1>
                <h4>Email:</h4>
                <h2>  {$pg->email} </h2>
            </div>
            
            
        </div>
 
    </article>
OV;
    return $toverview;
}
function renderGameAsPanel(BLLGame $pg)
{
    $timgref = "img/game/{$pg->id}.jpg";
    $timg = file_exists($timgref) ? $timgref : "img/game/blank.jpg";
   
    $tpanel = <<<OV
    <div class="panel panel-default gameOnList">
    
      <div class="panel-body commentBody gamePic">
      <img src="$timg" width="300" />
    </div>
      <div class="panel-footer ">
        <div><h3><a href="game.php?id={$pg->id}">$pg->title </a></h3></div><div class="font-bold"> </div>
        <p class="sized-text">$pg->shrtdescription</p>
      </div>
  </div>
    
OV;
    return $tpanel;
}
function renderGameAsListItem(BLLGame $pg)
{
    $timgref = "img/game/{$pg->id}.jpg";
    $timg = file_exists($timgref) ? $timgref : "img/game/blank.jpg";
   
    $tpanel = <<<OV
    <tr>
            
            <td><a href="game.php?id={$pg->id}"><img src="$timg" class="gameMiniature"></a></td>
            <td><h5><a href="game.php?id={$pg->id}">$pg->title </a></h5></td>
            <td><h5>$pg->score/10</h5></td>
            
    </tr>
    
    
OV;
    return $tpanel;
}
function renderReviewAsPanel(BLLReview $pg)
{

    $tpanel = <<<OV
    <div class="panel panel-default comment">
    <div class="panel-heading commentHead">
      By:&nbsp;<a href="profile.php?name={$pg->name}"> $pg->name </a><br>Rated $pg->score/5
    </div>
    <div class="panel-body commentBody">
    $pg->review
    
    </div>
  </div>
    
    
OV;
    return $tpanel;
}
function renderProfileReview(BLLReview $pg)
{

    $tpanel = <<<OV
    <div class="panel panel-default comment">
    <div class="panel-heading commentHead">
      Game:  $pg->game  <br> Rated $pg->score/5
    </div>
    <div class="panel-body commentBody">
    $pg->review
    
    </div>
  </div>
    
    
OV;
    return $tpanel;
}
function renderCommercialSection(BLLCommercial $pc)
{
    $toverview = <<<OV
    <article class="row marketing">
    <div class="well">
        <h2>You can buy it at:</h2>
    </div>
        <div class="media-body">
            <h3><a href="{$pc->link1}"> {$pc->name1} </a> for £{$pc->price1}&nbsp;&nbsp;&nbsp;&nbsp;     
            <a href="{$pc->link2}"> {$pc->name2} </a> for £{$pc->price2}&nbsp;&nbsp;&nbsp;&nbsp;       
            <a href="{$pc->link3}"> {$pc->name3} </a> for £{$pc->price3}</h3>
        </div>
 
    </article>
OV;
    return $toverview;
}

// ----------EXECUTIVE RENDERING------------------------------------------
function renderExecutiveTable(array $pmanlist)
{
    $trowdata = "";
    foreach ($pmanlist as $tman) {
        $tlink = "<a class=\"btn btn-info\" href=\"staff.php?type=exec&id={$tman->id}\">More...</a>";
        $trowdata .= <<<ROW
            <tr>
    		    <td>{$tman->name}</td>
    		    <td>{$tman->role}</td>
    		    <td>{$tlink}</td>
			</tr>
ROW;
    }
    $ttable = <<<TABLE
<table class="table table-striped table-hover">
					<thead>
						<tr>
							<th>Name</th>
							<th>Role</th>
							<th> </th>
						</tr>
					</thead>
					<tbody>
					{$trowdata}
					</tbody>
</table>
TABLE;
    return $ttable;
}

function renderExecutiveOverview(BLLExecutive $pe)
{
    $toverview = <<<OV
    <article class="row marketing">
        <h2>Executive Staff Details</h2>
        <div class="well">
            <h1>{$pe->name}</h1>
            <h3>Role: <strong>{$pe->role}</h3>
        </div>
    </article>
OV;
    return $toverview;
}

// ----------FIXTURE RENDERING--------------------------------------------

function renderFixtureSummary(array $pflist)
{
    $tfixhtml = "";
    foreach($pflist as $pf)
    {
    $tfixture = <<<HTML
    <div class="row details">
     	<section class="panel panel-primary">
			<div class="panel-body">
				<h3>
					<img width="24" src="img/clubs/fcb.png"><abbr title="FC Barcelona">Barcelona</abbr>
					<span class="info">{$pf->goalsfor}</span>
				</h3>
        		<h3>
					<img width="24"	src="img/clubs/{$pf->opp_abbr}.png"><abbr title="{$pf->opp_full}">{$pf->opp_abbr}</abbr>
					<span class="info">{$pf->goalsagainst}</span>
				</h3>
				<p><strong>Barcelona vs {$pf->opp_full}</strong></p>
				<a class="btn btn-primary" href="fixtures.php?fixid={$pf->id}">Get Full Match Report</a>
			</div>
		</section>
	</div>
HTML;
	$tfixhtml.=$tfixture;
    }
    return $tfixhtml;
}


function renderFixtureDetails(BLLFixture $pf, $ptitle, $pid = "club-results")
{
    $treport = !empty($pf->report) ? $pf->report : "Fixture report to follow";
    
    $tfixture = <<<HTML
        <section>
				<h2>
					<img width="24" src="img/clubs/fcb.png"><abbr title="FC Barcelona">Barcelona</abbr>
					<span class="info">{$pf->goalsfor}</span>
				</h2>
        		<h2>
					<img width="24"	src="img/clubs/{$pf->opp_abbr}.png"><abbr title="{$pf->opp_full}">{$pf->opp_abbr}</abbr>
					<span class="info">{$pf->goalsagainst}</span>
				</h2>
				<p><strong>Barcelona vs {$pf->opp_full}</strong></p>
				<p>{$pf->competition}</p>
				<p class="text-success>{$pf->date} {$pf->kickoff}</p>
				<p class="text-danger">{$pf->venue} (Att: {$pf->attendance})</p>
				<section class="well">
				{$treport}
				</section>
			</div>
		</section>
HTML;
    return $tfixture;
}

// ----------KIT RENDERING------------------------------------------------
function renderKitTable(array $pkitlist)
{
    $trowdata = "";
    foreach ($pkitlist as $tk) 
    {
        $tlink = "<a class=\"btn btn-info\" href=\"kit.php?kitid={$tk->id}\">More...</a>";
        $trowdata .= "<tr>
                          <td>{$tk->kittype}</td>
                          <td>{$tk->kityear}</td>
                          <td>{$tk->manufacturer}</td>
                          <td>{$tlink}</td>
                      </tr>";
    }
    $ttable = <<<TABLE
<table class="table table-striped table-hover">
	<thead>
		<tr>
	       	<th>Kit Desc</th>
			<th>Kit Year</th>
			<th>Kit Manufacturer</th>
			<th> </th>
		</tr>
	</thead>
	<tbody>
	   {$trowdata}
	</tbody>
</table>
TABLE;
    return $ttable;
}

function renderKitOverview(BLLKit $pkit)
{
    $tkithtml = <<<OV
    <h2>Kit Details</h2>
    <img src="img/kits/{$pkit->kitimage_href}" width="512"/>
    <h1>{$pkit->kittype} {$pkit->kityear}</h1>
    <h3>Sponsor: <strong>{$pkit->sponsor}</strong></h3>
    <h3>Manufacturer: <strong>{$pkit->manufacturer}</strong>
    <div class="col">
        <ul>
        <li>Shirt: <strong>{$pkit->shirtdesc}</strong></li>
        <li>Shorts:<strong>{$pkit->shortsdesc}</strong> </li>
        <li>Socks: <strong>{$pkit->socksdesc}</strong></li>
        </ul>
    </div>
OV;
    return $tkithtml;
}

// ----------STADIUM RENDERING--------------------------------------------
function renderStadiumSummary(BLLStadium $ps)
{
   $tshtml = <<<OVERVIEW
    <div class="well">
            <ul class="list-group">
                <li class="list-group-item">
                    Stadium Name: <strong>{$ps->name}</strong>
                </li>
                <li class="list-group-item">
                    Capacity: <strong>{$ps->capacity}</strong>
                </li>
                <li class="list-group-item">
                    Location: <strong>{$ps->addr}</strong>
                </li>
            </ul>
            <a class="btn btn-info" href="stadium.php?id={$ps->id}">Find out more...</a>
    </div>
OVERVIEW;
   return $tshtml;
}

function renderStadiumOverview(BLLStadium $ps)
{
    $tdesc = empty($ps->desc) ? "Details to Follow" : $ps->desc;
    $tci = [];
    $turl = "img/stadium/{$ps->imgdir}";
    //Get the Images
    foreach (new DirectoryIterator($turl) as $tfi) 
    {
        if($tfi->isDot())
            continue;
            $txml = <<<XML
<Image>
<img_href>{$tfi->getFilename()}</img_href>
<title> </title>
<lead> </lead>
</Image>
XML;
        $titem = new PLCarouselImage($txml);
        $tci[] = $titem;
    }
    $tcarousel = renderUICarousel($tci,$turl,"stad-carousel");
    $tmap = renderUIGoogleMap($ps->long,$ps->lat);
    
    $tshtml = <<<OVERVIEW
<div class="row">
  <h1>{$ps->name}</h1>
  <h3>Capacity: <strong>{$ps->capacity}</strong></h3>
  <h3>Location: <strong>{$ps->addr}</strong></h3>
  <h3>Stadium Overview</h3>
  {$tdesc}
</div>
<div class="row details">
  {$tcarousel}
</div>
<div class="row details">
<div id="stad-map">
{$tmap}
</div>
</div>
OVERVIEW;
    return $tshtml;
}

//=============RENDER PRESENTATION LOGIC OBJECTS==================================================================
function renderUICarousel(array $pimgs, $pimgdir,$pid = "mycarousel")
{
    $tci = "";
    $count = 0;
    
    // -------Build the Images---------------------------------------------------------
    foreach ($pimgs as $titem) {
        $tactive = $count === 0 ? " active" : "";
        $thtml = <<<ITEM
        <div class="item{$tactive}">
            <img class="img-responsive" src="{$pimgdir}/{$titem->img_href}">
            <div class="container">
                <div class="carousel-caption">
                    <h1>{$titem->title}</h1>
                    <p class="lead">{$titem->lead}</p>
		        </div>
			</div>
	    </div>
ITEM;
        $tci .= $thtml;
        $count ++;
    }
    
    // --Build Navigation-------------------------
    $tdot = "";
    $tdotset = "";
    $tarrows = "";
    
    if ($count > 1) {
        for ($i = 0; $i < count($pimgs); $i ++) {
            if ($i === 0)
                $tdot .= "<li data-target=\"#{$pid}\" data-slide-to=\"$i\" class=\"active\"></li>";
            else
                $tdot .= "<li data-target=\"#{$pid}\" data-slide-to=\"$i\"></li>";
        }
        $tdotset = <<<INDICATOR
        <ol class="carousel-indicators">
        {$tdot}
        </ol>
INDICATOR;
    }
    if ($count > 1) {
        $tarrows = <<<ARROWS
		<a class="left carousel-control" href="#{$pid}" data-slide="prev"><span class="glyphicon glyphicon-chevron-left"></span></a>
		<a class="right carousel-control" href="#{$pid}" data-slide="next"> <span class="glyphicon glyphicon-chevron-right"></span></a>
ARROWS;
    }
    
    $tcarousel = <<<CAROUSEL
    <div class="carousel slide" id="{$pid}">
            {$tdotset}
			<div class="carousel-inner">
				{$tci}
			</div>
		    {$tarrows}
    </div>
CAROUSEL;
    return $tcarousel;
}

function renderUITabs(array $ptabs, $ptabid)
{
    $count = 0;
    $ttabnav = "";
    $ttabcontent = "";
    
    foreach ($ptabs as $ttab) {
        $tnavactive = "";
        $ttabactive = "";
        if ($count === 0) {
            $tnavactive = " class=\"active\"";
            $ttabactive = " active in";
        }
        $ttabnav .= "<li{$tnavactive}><a href=\"#{$ttab->tabid}\" data-toggle=\"tab\">{$ttab->tabname}</a></li>";
        $ttabcontent .= "<article class=\"tab-pane fade{$ttabactive}\" id=\"{$ttab->tabid}\">{$ttab->content}</article>";
        $count ++;
    }
    
    $ttabhtml = <<<HTML
        <ul class="nav nav-tabs">
        {$ttabnav}
        </ul>
    	<div class="tab-content" id="{$ptabid}">
			  {$ttabcontent}
		</div>
HTML;
    return $ttabhtml;
}

function renderUIQuote(PLQuote $pquote)
{
    $tquote = <<<QUOTE
    <blockquote>
    {$pquote->quote}
    <small>{$pquote->person} in <cite title="{$pquote->source}">{$pquote->pub}</cite></small>
	</blockquote>
QUOTE;
    return $tquote;
}

function renderUIHomeArticle(PLHomeArticle $phome, $pwidth = 6)
{
    $thome = <<<HOME
    <article class="col-lg-{$pwidth}">
		<h2>{$phome->heading}</h2>
		<h4>
			<span class="label label-success">{$phome->tagline}</span>
		</h4>
		<div class="home-thumb">
			<img src="img/{$phome->storyimg_href}" />
		</div>
		<div>
		  <strong>
			{$phome->summary}
		  </strong>
		</div>
        <div>
		    {$phome->content}
        </div>
        <div class="options details">
			<a class="btn btn-info" href="{$phome->link}">{$phome->linktitle}</a>
        </div>
	</article>
HOME;
    return $thome;
}

function renderUIKeyPlayersList(array $pkeyplayers)
{
    $tkeylist = "";
    foreach ($pkeyplayers as $tkey) {
        $tli = <<<LI
        <section class="list-group-item">
            <h4 class="list-group-item-heading">
                <a href="player.php?name={$tkey->key_href}">{$tkey->name}</a>
            </h4>
            <p class="list-group-item-text">{$tkey->desc}</p>
        </section>            
LI;
        $tkeylist .= $tli;
    }
    
    $tpanel = <<<PANEL
    <div class="panel panel-default">
        <div class="panel-heading">Key Players</div>
        <div class="panel-body">
        <div class="list-group">
        {$tkeylist}   
        </div>        
PANEL;
    return $tpanel;
}

function renderUIStatistics(array $pstats)
{
    $tstats = "";
    foreach ($pstats as $tstat) {
        $tstats .= <<<STAT
        <li class="list-group-item">
            <span class="badge">{$tstat->value}</span>
            <strong>{$tstat->stat}: </strong>
            <a href="player.php?name={$tstat->ref}">{$tstat->holder}</a>
        </li>
STAT;
    }
    
    $tpanel = <<<PANEL
    <div class="well well-lg">
        <ul class="list-group">
            {$tstats}
        </ul>
    </div>

PANEL;
    return $tpanel;
}

function renderPagination($ppage,$pno,$pcurr)
{
    if($pno <= 1)
        return "";
        
        $titems = "";
        $tld= $pcurr == 1 ? " class=\"disabled\"" : "";
        $trd = $pcurr == $pno ? " class=\"disabled\"" : "";
        
        $tprev = $pcurr - 1;
        $tnext = $pcurr + 1;
        
        $titems .= "<li$tld><a href=\"{$ppage}?page={$tprev}\">&laquo;</a></li>";
        for($i = 1; $i <=$pno; $i++)
        {
            $ta = $pcurr == $i? " class=\"active\"" : "";
            $titems .= "<li$ta><a href=\"{$ppage}?page={$i}\">{$i}</a></li>";
        }
        $titems .= "<li$trd><a href=\"${ppage}?page={$tnext}\">&raquo;</a></li>";
        
        $tmarkup = <<<NAV
    <ul class="pagination pagination-sm">
        {$titems}
    </ul>
NAV;
        return $tmarkup;
}

function renderUIGoogleMap($plong, $plat)
{
    $tmaphtml = <<<MAP
    <iframe width="100%" height="100%"
                        frameborder="1" scrolling="no" marginheight="0"
                        marginwidth="0"
                        src="http://maps.google.com/maps?f=q&amp;
                        source=s_q&amp;hl=en&amp;
                        geocode=&amp;q={$plong},{$plat}
                        &amp;output=embed"></iframe>
MAP;
    return $tmaphtml;
}

?>