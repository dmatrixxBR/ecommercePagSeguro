<?php

namespace Hcode\PagSeguro;

class Payment {

    private $mode = "default";
    private $currency = "BRL";
    private $extraAmount = 0;
    private $reference = "";
    private $items = [];
    private $sender;
    private $shipping;
    private $method;
    private $creditCard;
    private $bank;


    public function __construct(
        string $token,
        Installment $installment,
        Holder $holder,
        Address $billingAddress
    )
    {

        if (!token)
        {
            throw new Exception("Informe o token do cartão de Credito"); 
        }

        $this->token = $token;
        $this->installment = $installment;
        $this->holder = $holder;
        $this->billingAddress = $billingAddress;

    }

    public function getDOMElement():DOMElement
    {
    
        $dom = new DOMDocument();
    
        $creditCard = $dom->createElement("creditCard");
        $creditCard = $dom->appendChild($creditCard);

        $token = $dom->createElement("token", $this->token);
        $token = $creditCard->appendChild($token);

        $installment = $this->installment->getDOMElement();
        $installment = $dom->importNode($installment,true);
        $installment = $creditCard->appendChild($installment);

        $holder = $this->holder->getDOMElement();
        $holder = $dom->importNode($holder,true);
        $hcreditCard->appendChild($holder);

        $billingAddress = $this->billingAddress->getDOMElement("billingAddress");
        $billingAddress = $dom->importNode($billingAddress,true);
        $billingAdcreditCard->appendChild($billingAddress);

    
        return $creditCard;
        
    
    }




}