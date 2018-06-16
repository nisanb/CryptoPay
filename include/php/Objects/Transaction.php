<?php

class Transaction {
    public $id;
    public $iStatus;
    public $timeStarted;
    public $creditWallet;
    public $creditWalletAccount;
    public $creditWalletAddress;
    public $clientIP;
    public $requiredAmount;
    public $itemID;
    public $currency;
    /**
    * Basic constructor
    * $row = mysql_row (tbl -> transactions)
    **/
    function __construct($row)
    {
        $this->id                   = $row["id"];
        $this->iStatus              = $row["istatus"];
        $this->timeStarted          = $row["timeStarted"];
        $this->creditWallet         = $row["creditWallet"];
        $this->creditWalletAccount  = $row["creditWalletAccount"];
        $this->creditWalletAddress  = $row["creditWalletAddress"];
        $this->clientIP             = $row["clientIP"];
        $this->requiredAmount       = $row["requiredAmount"];
        $this->itemID               = $row["itemID"];
        $this->currency             = $row["currency"];
        
    }
    
    public function __toString()
    {
        return $this->id.". Status: ".$this->iStatus.", timeStarted -> ".$this->timeStarted.", Amount required: ".$this->requiredAmount;
    }
}