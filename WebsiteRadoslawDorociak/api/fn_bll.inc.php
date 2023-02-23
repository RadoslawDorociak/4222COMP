<?php

require_once("oo_bll.inc.php");


//////////////////////////////
// Function to clean-up data
// that has been received from
// a form, regardless of value.
//////////////////////////////
function appFormProcessDataWithNulls($pdata)
{
    $tdata = trim($pdata);
    $tdata = stripslashes($tdata);
    $tdata = htmlspecialchars($tdata);
    return $tdata;
}

//////////////////////////////
// Function to clean-up data
// that has been received from a form.
// Null entries are treated as blanks
// and skipped.
//////////////////////////////
function appFormProcessData($pdata)
{
    $tclean = $pdata ?? "";
    if (! empty($tclean))
    {
        $tclean = trim($tclean);
        $tclean = stripslashes($tclean);
        $tclean = htmlspecialchars($tclean);
    }
    return $tclean;
}

function processRequest($pdata)
{
    $tdata = trim($pdata);
    $tdata = stripslashes($tdata);
    $tdata = htmlspecialchars($tdata);
    return $tdata;
}
//////////////////////////////
// Function to add Form Validity
// key - to be used with Check Function. 
//////////////////////////////
function appFormSetValid(array &$pdata)
{
    $pdata["valid"] = true;
}

//////////////////////////////
// Function to Check for Form
// Validity Key
//////////////////////////////
function appFormCheckValid(array $pdata)
{
    return array_key_exists("valid",$pdata);
}

//////////////////////////////
// Function to Treat Null values
// and missing keys as empty.
//////////////////////////////
function appFormNullAsEmpty(array &$pdata, $tkey)
{
    $pdata[$tkey] = $pdata[$tkey] ?? "";
}

function appFormMethodIsPost()
{
    return strtolower($_SERVER['REQUEST_METHOD']) == 'post';
}

/////////////////////////////////
// Function to get the Form
// Action to Self-Submit.
/////////////////////////////////
function appFormActionSelf()
{
    return htmlspecialchars($_SERVER['PHP_SELF']);
}

/////////////////////////////////
// Function to get the Form
// Method.
/////////////////////////////////
function appFormMethod($pmethoddefault = true)
{
    return $pmethoddefault ? "POST" : "GET";
}

//////////////////////////////
// Function to paginate an
// array using the slice technique
//////////////////////////////
function appPaginateArray(array $parray,$ppageno,$pnoitems)
{
    $tpageno = $ppageno < 1 ? 1 : $ppageno;
    $tstart = ($tpageno - 1) * $pnoitems;
    return array_slice($parray, $tstart, $pnoitems);
}

//////////////////////////////
// Function to paginate an
// array using the chunk
// technique.
//////////////////////////////
function appPaginateArrayAlt(array $parray, $ppageno,$pnoitems)
{
    $tarrayofpages = array_chunk($parray, $pnoitems);
    return $ppageno > sizeof($tarrayofpages) ? [] : $tarrayofpages[$ppageno - 1];
}


////////////////////////////////
// Redirect To Home Page 
////////////////////////////////
function appGoToHome()
{
    header("Location: index.php");
}

////////////////////////////////
// Redirect To Error Page
////////////////////////////////
function appGoToError()
{
    header("Location: app_error.php");
}

////////////////////////////////
// Redirect To Error Page With Message
////////////////////////////////
function appGoToErrorMsg($pmsg)
{
   $pmsg = processFormData($pmsg);
   header("Location: app_error.php?msg={$pmsg}");
}

//////////////////////////////
// Function to initialise the
// common session keys for this
// application.
//////////////////////////////
function appSessionInitData(bool $pstart = true)
{
    if($pstart)
    {
        session_start();
    }
        $tsession = ["myname","myuser","last-access","fav-club"];
        foreach($tsession as $tsessionkey)
        {
            $_SESSION[$tsessionkey] = "";
        }
}

////////////////////////////////
// Check for Existence of the
// Login Token
////////////////////////////////
function appSessionLoginExists()
{
    $tuser = $_SESSION["myuser"] ?? "";
    if(!empty($tuser))
        return true;
        return false;
}

////////////////////////////////
// Create the Login Tokens
////////////////////////////////
function appSessionSetLoginTokens($pusername)
{
    if(!empty($pusername))
    {
        $_SESSION["myuser"] = processRequest($pusername);
        $_SESSION["entered"] = true;
    }
}

////////////////////////////////
// Create the Login Tokens and
// Redirect to the Home Page
////////////////////////////////
function appSessionLoginAndReturn($pusername)
{
    setLoginTokens($pusername);
    goToHome();
}

//////////////////////////////
// Function to reset all tokens
// by unsetting them this preserves
// the actual session. 
//////////////////////////////
function appSessionUnsetTokens()
{
    foreach(array_keys($_SESSION) as $tkey)
    {
        unset($_SESSION[$tkey]);
    }
}

//////////////////////////////
// Function to reset all tokens to empty
// Also clears the login token.
//////////////////////////////
function appSessionEmptyTokens()
{
    foreach(array_keys($_SESSION) as $tkey)
    {
        $_SESSION[$tkey] = "";
    }
    unset($_SESSION["entered"]);
}

//////////////////////////////
// Function to destroy the session
// and unset any existing tokens.
//////////////////////////////
function appSessionDestroy()
{
    session_unset();
    session_destroy();
}
    
?>