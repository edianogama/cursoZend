<?php

namespace Application\Model;

class Perfil {

    public $id;
    public $nome;

    public function exchangeArray($data) {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->nome = (isset($data['nome'])) ? $data['nome'] : null;
    }

    public function getArrayCopy() {
        return get_object_vars($this);
    }

}
