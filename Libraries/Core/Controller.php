<?php
// Sirver para cargar vistas y el modelo`

class Controller
{
    protected $model, $views;
    function __construct()
    {
        $this->views = new Views();
        $this->loadModel();
    }
    public function loadModel()
    {
        $modelName = str_replace("Controller", "Model", get_class($this));  // me llega el nombre del controlador, por ejemplo: HomeController
        $ModelPath = "Models/{$modelName}.php"; // me arma el path del modelo, por ejemplo: Models/HomeModel.php
        if (file_exists($ModelPath)) {
            require_once $ModelPath; // si el archivo existe, lo incluyo
            $this->model = new $modelName(); // creo una instancia del modelo y la asigno a una propiedad del controlador
        }
    }
}
