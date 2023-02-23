<?php

//Lightweight Class to create an Object-Oriented
//Representation of an HTML Page.
class HTMLPage
{
    //----CLASS MEMBERS-------------------------
    private $_dir_css   = "";   //Store the CSS Directory
    private $_dir_js    = "";   //Store the JavaScript Directory
    private $_dir_img   = "";   //Store the Images Directory
    private $_dir_fonts = "";   //Store the Web Fonts Directory
    private $_dir_data  = "";   //Store the Data Directory
    
    private $_arr_js    = [];   //Store an Array of Scripts
    private $_arr_css   = [];   //Store an Array of CSS Files
    private $_arr_meta  = [];   //Store an Associative Array of Meta Files 
    
    private $_head_title     = "";     //Store the Page Title
    private $_head_otherhtml = ""; //Store any other HTML in the Head
    private $_head_favicon   = "";   //Store the Favicon.
    
    private $_body_content   = "";   //Store the Body Content
  
    //-----CONSTRUCTORS-------------------------
    function __construct($ptitle)
    {
        $this->_head_title = $ptitle;
    }
    
    //-----GETTERS/SETTERS-----------------------
    
    //-----SETTERS---------------
    public function addScriptFile($pscriptfile)
    {
        $this->_arr_js[] = $pscriptfile;
    }
    
    public function addCSSFile($pcssfile)
    {
        $this->_arr_css[] = $pcssfile;
    }
    
    public function addMetaElement($pmetakey,$pmetavalue)
    {
        $this->_arr_meta[$pmetakey] = $pmetavalue;
    }
    
    public function setPageTitle($ptitle)
    {
        $this->_head_title = $ptitle;
    }
    
    public function setCustomHead($pheadhtml)
    {
        $this->_head_otherhtml = $pheadhtml;
    }
    
    public function setDirCSS($pcsspath)
    {
        $this->_dir_css = $pcsspath;
    }
    
    public function setDirJS($pjspath)
    {
        $this->_dir_js = $pjspath;
    }
    
    public function setDirImages($pimgpath)
    {
        $this->_dir_img = $pimgpath;
    }
    
    public function setDirFonts($pfontpath)
    {
        $this->_dir_fonts = $pfontpath;
    }
    
    public function setDirData($pdatapath)
    {
        $this->_dir_data = $pdatapath;
    }
    
    public function setBodyContent($pbodycontent)
    {
        $this->_body_content = $pbodycontent;
    }
    
    public function setFavIcon($piconfile)
    {
        $this->_head_favicon = $piconfile;
    }
    
    //------GETTERS-------------    
    public function getScriptFileArray($pwithpath = false)
    {
        if($pwithpath)
        {
            return $this->toURLs($this->_arr_js,$this->_dir_js);
        }
        //If we get to here, then $pwithpath must be false
        return $this->_arr_js;
    }
    
    public function getCSSFileArray($pwithpath = false)
    {
        if($pwithpath)
        {
            return $this->toURLs($this->_arr_css,$this->_dir_css);
        }
        //If we get to here, then $pwithpath must be false
        return $this->_arr_css;
    }
    
    public function getPageTitle()
    {
        return $this->_head_title;
    }
    
    public function getDirCSS()
    {
        return $this->_dir_css;
    }
    
    public function getDirJS()
    {
        return $this->_dir_js;
    }
    
    public function getDirImages()
    {
        return $this->_dir_img;
    }
    
    public function getDirFonts()
    {
        return $this->_dir_fonts;
    }
    
    public function getDirData()
    {
        return $this->_dir_data;
    }
    
    public function getBodyContent()
    {
        return $this->_body_content;
    }
        
    //-----PUBLIC FUNCTIONS----------------------
    
    public function renderPage()
    {
        echo $this->createPage();
    }
    
    public function createPage()
    {
            $thtmlmarkup = <<<HTML
<!DOCTYPE html>
<html lang="en">
<!--HEAD ELEMENT -->
{$this->createHTML_Head()}
<!--BODY ELEMENT -->
{$this->createHTML_Body()}
</html>
HTML;
        return $thtmlmarkup;
    }
  
    //Set all five relevant paths in a function.
    public function setMediaDirectory($pcss,$pjs,$pfonts,$pimg,$pdata)
    {
        $this->setDirCSS($pcss);
        $this->setDirJS($pjs);
        $this->setDirFonts($pfonts);
        $this->setDirImages($pimg);
        $this->setDirData($pdata);
    }
    
    //-----PRIVATE FUNCTIONS---------------------
    
    //-----HEAD CREATION FUNCTIONS-----------
    
    //Create the <head> element
    private function createHTML_Head()
    {
        $thead = <<<HEAD
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {$this->createHTML_Meta()}
    {$this->createHTML_FavIcon()}
    <title>{$this->_head_title}</title>
    <!-- Include External CSS -->
    {$this->createHTML_CSS()}
</head>
HEAD;
        return $thead;
    }
    
    //Create a set of <meta> elements    
    private function createHTML_Meta()
    {
        $thtml = "";
        foreach($this->_arr_meta as $tkey => $tvalue)
        {
            $tmetamarkup = <<<META
<meta name="{$tkey}" value="{$tvalue}">       
META;
            $thtml.=$tmetamarkup;
        }
        return $thtml;
    }
    
    //Create a FavIcon <link> element
    private function createHTML_FavIcon()
    {
        $thtml = "";
        if(!empty($this->_head_favicon))
        {
            $tfaviconpath = $this->_dir;        
            $thtml = <<<FAVICON
<link href="{$tfaviconpath}"rel="icon" type="image/x-icon" />
FAVICON;
        
        }
        return $thtml;
    }
    
    //Create a set of CSS <link> elements
    private function createHTML_CSS()
    {
        $thtml = "";
        $tpathcss = $this->toURLs($this->_arr_css,$this->_dir_css);
        foreach($tpathcss as $tcssfile)
        {
            $tcssmarkup = <<<SCRIPT
<link href="{$tcssfile}" rel="stylesheet">

SCRIPT;
            $thtml .=$tcssmarkup;
        }
        return $thtml;
    }
    
    //------BODY CREATION FUNCTIONS-------------------
    
    //Create the HTML <body> element
    private function createHTML_Body()
    {
        $this->createHTML_JS();
        $thtml = <<<BODY
<body>
    <!--PHP GENERATED PAGE CONTENT -->
    {$this->_body_content}
    
    <!-- EXTERNAL SCRIPTS -->
    {$this->createHTML_JS()}
</body>
BODY;
        return $thtml;
    }
    
    //Create a set of JS <script> elements
    private function createHTML_JS()
    {
        $thtml = "";
        $tpathjs = $this->toURLs($this->_arr_js,$this->_dir_js);
        foreach($tpathjs as $tjsfile)
        {
        $tjsmarkup = <<<SCRIPT
<script src="{$tjsfile}"></script>
   
SCRIPT;
        $thtml .=$tjsmarkup;
        }
        return $thtml;
    }
    
    //-------HELPER FUNCTIONS---------------------------
    
    //Convert a set of source files into an array
    //of URLs given the base path.
    function toURLs(array &$parray,$ppath)
    {
        $tpatharray = [];
        foreach($parray as $tfile)
        {
            $tpatharray[] = "{$ppath}/{$tfile}";
        }
        return $tpatharray;
    }
}
?>