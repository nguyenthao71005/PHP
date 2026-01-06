<?php
class Product {
    function __construct(
        private string $id,
        private string $name,
        private float $price,
        private int $qty
    ){}

    function amount(){ return $this->price * $this->qty; }
    function getId(){return $this->id;}
    function getName(){return $this->name;}
    function getPrice(){return $this->price;}
    function getQty(){return $this->qty;}
}
