<?php

class IndexController extends Zend_Controller_Action
{

	public function init()
	{
		/* Initialize action controller here */
	}

	public function indexAction()
	{
		// action body
		$albums             = new Application_Model_DbTable_Albums();
		$this->view->albums = $albums->fetchAll();
	}

	public function addAction()
	{


		$form = new Application_Form_Album(); //    Inicjujemy obiekt klasy Form_Album
		$form->submit->setLabel('Add');//   ustawiamy nazwę przycisku wysyłającego formularz na “Add”
		$this->view->form = $form;//przypisujemy do zmiennej widoku.


//		Jeśli metoda obiektu żądania, isPost() zwraca wartość true, znaczy to że formularz został wysłany,
//dlatego też możemy pobrać przesłane dane za pomocą metody getPost() i sprawdzić ich poprawność za
//pomocą metody isValid() formularza.
//
//		Jeśli przesłane dane są poprawne, inicjuemy obiekt klasy modelu -
//Application_Model_DbTable_Albums i wywołujemy zdefniowaną wcześniej metodę addAlbum() w
//celu zapisania danych w bazie.
//
//		Jeśli przesłane dane są poprawne, inicjuemy obiekt klasy modelu -
//Application_Model_DbTable_Albums i wywołujemy zdefniowaną wcześniej metodę addAlbum() w
//celu zapisania danych w bazie.
		if ($this->getRequest()->isPost())
		{
			$formData = $this->getRequest()->getPost();

			if ($form->isValid($formData))
			{
				$artist = $form->getValue('artist');
				$title  = $form->getValue('title');
				$albums = new Application_Model_DbTable_Albums();
				$albums->addAlbum($artist, $title);
				$this->_helper->redirector('index');
			}
//			Jeśli dane formularza nie przeszły procesu walidacji, wypełniamy pola formularza przesłanymi danymi i
//ponownie wyświetlamy go użytkownikowi.
			else
			{
				$form->populate($formData);
			}
		}
	}

	public function editAction()
	{

		$form = new Application_Form_Album();
		$form->submit->setLabel('Save');
		$this->view->form = $form;

		if ($this->getRequest()->isPost()){
			$formData = $this->getRequest()->getPost();
			if ($form->isValid($formData))
			{
				$id = (int)$form->getValue('id');
				$artist = $form->getValue('artist');
				$title = $form->getValue('title');
				$albums = new Application_Model_DbTable_Albums();
				$albums->updateAlbum($id, $artist, $title);

				$this->_helper->redirector('index');
			}
			else{
				$form->populate($formData);
			}
		}else{
			$id = $this->_getParam('id', 0);

			if ($id > 0)
			{
				$albums = new Application_Model_DbTable_Albums();
				$form -> populate($albums->getAlbum($id));
			}
		}


	}

	public function deleteAction()
	{
		if ($this->getRequest()->isPost())
		{
			$del=$this->getRequest()->getPost('del');
			if ($del == 'Yes'){
				$id = $this->getRequest()->getPost('id');
				$albums = new Application_Model_DbTable_Albums();
				$albums->deleteAlbum($id);
			}
			$this->_helper->redirector('index');
		}else{
			$id=$this->getParam('id', 0);
			$albums = new Application_Model_DbTable_Albums();
			$this->view->album = $albums->getAlbum($id);
		}
	}


}








