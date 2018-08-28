<?php

namespace Admin;

use Admin\Entity\User;
return array (
		'controllers' => array (
				'invokables' => array (
						'Admin\Controller\Index' => 'Admin\Controller\IndexController',
						'Admin\Controller\ServiceLoginAuth' => 'Admin\Controller\ServiceLoginAuthController',
				) 
		),
		'router' => array (
				'routes' => array (
						'Dashboard' => array (
								'type' => 'literal',
								'options' => array (
										'route' => '/index',
										'defaults' => array (
												'__NAMESPACE__' => 'Admin\Controller',
												'controller' => 'Index',
												'action' => 'Index' 
										) 
								) 
						),
						
						'login' => array (
								'type' => 'literal',
								'options' => array (
										'route' => '/',
										'defaults' => array (
												'__NAMESPACE__' => 'Admin\Controller',
												'controller' => 'ServiceLoginAuth',
												'action' => 'Index' 
										) 
								) 
						),
						'service_login' => array (
								'type' => 'literal',
								'options' => array (
										'route' => '/ServiceLoginAuth',
										'defaults' => array (
												'__NAMESPACE__' => 'Admin\Controller',
												'controller' => 'ServiceLoginAuth',
												'action' => 'authenticate' 
										) 
								),
								'may_terminate' => true,
								'child_routes' => array (
										'process' => array (
												'type' => 'Segment',
												'options' => array (
														'route' => '/[:action]',
														'constraints' => array (
																'route' => array (
																		'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
																		'action' => '[a-zA-Z][a-zA-Z0-9_-]*' 
																),
																'defaults' => array () 
														) 
												) 
										) 
								) 
						),
						'logout' => array (
								'type' => 'literal',
								'options' => array (
										'route' => '/logout',
										'defaults' => array (
												'__NAMESPACE__' => 'Admin\Controller',
												'controller' => 'ServiceLoginAuth',
												'action' => 'logout' 
										) 
								) 
						),
						'members' => array (
								'type' => 'literal',
								'options' => array (
										'route' => '/members',
										'defaults' => array (
												'__NAMESPACE__' => 'Admin\Controller',
												'controller' => 'Index',
												'action' => 'members'
										)
								)
						),
						'source_datatable_members' => array (
								'type' => 'literal',
								'options' => array (
										'route' => '/source_datatable_members',
										'defaults' => array (
												'__NAMESPACE__' => 'Admin\Controller',
												'controller' => 'Index',
												'action' => 'sourcedatatablemembers'
										)
								)
						),
						'get_form_cotisation'=> array (
								'type' => 'segment',
								'options' => array (
										'route' => '/get_form_cotisation[/:action][/:id]',
										'constraints' => array (
												'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
												'id' => '[0-9]+'
										),
										'defaults' => array (
												'__NAMESPACE__' => 'Admin\Controller',
												'controller' => 'Index',
												'action' => 'formcotisation'
										)
								)
						),
						'cotisation_processing' => array (
								'type' => 'literal',
								'options' => array (
										'route' => '/cotisation_processing',
										'defaults' => array (
												'__NAMESPACE__' => 'Admin\Controller',
												'controller' => 'Index',
												'action' => 'cotisationprocessing'
										)
								)
						),
						'cotisations' => array (
								'type' => 'literal',
								'options' => array (
										'route' => '/cotisations',
										'defaults' => array (
												'__NAMESPACE__' => 'Admin\Controller',
												'controller' => 'Index',
												'action' => 'cotisations'
										)
								)
						),
						'source_datatable_cotisations' => array (
								'type' => 'literal',
								'options' => array (
										'route' => '/source_datatable_cotisations',
										'defaults' => array (
												'__NAMESPACE__' => 'Admin\Controller',
												'controller' => 'Index',
												'action' => 'sourcedatatablecotisations'
										)
								)
						),
						'get_num_appts' => array (
								'type' => 'literal',
								'options' => array (
										'route' => '/get_num_appts',
										'defaults' => array (
												'__NAMESPACE__' => 'Admin\Controller',
												'controller' => 'Index',
												'action' => 'getnumappartements'
										)
								)
						),
						
						'import_cotisations' => array (
								'type' => 'literal',
								'options' => array (
										'route' => '/import_cotisations',
										'defaults' => array (
												'__NAMESPACE__' => 'Admin\Controller',
												'controller' => 'Index',
												'action' => 'importcotisations'
										)
								)
						),
						
						'get_stats_dashboard' => array (
								'type' => 'literal',
								'options' => array (
										'route' => '/get_stats_dashboard',
										'defaults' => array (
												'__NAMESPACE__' => 'Admin\Controller',
												'controller' => 'Index',
												'action' => 'getstatsdashboard'
										)
								)
						),
						//source_stats_charges_amchart
						'source_stats_charges_amchart' => array (
								'type' => 'literal',
								'options' => array (
										'route' => '/source_stats_charges_amchart',
										'defaults' => array (
												'__NAMESPACE__' => 'Admin\Controller',
												'controller' => 'Index',
												'action' => 'sourcestatschargesamchart'
										)
								)
						),
						'charges' => array (
								'type' => 'literal',
								'options' => array (
										'route' => '/charges',
										'defaults' => array (
												'__NAMESPACE__' => 'Admin\Controller',
												'controller' => 'Index',
												'action' => 'charges'
										)
								)
						),
						'source_datatable_charges' => array (
								'type' => 'literal',
								'options' => array (
										'route' => '/source_datatable_charges',
										'defaults' => array (
												'__NAMESPACE__' => 'Admin\Controller',
												'controller' => 'Index',
												'action' => 'sourcedatatablecharges'
										)
								)
						),
						'get_select_type_charge' => array (
								'type' => 'literal',
								'options' => array (
										'route' => '/get_select_type_charge',
										'defaults' => array (
												'__NAMESPACE__' => 'Admin\Controller',
												'controller' => 'Index',
												'action' => 'getselectTypecharge'
										)
								)
						),
						//FORM CHARGE
						'get_form_charge'=> array (
								'type' => 'segment',
								'options' => array (
										'route' => '/get_form_charge[/:action][/:id]',
										'constraints' => array (
												'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
												'id' => '[0-9]+'
										),
										'defaults' => array (
												'__NAMESPACE__' => 'Admin\Controller',
												'controller' => 'Index',
												'action' => 'formcharge'
										)
								)
						),
						//charge_processing
						'charge_processing' => array (
								'type' => 'literal',
								'options' => array (
										'route' => '/charge_processing',
										'defaults' => array (
												'__NAMESPACE__' => 'Admin\Controller',
												'controller' => 'Index',
												'action' => 'chargeprocessing'
										)
								)
						),
						'delete_charge' => array (
								'type' => 'literal',
								'options' => array (
										'route' => '/delete_charge',
										'defaults' => array (
												'__NAMESPACE__' => 'Admin\Controller',
												'controller' => 'Index',
												'action' => 'deletecharge'
										)
								)
						),
						'history' => array (
								'type' => 'literal',
								'options' => array (
										'route' => '/history',
										'defaults' => array (
												'__NAMESPACE__' => 'Admin\Controller',
												'controller' => 'Index',
												'action' => 'history'
										)
								)
						),
						'source_datatable_history' => array (
								'type' => 'literal',
								'options' => array (
										'route' => '/source_datatable_history',
										'defaults' => array (
												'__NAMESPACE__' => 'Admin\Controller',
												'controller' => 'Index',
												'action' => 'sourcedatatablehistory'
										)
								)
						),
						//IF EXIST COTISATION
						'check_if_exist_cotisation'=> array (
								'type' => 'segment',
								'options' => array (
										'route' => '/check_if_exist_cotisation[/:action][/:n]',
										'constraints' => array (
												'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
												'n' => '[0-9]+'
										),
										'defaults' => array (
												'__NAMESPACE__' => 'Admin\Controller',
												'controller' => 'Index',
												'action' => 'checkIfExistCotisation'
										)
								)
						),
				)
				 
		),
		'service_manager' => array(
				'abstract_factories' => array(
						'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
						'Zend\Log\LoggerAbstractServiceFactory',
				),
				'factories' => array(
						'translator' => 'Zend\Mvc\Service\TranslatorServiceFactory',
				),
		),
		'translator' => array(
				'locale' => 'fr_FR',
				'translation_file_patterns' => array(
						array(
								'type'     => 'gettext',
								'base_dir' => __DIR__ . '/../language',
								'pattern'  => '%s.mo',
						),
				),
		),
		'view_manager' => array (
				'display_not_found_reason' => true,
				'display_exceptions'       => true,
				'doctype'                  => 'HTML5',
				'not_found_template'       => 'error/404',
				'exception_template'       => 'error/index',
				'template_map' => array(
						'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
						'navigation_bo_mywebtoprint' => __DIR__ . '/../view/navigation/navigation.phtml',
						'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
						'error/404'               => __DIR__ . '/../view/error/404.phtml',
						'error/index'             => __DIR__ . '/../view/error/index.phtml',
				),
				'template_path_stack' => array (
						'admin' => __DIR__ . '/../view' 
				),
				'strategies' => array (
						'ViewJsonStrategy' 
				) 
		),
		
		'module_config' => array (
				'upload_location' => 'uploads/documents',
				'excel_location' => 'public/export',
				'avatar_location' => 'public/img',
		),		
		'controller_plugins' => array (
				'invokables' => array (
						'AdminTools' => 'Admin\Tools\AdminTools',
				)
		),
		/**
		 * La configuration doctrine pour le module Admin
		 */
		'doctrine' => array (
				'driver' => array (
						__NAMESPACE__ . '_driver' => array (
								'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
								'cache' => 'array',
								'paths' => array (
										__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity'
								)
						),
						'orm_default' => array (
								'drivers' => array (
										__NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
								)
						)
				),
				'authentication' => array (
						'orm_default' => array (
								'object_manager' => 'Doctrine\ORM\EntityManager',
								'identity_class' => 'Admin\Entity\User',
								'identity_property' => 'login',
								'credential_property' => 'password',
								'credential_callable' => function (User $user, $passwordGiven) {
									
								if ($user->getPassword () == sha1 ( $passwordGiven ) ) {
									return true;
								}
								return false;
								}
								)
						)
				)
);