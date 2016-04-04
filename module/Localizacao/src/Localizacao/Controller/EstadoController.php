<?php
namespace Localizacao\Controller;

use Localizacao\Form\EstadoForm;
use Localizacao\Model\Estado;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;


class EstadoController extends AbstractActionController {

    protected $estadoTable;

    public function listAction() {
        return new ViewModel(
                array(
            "estados" => $this->getEstadoTable()->fetchAll()
        ));
    }

    public function getEstadoTable() {
        if (!$this->estadoTable) {
            $sm = $this->getServiceLocator();
            $this->estadoTable = $sm->get('Localizacao\Model\EstadoTable');
        }
        return $this->estadoTable;
    }
    public function addAction()
    {
        $form = new EstadoForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $estado = new Estado();
            $form->setInputFilter($estado->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $estado->exchangeArray($form->getData());
                $this->getEstadoTable()->salvarEstado($estado);
                return $this->redirect()->toUrl("/localizacao/estado/list");
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
    		$estado = $this->getEstadoTable()->getEstado($id);
    	}
    	catch (Exception $ex) {
    		return $this->redirect()->toUrl('list');
    	}
    
    	$form  = new EstadoForm();
    	$form->bind($estado);
    
    	$request = $this->getRequest();
    	if ($request->isPost()) {
    		$form->setInputFilter($estado->getInputFilter());
    		$form->setData($request->getPost());
    
    		if ($form->isValid()) {
    			$this->getEstadoTable()->salvarEstado($form->getData());
    			return $this->redirect()->toUrl('add');
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
            return $this->redirect()->toUrl('/localizacao/estado/list');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'Nao');

            if ($del == 'Sim') {
                $id = (int) $request->getPost('id');
                $this->getEstadoTable()->deletarEstado($id);
            }
            return $this->redirect()->toUrl('list');
        }

        return array(
            'id'    => $id,
            'estado' => $this->getEstadoTable()->getEstado($id)
        );
    }
}
