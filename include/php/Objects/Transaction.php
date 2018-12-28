<?php

class Transaction {
    public $id;
    public $type;
    public $iStatus;
    public $timeStarted;
    public $creditWallet;
    public $creditWalletAccount;
    public $creditWalletAddress;
    public $clientIP;
    public $requiredAmount;
    public $receivedAmount;
    public $itemID;
    public $currency;
    
    function __construct()
    {
        $a = func_get_args();
        $i = func_num_args();
        if($i > 0)
        {
            if (method_exists($this,$f='__construct'.$i)) {
                call_user_func_array(array($this,$f),$a);
            }
        }
        else {
            $this->id                   = "invalid";
        }
    }

    /**
    * Basic constructor
    * $row = mysql_row (tbl -> transactions)
    **/
    function __construct1($row)
    {
        $this->id                   = $row["txid"];
        $this->type                 = $row["type"];
        $this->iStatus              = $row["istatus"];
        $this->timeStarted          = $row["timeStarted"];
        $this->creditWallet         = $row["creditWallet"];
        $this->creditWalletAccount  = $row["creditWalletAccount"];
        $this->creditWalletAddress  = $row["creditWalletAddress"];
        $this->clientIP             = $row["clientIP"];
        $this->requiredAmount       = $row["requiredAmount"];
        $this->receivedAmount       = $row["receivedAmount"];
        $this->itemID               = $row["itemID"];
        $this->currency             = $row["currencyName"];
    }

    
    public function __toString()
    {
        return $this->id.". Status: ".$this->iStatus.", timeStarted -> ".$this->timeStarted.", Amount required: ".$this->requiredAmount;
    }
}