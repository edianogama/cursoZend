<?php
namespace Application\Controller;

use Application\Form\UsuarioForm;
use Application\Model\Usuario;
use Exception;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class UsuarioController extends AbstractActionController {

    protected $usuarioTable;
    protected $_objectManager;
    
    public function listAction() {
        return new ViewModel( array("usuarios" => $this->getUsuarioTable()->fetchAll()));
    }

    public function getUsuarioTable() {
        if (!$this->usuarioTable) {
            $sm = $this->getServiceLocator();
            $this->usuarioTable = $sm->get('Application\Model\UsuarioTable');
        }
        return $this->usuarioTable;
    }
    public function addAction()
    {
        $form = new UsuarioForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $usuario = new Usuario();
            $form->setInputFilter($usuario->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $usuario->exchangeArray($form->getData());
                $this->getUsuarioTable()->salvarUsuario($usuario);
                return $this->redirect()->toUrl("/application/usuario/list");
            }
        }
        return array('form' => $form);
    }
    public function editAction()
       {
           $id = (int) $this->params()->fromRoute('id', 0);

           if (empty($id))
           {
                   $id = $this->getRequest()->getPost('id');
                   if (empty($id)) {
                           return $this->redirect()->toUrl('add');
                   }
           }

           try {
                   $usuario = $this->getUsuarioTable()->getUsuario($id);
           }
           catch (Exception $ex) {
                   return $this->redirect()->toUrl('list');
           }

           $form  = new UsuarioForm();
           $form->bind($usuario);

           $request = $this->getRequest();
           if ($request->isPost()) {
                   $form->setInputFilter($usuario->getInputFilter());
                   $form->setData($request->getPost());

                   if ($form->isValid()) {
                           $this->getUsuarioTable()->salvarUsuario($form->getData());

                           return $this->redirect()->toUrl('list');
                   }
           }

           return array(
               'id' => $id,
               'form' => $form,
           );
       }
       public function deleteAction()
       {
           $id = (int) $this->params()->fromRoute('id', 0);
           if (!$id) {
               return $this->redirect()->toUrl('/application/usuario/list');
           }

           $request = $this->getRequest();
           if ($request->isPost()) {
               $del = $request->getPost('del', 'Nao');

               if ($del == 'Sim') {
                   $id = (int) $request->getPost('id');
                   $this->getUsuarioTable()->deletarUsuario($id);
               }
               return $this->redirect()->toUrl('list');
           }

           return array(
               'id'    => $id,
               'usuario' => $this->getUsuarioTable()->getUsuario($id)
           );
       }
}
