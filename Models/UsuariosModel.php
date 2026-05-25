<?php

class UsuariosModel extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->tabla = "usuarios";
    }

    public function obtenerPermisosDelUsuario($usuario_id)
    {
        $permisos = $this->select("p.clave_acceso")
            ->join("usuarios_roles ur", "ur.usuario_id = usuarios.id")
            ->join("roles r", "r.id = ur.rol_id")
            ->join("roles_permisos rp", "rp.rol_id = r.id")
            ->join("permisos p", "p.id = rp.permiso_id")
            ->where(["usuarios.id" => $usuario_id])
            ->get();

        $claves = [];
        foreach ($permisos as $p) {
            $claves[] = $p['clave_acceso'];
        }
        return $claves;
    }
}
