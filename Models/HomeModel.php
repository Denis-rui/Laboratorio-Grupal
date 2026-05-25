<?php
class HomeModel extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->tabla = "usuarios";
        $this->primaryKey = "id";
    }



}