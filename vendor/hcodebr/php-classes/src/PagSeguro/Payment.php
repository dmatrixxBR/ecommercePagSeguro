<?php

namespace Hcode\PagSeguro;

use Exception;
use DOMDocument;
use DOMElement;
use Hcode\PagSeguro\Payment\Method;
use Hcode\PagSeguro\Config;

class Payment {
    private $reference = "";
    private $mode = "default";
    private $currency = "BRL";
    private $extraAmount = 0;
    private $items = [];
    private $sender;
    private $shipping;
    private $method;
    private $creditCard;
    private $bank;

    public function __construct(
        string $reference,
        Sender $sender,
        Shipping $shipping,
        float $extraAmount = 0
    )
    {
        $this->sender = $sender;
        $this->shipping = $shipping;
        $this->reference = $reference;
        $this->extraAmount = number_format($extraAmount, 2, ".", "");

    }


    public function addItem(Item $item)
    {

        array_push($this->items, $item);
    }

    public function setCreditCard(CreditCard $creditCard)
    {
        $this->creditCard = $creditCard;
        $this->method = Method::CREDIT_CARD;
    }

    public function setBank(Bank $bank)
    {
        $this->bank = $bank;
        $this->method = Method::DEBIT;
    }

    public function setBoleto()
    {
        $this->method = Method::BOLETO;
    }

    public function getDOMDocument():DOMDocument
    {
        $dom = new DOMDocument("1.0","ISO-8859-1");

        $payment = $dom->createElement("payment");
        $payment = $dom->AppendChild($payment);

        $mode = $dom->createElement("mode", $this->mode);
        $mode = $payment->AppendChild($mode);

        $currency = $dom->createElement("currency", $this->currency);
        $currency = $payment->AppendChild($currency);

        $notificationUrl = $dom->createElement("notificationURL", Config::NOTIFICATION_URL);
        $notificationUrl = $payment->AppendChild($notificationUrl);

        $receiverEmail = $dom->createElement("receiverEmail", Config::PRODUCTION_EMAIL);
        $receiverEmail = $payment->AppendChild($receiverEmail);

        $sender = $this->sender->getDOMElement();
        $sender = $dom->importNode($sender, true);
        $sender = $payment->appendChild($sender);

        $items = $dom->createElement("items");
        $items = $payment->appendChild($sender);

        foreach($this->items as $_item)
        {
            $item = $_item->getDOMElement();
            $item = $dom->importNode($item, true);
            $item = $items->appendChild($item);
        }

        
       // var_dump($this->shipping->getDOMElement());
       // exit;

        $reference = $dom->createElement("reference" , $this->reference);
        $reference = $payment->appendChild($reference);


        $shipping = $this->shipping->getDOMElement();
        $shipping = $dom->importNode($shipping, true);
        $shipping = $payment->appendChild($shipping);

        
        
        $extraAmount = $dom->createElement("extraAmount",$this->extraAmount);
        $extraAmount = $payment->appendChild($extraAmount);

        $method = $dom->createElement("method" , $this->method);
        $method = $payment->appendChild($method);

        switch ($this->method)
        {
            case Method::CREDIT_CARD:

            $creditCard = $this->creditCard->getDOMElement();
            $creditCard = $dom->importNode($creditCard, true);
            $creditCard = $payment->appendChild($creditCard);

            break;

            case Method::DEBIT:

            $bank = $this->bank->getDOMElement();
            $bank = $dom->importNode($bank, true);
            $bank = $payment->appendChild($bank);

            break;
        }

        return $dom;
    }


}