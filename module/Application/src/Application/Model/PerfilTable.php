<?php

namespace Application\Model;

use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGateway;

class PerfilTable {

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }

    public function fetchPars() {
        $select = new Select();
        $select->from('perfil')
                ->columns(array('id', 'nome'));
        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
    }

}
