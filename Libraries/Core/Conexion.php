<?php

require_once "Config/Config.php";

class conexion
{
    private $conection;
    public function __construct()
    {
        try {
            $this->conection = new PDO("mysql:host=" . BD_HOST . ";dbname=" . BD_NAME . ";charset=" . BD_CHARSET, BD_USER, BD_PASS);
            $this->conection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
            exit();
        }
    }
    public function conectar()
    {
        return $this->conection;
    }
}
