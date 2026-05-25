<?php
class ClientesController extends Controller
{
    private function esAdminGlobal()
    {
        $rolNombre = strtolower(trim($_SESSION['rol_nombre'] ?? ''));
        $rolId = (int) ($_SESSION['rol_id'] ?? 0);
        return $rolId === 1 || strpos($rolNombre, 'admin') !== false;
    }

    public function __construct()
    {
        parent::__construct();

        // Verificar sesión
        if (!isset($_SESSION['user_data'])) {
            header("Location: " . BASE_URL . "Login");
            exit();
        }

        // Verificar permiso según método
        $url = $_GET['url'] ?? '';
        $arrUrl = explode('/', $url);
        $metodo = $arrUrl[1] ?? 'index';

        $permisoRequerido = '';
        switch ($metodo) {
            case 'crear':
            case 'guardar':
                $permisoRequerido = 'clientes.crear';
                break;
            case 'editar':
            case 'actualizar':
                $permisoRequerido = 'clientes.editar';
                break;
            case 'eliminar':
                $permisoRequerido = 'clientes.eliminar';
                break;
            case 'listar':
            case 'index':
                $permisoRequerido = 'clientes.listar';
                break;
            case 'ver':
                $permisoRequerido = 'clientes.ver';
                break;
        }

        if ($permisoRequerido !== '' && !$this->esAdminGlobal()) {
            if (!in_array($permisoRequerido, $_SESSION['permisos'] ?? [])) {
                $this->accesoDenegado("No tienes permiso para acceder a esta sección");
            }
        }
    }

    public function index()
    {
        $this->listar();
    }

    public function listar()
    {
        $data = $this->model->where(["estado" => 1])->get();
        $this->model->resetQuery();
        $this->views->render($this, "listado", $data);
    }

    public function crear()
    {
        $this->views->render($this, "crear");
    }

    public function guardar()
    {
        $data = [
            "nombre"   => $_POST['nombre'],
            "dni"      => $_POST['dni'],
            "telefono" => $_POST['telefono'],
            "estado"   => 1
        ];
        if ($this->model->create($data)) {
            header("Location: " . BASE_URL . "Clientes/listar");
        } else {
            echo "Error al guardar";
        }
        exit();
    }

    public function editar($id)
    {
        $data = $this->model->where(["id" => $id])->first();
        $this->model->resetQuery();
        $this->views->render($this, "editar", $data);
    }

    public function actualizar($id)
    {
        $data = [
            "nombre"   => $_POST['nombre'],
            "dni"      => $_POST['dni'],
            "telefono" => $_POST['telefono']
        ];
        if ($this->model->update($id, $data)) {
            header("Location: " . BASE_URL . "Clientes/listar");
        } else {
            echo "Error al actualizar";
        }
        exit();
    }

    public function eliminar($id)
    {
        $this->model->delete($id);
        header("Location: " . BASE_URL . "Clientes/listar");
        exit();
    }
}