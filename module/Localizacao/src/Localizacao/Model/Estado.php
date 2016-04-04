<?php
namespace Localizacao\Model;

use Exception;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;
class Estado{
    
    public $id;
    
    public $nome;
    
    public $uf;
    
    protected $inputFilter;


    public function exchangeArray($data){
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->nome = (isset($data['nome'])) ? $data['nome'] : null;
        $this->uf = (isset($data['uf'])) ? $data['uf'] : null;

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
                            'min'      => 1,
                            'max'      => 200,
                        ),
                    ),
                ),
            )));
            $inputFilter->add($factory->createInput(array(
                'name'     => 'uf',
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
            'nome' => $this->nome,
            'uf' => $this->uf,

        );
    }
    
}


