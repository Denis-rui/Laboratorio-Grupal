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
}
