<?php

class Currency {
    public $id;
    public $currencyName;
    public $currencyPair;
    public $cmc;
    public $rpcIP;
    public $rpcPort;
    public $rpcUser;
    public $rpcPass;
    /**
    * Basic constructor
    * $row = mysql_row (tbl -> currencies)
    **/
    function __construct($row)
    {
        $this->id                        = $row["id"];
        $this->currencyName              = $row["currencyName"];
        $this->currencyPair              = $row["currencyPair"];
        $this->cmc                       = $row["cmc"];
        $this->rpcIP                     = $row["rpcIP"];
        $this->rpcPort                   = $row["rpcPort"];
        $this->rpcUser                   = $row["rpcUser"];
        $this->rpcPass                   = $row["rpcPass"];
        
    }
    
    public function __toString()
    {
        return $this->id.". currencyName: ".$this->currencyName.", currencyPair -> ".$this->currencyPair.", cmc: ".$this->cmc . ", rpc: " . $this->rpcIP.":".$this->rpcPort." (".$this->rpcUser."@".$this->rpcPass.")";
    }
    
    
}