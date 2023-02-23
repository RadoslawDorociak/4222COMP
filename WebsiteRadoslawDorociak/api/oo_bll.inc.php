<?php



class BLLuser 
{
    //-------CLASS FIELDS------------------
    public $id = null;
    public $name;
    public $email;
    public $password;
    
    public function fromArray(stdClass $passoc)
    {
        foreach($passoc as $tkey => $tvalue)
        {
            $this->{$tkey} = $tvalue;
        }
    }
}
class BLLReview 
{
    //-------CLASS FIELDS------------------
    public $id = null;
    public $game;
    public $name;
    public $score;
    public $review;
    
    public function fromArray(stdClass $passoc)
    {
        foreach($passoc as $tkey => $tvalue)
        {
            $this->{$tkey} = $tvalue;
        }
    }
}
class BLLGame 
{
    //-------CLASS FIELDS------------------
    public $id = null;
    public $title;
    public $description;
    public $shrtdescription;
    public $publisher;
    public $platforms;
    public $reldate;
    public $score;
    public $extrev1link;
    public $extrev1name;
    public $extrev2link;
    public $extrev2name;
    public $extrev3link;
    public $extrev3name;
    
    public function fromArray(stdClass $passoc)
    {
        foreach($passoc as $tkey => $tvalue)
        {
            $this->{$tkey} = $tvalue;
        }
    }
}
class BLLCommercial 
{
    //-------CLASS FIELDS------------------
    public $id = null;
    public $gametitle;
    public $name1;
    public $link1;
    public $price1;
    public $name2;
    public $link2;
    public $price2;
    public $name3;
    public $link3;
    public $price3;
    public function fromArray(stdClass $passoc)
    {
        foreach($passoc as $tkey => $tvalue)
        {
            $this->{$tkey} = $tvalue;
        }
    }
}


?>