<?php
namespace Application\Form;
use Zend\Form\Form;

class PerfilForm extends Form
    {
        public function __construct($name = null)
        {
        parent::__construct('perfil');
        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'form-horizontal');
        $this->setAttribute('role', 'form');
        
        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));
        $this->add(array(
            'name' => 'nome',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'placeholder' => 'Nome do Perfil'
            )
        ));
        
        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Salvar',
                'id' => 'submitbutton',
                'class' => 'btn btn-default'
            ),
        ));
        }
    }