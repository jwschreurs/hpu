<?php
namespace Blog\Form;
 
use Zend\Form\Form;
 
class BlogForm extends Form
{
    public function __construct($title = null)
    {
        parent::__construct('blog');
        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype','multipart/form-data');
         
        $this->add(array(
             'name' => 'id',
             'type' => 'Hidden',
         ));
        $this->add(array(
            'name' => 'Title',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Titel',
            ),
        ));
		 $this->add(array(
            'name' => 'Text',
            'attributes' => array(
            'id' => 'Text',
            'type' => 'textarea',
            'cols' => '40',
            'rows' => '20',
            ),
            'options' => array(
                'label' => 'Tekst',
            ),
        ));
        
        $this->add(array(
            'name' => 'Image',
            'attributes' => array(
                'type'  => 'file',
            ),
            'options' => array(
                'label' => 'Afbeelding',
            ),
        ));
		
         
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Opslaan'
            ),
        )); 
    }
}
?>