<?php

class ErrorController extends Controller
{
    public function index()
    {
        $this->views->render($this, "Error404");
    }
}


$ObjectError = new ErrorController();
$ObjectError->index();
