<?php

class HomeController extends Controller
{
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
