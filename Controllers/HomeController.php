<?php

class HomeController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_data'])) {
            header("Location: " . BASE_URL . "Login");
            exit();
        }
    }

    public function index()
    {
        $this->views->render($this, "index");
    }

    public function datos($param)
    {
        $datos["titulo"] = "Datos recibidos";
        $datos["subtitulo"] = "Estos son los datos que se han recibido";
        $datos["param"] = $param;
        $this->views->render($this, "Datos", $datos);
    }
}
