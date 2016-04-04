<?php
namespace Localizacao\Controller;

use Localizacao\Form\CidadeForm;
use Localizacao\Model\Cidade;
use SebastianBergmann\RecursionContext\Exception;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;


class CidadeController extends AbstractActionController {

    protected $cidadeTable;

    public function listAction() {
        return new ViewModel(
                array(
            "cidades" => $this->getCidadeTable()->fetchAll()
        ));
    }

    public function getCidadeTable() {
        if (!$this->cidadeTable) {
            $sm = $this->getServiceLocator();
            $this->cidadeTable = $sm->get('Localizacao\Model\CidadeTable');
        }
        return $this->cidadeTable;
    }
    public function addAction()
    {
        $form = new CidadeForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $cidade = new Cidade();
            $form->setInputFilter($cidade->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $cidade->exchangeArray($form->getData());
                $this->getCidadeTable()->salvarCidade($cidade);
                return $this->redirect()->toUrl("/localizacao/cidade/list");
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
    		$cidade = $this->getCidadeTable()->getCidade($id);
    	}
    	catch (Exception $ex) {
    		return $this->redirect()->toUrl('list');
    	}
    
    	$form  = new CidadeForm();
    	$form->bind($cidade);
    
    	$request = $this->getRequest();
    	if ($request->isPost()) {
    		$form->setInputFilter($cidade->getInputFilter());
    		$form->setData($request->getPost());
    
    		if ($form->isValid()) {
    			$this->getCidadeTable()->salvarCidade($form->getData());
    
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
            return $this->redirect()->toUrl('/localizacao/cidade/list');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'Nao');

            if ($del == 'Sim') {
                $id = (int) $request->getPost('id');
                $this->getCidadeTable()->deletarCidade($id);
            }
            return $this->redirect()->toUrl('list');
        }

        return array(
            'id'    => $id,
            'cidade' => $this->getCidadeTable()->getCidade($id)
        );
    }
}
