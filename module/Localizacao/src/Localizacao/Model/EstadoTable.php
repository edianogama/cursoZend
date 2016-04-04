<?php

namespace Localizacao\Model;

use Exception;
use Zend\Db\TableGateway\TableGateway;

class EstadoTable {

    protected $tableGateway;

    const ATIVO = 1;

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }
     public function getEstado($id)
    {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(array(
            'id' => $id
        ));
        $row = $rowset->current();
        if (! $row) {
            throw new Exception("Não existe linha no banco para este id $id");
        }
        return $row;
    }
    public function fetchAll() {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }
    
    public function salvarEstado(Estado $estado)
    {
        $data = array(
            'nome' =>  $estado->nome,
            'uf' => $estado->uf
          
        );

        $id = (int) $estado->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            
            if ($this->getEstado($id)) {
                $this->tableGateway->update($data, array(
                    'id' => $id
                ));
            } else {
                throw new Exception('Não existe registro com esse ID' . $id);
            }
        }
    }
    public function deletarEstado($id)
    {
        $this->tableGateway->delete(array(
            'id' => $id
        ));
    }
}
