<?php

class Wallet {
    public $id;
    public $account;
    public $walletLabel;
    public $walletAPI;
    public $domain;
    public $balances;
    public $transactions;
    
    /**
     * Basic constructor
     * $row = mysql_row (tbl -> transactions)
     **/
    function __construct($row)
    {
        $this->id                   = $row["id"];
        $this->walletLabel          = $row["walletLabel"];
        $this->walletAPI          = $row["walletAPI"];
        $this->domain               = $row["domain"];
    }
    
    public function __toString()
    {
        return "Wallet - ".$this->id.". Account: ".$this->account.", Wallet: ".$this->walletLabel.", Domain -> ".$this->domain;
    }
}