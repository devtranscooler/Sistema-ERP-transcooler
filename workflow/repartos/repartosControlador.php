<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/system/connection.php');

class repartosControlador
{
    private $db;

    public function __construct()
    {
        $this->db = new MySQL();
        date_default_timezone_set('America/Mexico_City');
    }
    
    public function crear($data){
        $SQL = "INSERT INTO repartos (id_servicio,numero_reparto,id_destino) 
                VALUES (?, ?, ?)";
        
        $params = [
            $data['id_servicio'],
            $data['numero_reparto'],
            $data['id_destino'],
        ];

        return $this->db->execute($SQL, $params);
    }
    public function actualizar($id , $data){
        $campos = [
            'id_servicio = ?',
            'numero_reparto = ?',
            'id_destino = ?'            
            ];

        $params = [
            $data['id_servicio'],
            $data['numero_reparto'],
            $data['id_destino'],
            $id
        ];

        $sql = "UPDATE repartos SET ". implode(", ", $campos) ." WHERE id = ?";
        return $this->db->execute($sql, $params);
    }

    public function eliminar($id){
        $sql = "UPDATE repartos SET status='eliminado' WHERE id=?";
        return $this->db->execute($sql, [$id]);
    } 
    public function eliminarPorServicio($id_servicio) {
    $sql = "UPDATE repartos SET status = 'eliminado' WHERE id_servicio = ?";
    return $this->db->execute($sql, [$id_servicio]);
}
}
