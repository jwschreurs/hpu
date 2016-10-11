<?php
namespace Blog;

 return array(
     'controllers' => array(
         'invokables' => array(
             'Blog\Controller\Blog' => 'Blog\Controller\BlogController',
         ),
     ),
     
	 'router' => array(
         'routes' => array(
             'blog' => array(
                 'type'    => 'segment',
                 'options' => array(
                     'route'    => '/blog[/:action][/:id]',
                     'constraints' => array(
                         'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                         'id'     => '[0-9]+',
                     ),
                     'defaults' => array(
                         'controller' => 'Blog\Controller\Blog',
                         'action'     => 'index',
                     ),
                 ),
             ),
         ),
     ),
     
	 
     'view_manager' => array(
         'template_path_stack' => array(
             'blog' => __DIR__ . '/../view',
         ),
     ),

    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                )
            )
        )
    )
);

?>