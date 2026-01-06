<?php
class Student {
    private string $id;
    private string $name;
    private float $gpa;

    public function __construct($id,$name,$gpa){
        $this->id=$id;
        $this->name=$name;
        $this->gpa=$gpa;
    }

    public function getId(){ return $this->id; }
    public function getName(){ return $this->name; }
    public function getGpa(){ return $this->gpa; }

    public function rank(){
        if ($this->gpa >= 3.2) return "Giỏi";
        if ($this->gpa >= 2.5) return "Khá";
        return "Trung bình";
    }
}
