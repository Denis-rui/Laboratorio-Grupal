<?php
class LoginModel extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->tabla = "usuarios";
        $this->primaryKey = "id";
    }

    public function getPermisos($usuarioId)
    {
        $permisos = $this->select("p.clave_acceso")
            ->join("usuarios_roles ur", "ur.usuario_id = usuarios.id")
            ->join("roles_permisos rp", "rp.rol_id = ur.rol_id")
            ->join("permisos p", "p.id = rp.permiso_id")
            ->where(["usuarios.id" => $usuarioId])
            ->get();

        $claves = [];
        foreach ($permisos as $permiso) {
            $claves[] = $permiso['clave_acceso'];
        }

        return array_values(array_unique($claves));
    }

    public function validarCredenciales($correo, $clave)
    {
        $usuario = $this->where(["correo" => $correo, "estado" => 1])->first();

        if (!$usuario) {
            return null;
        }

        return password_verify($clave, $usuario['clave']) ? $usuario : null;
    }

    public function getRol($usuarioId)
    {
        return $this->select("r.id, r.nombre_rol AS nombre")
            ->join("usuarios_roles ur", "ur.usuario_id = usuarios.id")
            ->join("roles r", "r.id = ur.rol_id")
            ->where(["usuarios.id" => $usuarioId])
            ->first();
    }
}
