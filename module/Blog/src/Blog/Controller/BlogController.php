<?php
namespace Blog\Controller;

 
 use Zend\Mvc\Controller\AbstractActionController;
 use Zend\View\Model\ViewModel;
 use Blog\Entity\Blog;
 use Blog\Form\BlogForm;
 use Zend\Validator\File\Size;
 use Doctrine\ORM\EntityManager;

 class BlogController extends AbstractActionController
 {
 	protected $em;
	
    public function getEntityManager()
    {
        if (null === $this->em) {
            $this->em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        }
        return $this->em;
    }
	
	public function indexAction()
    {
        return new ViewModel(array(
            'blogs' => $this->getEntityManager()->getRepository('Blog\Entity\Blog')->findAll(),
        ));
    } 
	
	public function detailAction()
    {
    	$id = (int) $this->params()->fromRoute('id', 0);
		return new ViewModel(array(
            'blog' => $this->getEntityManager()->find('Blog\Entity\Blog', $id),
        ));
		
    } 
    
    public function addAction()
    {
   		$form = new BlogForm();
        $request = $this->getRequest();  
        if ($request->isPost()) {
             
            $blog = new Blog();
            $form->setInputFilter($blog->getInputFilter());
             
            $nonFile = $request->getPost()->toArray();
            $File    = $this->params()->fromFiles('Image');
            $data = array_merge(
                 $nonFile,
                 array('Image'=> $File['name'])
             );
			
            $form->setData($data);
            if ($form->isValid()) {
			    $size = new Size(array('min'=>2000000)); 
                 
                $adapter = new \Zend\File\Transfer\Adapter\Http();
                $adapter->setValidators(array($size), $File['name']);
                if (!$adapter->isValid()){
                    $dataError = $adapter->getMessages();
                    $error = array();
                    foreach($dataError as $key=>$row)
                    {
                        $error[] = $row;
                    }
                    $form->setMessages(array('Image'=>$error ));
                } else {
					
                	$adapter->setDestination('.\public\Assets');
					$adapter->addFilter('File\Rename', array('target' => $adapter->getDestination() .DIRECTORY_SEPARATOR . rand(2,1000).'.jpg','overwrite' => true));
					$adapter->receive();
					$filename = explode($adapter->getDestination().DIRECTORY_SEPARATOR,$adapter->getFileName('Image'));
					$data = array_merge(
		                 $nonFile,
		                 array('Image'=> $filename[1])
		             );	
					$form->setData($data);
					 if ($form->isValid()) {
		                $blog->exchangeArray($form->getData());
		                $this->getEntityManager()->persist($blog);
		                $this->getEntityManager()->flush();
		                
		                return $this->redirect()->toRoute('blog');
					 }
                   
			    }
			} 
			else {
			    foreach ($form->getMessages() as $messageId => $message) {
			       var_dump($messageId);	
			       var_dump($message);
			    } 
        	}
    	}
         $id = (int) $this->params()->fromRoute('id', 0); 
        return array('form' => $form, 'id' => $id);     	
        
    }
	
 
    public function editAction()
    {
    	
	      $form = new BlogForm();
		  $request = $this->getRequest();
		  if (!$request->isPost()) {
	   		if ($this->params('id') > 0) {
		   		$id = $this->params('id');
			   
			    $blog =  $this->getEntityManager()->find('Blog\Entity\Blog', $id);
				$form->bind($blog);
				return array('form' => $form, 'id' => $id , 'blog' => $blog);
	        }
	  	}  
		 else if ($request->isPost()) {
            return $this->AddAction();
    	}
         $id = (int) $this->params()->fromRoute('id', 0); 
        return array('form' => $form, 'id' => $id);
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
         if (!$id) {
             return $this->redirect()->toRoute('blog');
         }

         $request = $this->getRequest();
         if ($request->isPost()) {
         	$del = $request->getPost()->toArray();
             if ($del["del"] == 'Yes') {
                $feature = $this->getEntityManager()->find('Blog\Entity\Blog', $id);
		            if ($feature) {
		                $this->getEntityManager()->remove($feature);
		                $this->getEntityManager()->flush();
		            }
             }

             return $this->redirect()->toRoute('blog');
         }

		return new ViewModel(array(
            'blog' => $this->getEntityManager()->find('Blog\Entity\Blog', $id),
        ));
    }
	
}
?>