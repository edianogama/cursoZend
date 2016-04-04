<?php
namespace Application\Model;

use Exception;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;

class Usuario{
    
    public $id;
    
    public $nome;
    
    public $email;
    
    public $idade;
    
    public $login;
    
    public $senha;
    
    public $perfil;
    
    public $inputFilter;
    
    
    public function exchangeArray($data){
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->nome = (isset($data['nome'])) ? $data['nome'] : null;
        $this->email = (isset($data['email'])) ? $data['email'] : null;
        $this->idade = (isset($data['idade'])) ? $data['idade'] : null;
        $this->login = (isset($data['login'])) ? $data['login'] : null;
        $this->senha = (isset($data['senha'])) ? $data['senha'] : null;
        
        $this->perfil = new Perfil();
        
        $this->perfil->id = (isset($data['perfil_id'])) ? $data['perfil_id'] : null;
        $this->perfil->nome = (isset($data['nome_perfil'])) ? $data['nome_perfil'] : null;
    }
    
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new Exception("Não validado");
    }
    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory     = new InputFactory();
            $inputFilter->add($factory->createInput(array(
                'name'     => 'id',
                'required' => false,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            )));
            $inputFilter->add($factory->createInput(array(
                'name'     => 'nome',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 3,
                            'max'      => 100,
                        ),
                    ),
                ),
            )));
            $inputFilter->add($factory->createInput(array(
                'name'     => 'email',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            )));
            $inputFilter->add($factory->createInput(array(
                'name'     => 'idade',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 3,
                        ),
                    ),
                ),
            )));
            $this->inputFilter = $inputFilter;
        }
        return $this->inputFilter;
    }
    public function getArrayCopy()
    {
        return array(
            'id' => $this->id,
            'login' => $this->login,
            'senha' => $this->senha,
            'nome' => $this->nome,
            'user_channel' => $this->user_channel,
            'email' => $this->email,
            'perfil_id' => $this->perfil->id
        );
    }
    
}


