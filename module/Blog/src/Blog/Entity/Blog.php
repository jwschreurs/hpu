<?php
 
namespace Blog\Entity;
 
use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface; 
 
/**
 * A Blog
 *
 * @ORM\Entity
 * @ORM\Table(name="blog")
 * @property string $title
 * @property string $text
 * @property string $image
 * @property string $movie
 * @property int $id
 */
class Blog implements InputFilterAwareInterface 
{
	protected $inputFilter;
   /**
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    * @ORM\Column(type="integer")
    */
    private $id;
	/** @ORM\Column(type="string") */
    private $title;
	/** @ORM\Column(type="string") */
 	private $text;
	/** @ORM\Column(type="string") */
	private $image;
	/** @ORM\Column(type="string") */
	private $movie;
 
 	public function getArrayCopy() 
    {
        return get_object_vars($this);
    }
	
	
	 /**
     * Populate from an array.
     *
     * @param array $data
     */
    public function exchangeArray ($data = array()) 
    {
        $this->id = $data['id'];
        $this->title = $data['Title'];
        $this->text = $data['Text'];
		$this->image = $data['Image'];
		//$this->image = $data['Movie'];
    }
    
	public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }
    
	public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $inputFilter->add(array(
                'name'     => 'id',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            ));
            $inputFilter->add(array(
                'name'     => 'Title',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
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
            ));
            $inputFilter->add(array(
                'name'     => 'Text',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                        ),
                    ),
                ),
            ));
			
			$inputFilter->add(
			array(
			    'type'       => 'Zend\InputFilter\FileInput',
			    'name'       => 'Image',
			    'required'   => false,
			));
            $this->inputFilter = $inputFilter;
        }
        return $this->inputFilter;
    }
    
    public function getId()
    {
        return $this->id;
    }
 
    
    public function setTitle($title)
    {
        $this->title = $title;
     
        return $this;
    }
 
    
    public function getTitle()
    {
        return $this->title;
    }
 
 
    public function setText($text)
    {
        $this->text = $title;
     
        return $this;
    }
 
    public function getText()
    {
        return $this->text;
    }
	
	public function setImage($image)
    {
        $this->image = $image;
     
        return $this;
    }
 
    public function getImage()
    {
        return $this->image;
    }
	
	public function setMovie($movie)
    {
        $this->movie = $movie;
     
        return $this;
    }
 
    public function getMovie()
    {
        return $this->movie;
    }
	public function truncate($text, $length = 100, $options = array())
    {
        $default = array(
            'ending' => '...', 'exact' => false
        );
        $options = array_merge($default, $options);
        extract($options);

        if (mb_strlen($text) <= $length) {
            return $text;
        } else {
            $truncate = mb_substr($text, 0, $length - mb_strlen($ending));
        }

        if (!$exact) {
            $spacepos = mb_strrpos($truncate, ' ');
            if (isset($spacepos)) {
                $truncate = mb_substr($truncate, 0, $spacepos);
            }
        }
        $truncate .= $ending;
        return $truncate;
    }
    
}