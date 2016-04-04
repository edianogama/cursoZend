<?php
namespace Application\Controller;

use Application\Form\PerfilForm;
use Application\Model\Perfil;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class PerfilController extends AbstractActionController {

    protected $perfilTable;

    public function listAction() {
        return new ViewModel(
                array(
            "perfis" => $this->getPerfilTable()->fetchAll()
        ));
    }

    public function getPerfilTable() {
        if (!$this->perfilTable) {
            $sm = $this->getServiceLocator();
            $this->perfilTable = $sm->get('Application\Model\PerfilTable');
        }
        return $this->perfilTable;
    }
    public function addAction()
    {
        $form = new PerfilForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $perfil = new Perfil();
            $form->setInputFilter($perfil->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $perfil->exchangeArray($form->getData());
                
                $this->getPerfilTable()->salvarPerfil($perfil);
                return $this->redirect()->toUrl("/application/perfil/list");
            }
        }
        return array('form' => $form);
    }

}
