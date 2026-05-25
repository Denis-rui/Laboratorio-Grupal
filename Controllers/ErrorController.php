<?php

class ErrorController extends Controller
{
    public function index($view = "Error404", $data = [])
    {
        $this->views->render($this, $view, $data);
    }

    public function accessDenied()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $mensaje = $_SESSION['access_denied_message'] ?? 'No tienes permiso para acceder a esta sección';
        unset($_SESSION['access_denied_message']);

        $this->views->render($this, "AccessDenied", ["message" => $mensaje]);
    }
}
