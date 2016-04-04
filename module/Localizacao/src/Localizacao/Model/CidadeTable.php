<?php

namespace Localizacao\Model;

use Exception;
use Zend\Db\TableGateway\TableGateway;

class CidadeTable {

    protected $tableGateway;

    const ATIVO = 1;

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }
     public function getCidade($id)
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
    
    public function salvarCidade(Cidade $cidade)
    {
        $data = array(
            'nome' =>  $cidade->nome,
            'latitude' => $cidade->latitude,
            'longitude' => $cidade->longitude
          
        );

        $id = (int) $cidade->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            
            if ($this->getCidade($id)) {
                $this->tableGateway->update($data, array(
                    'id' => $id
                ));
            } else {
                throw new Exception('Não existe registro com esse ID' . $id);
            }
        }
    }
    public function deletarCidade($id)
    {
        $this->tableGateway->delete(array(
            'id' => $id
        ));
    }
}
