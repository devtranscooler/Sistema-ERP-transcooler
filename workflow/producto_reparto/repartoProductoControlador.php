<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/system/connection.php';

class repartoProductoControlador
{
    private $db;

    public function __construct()
    {
        $this->db = new MySQL();
        date_default_timezone_set('America/Mexico_City');
    }
    
    public function crear($data){
        $SQL = "INSERT INTO producto_reparto (reparto_id,producto_id,cantidad,peso) 
                VALUES (?, ?, ?, ?)";
        
        $params = [
            $data['reparto_id'],
            $data['producto_id'],
            $data['cantidad'],
            $data['peso']
        ];

        return $this->db->execute($SQL, $params);
    }

    public function actualizar($id , $data){
        $campos = [
            'reparto_id = ?',
            'producto_id = ?',
            'cantidad = ?',
            'peso = ?',
            ];

        $params = [
            $data['reparto_id'],
            $data['producto_id'],
            $data['cantidad'],
            $data['peso'],
            $id
        ];

        $sql = "UPDATE producto_reparto SET ". implode(", ", $campos) ." WHERE id = ?";
        return $this->db->execute($sql, $params);
    }    
}
