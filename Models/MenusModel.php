<?php

class MenusModel extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->tabla = "menus";
        $this->primaryKey = "id";
    }

    public function getMenusPorRol($rolId, $esAdmin = false)
    {
        if ($esAdmin) {
            return $this->select("menus.id, menus.titulo, menus.url, menus.icono")
                ->orderBy("menus.id", "ASC")
                ->get();
        }

        if ($rolId <= 0) {
            return [];
        }

        return $this->select("menus.id, menus.titulo, menus.url, menus.icono")
            ->join("roles_menus rm", "rm.menu_id = menus.id")
            ->where(["rm.rol_id" => (int) $rolId])
            ->orderBy("menus.id", "ASC")
            ->get();
    }
}
