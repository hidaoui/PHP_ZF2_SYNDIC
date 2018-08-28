<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Authentication\AuthenticationService;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\Session\Container;
use Admin\Entity\User;

class ServiceLoginAuthController extends AbstractActionController {
	protected $storage;
	protected $authservice;
	protected $em = null;
	
	/**
	 * getEntityManager
	 */
	public function getEntityManager() {
		if (null === $this->em) {
			$this->em = $this->getServiceLocator ()->get ( 'doctrine.entitymanager.orm_default' );
		}
		return $this->em;
	}
	public function getAuthService() {
		if (! $this->authservice) {
			$this->authservice = $this->getServiceLocator ()->get ( 'Zend\Authentication\AuthenticationService' );
		}
		return $this->authservice;
	}
	public function getSessionStorage() {
		if (! $this->storage) {
			$this->storage = $this->getServiceLocator ()->get ( 'Admin\Model\MyAuthStorage' );
		}
		return $this->storage;
	}
	
	/**
	 * Formulaire de login
	 *
	 * {@inheritDoc}
	 *
	 * @see \Zend\Mvc\Controller\AbstractActionController::indexAction()
	 */
	public function indexAction() {
		return (new ViewModel ())->setTerminal ( true );
	}
	
	/**
	 * Formulaire de login operator
	 *
	 * {@inheritDoc}
	 *
	 * @see \Zend\Mvc\Controller\AbstractActionController::indexAction()
	 */
	public function connexionAction() {
		return (new ViewModel ())->setTerminal ( true );
	}
	
	/**
	 * La fonction authenticate
	 */
	public function authenticateAction() {
		$redirect = "login";
		
		$request = $this->getRequest ();
		
		if ($request->isPost ()) {
			$data = $this->getRequest ()->getPost ();
			// check authentification
			$this->getAuthService ()->getAdapter ()->setIdentity ( $data ['login'] )->setCredential ( $data ['password'] );
			$result = $this->getAuthService ()->authenticate ();
			if ($result->isValid ()) {
				// redirect to home route, Todo: remember orginal route and redirect there after authentification
				$authenticationService = new AuthenticationService ();
				$authenticationService = $this->getServiceLocator ()->get ( 'Zend\Authentication\AuthenticationService' );
				$loggedUser = $authenticationService->getIdentity ();
				// Si le user est blocker
				if ($loggedUser->getIsactive () != 0) {
					$redirect = 'index';
					$rankUser = $loggedUser->getRankuser ();
					$this->getSessionStorage ()->write ( $data ['login'] );
					
					//Last login date
					$loggedUser->setLastlogindate( time() );
					$this->getEntityManager ()->persist ( $loggedUser);
					$this->getEntityManager ()->flush ();
					
					$responseJson = array (
							'success' => true,
							'redirect' => $redirect 
					);
				} else {
					// error_log('Utilisateur blocker');
					$responseJson = array (
							'success' => false 
					);
				}
			} else {
				$responseJson = array (
						'success' => false 
				);
			}
		}
		
		return new JsonModel ( $responseJson );
	}
	public function connexionmyehcgAction() {
		$idUser = ( int ) $this->getEvent ()->getRouteMatch ()->getParam ( 'id' );
		$redirect = "login";
		
		$request = $this->getRequest ();
		
		//if ($request->isPost ()) {
			//$data = $this->getRequest ()->getPost ();
			
			if ($idUser > 0) {
				
				$user = $this->getEntityManager ()->find ( 'Admin\Entity\User', $idUser );
				//$user = new User ();
				if ($user) {
					// check authentification
					$this->getAuthService ()->getAdapter ()->setIdentity ( $user->getLoginuser () )->setCredential ( $user->getPassworduser () );
					$result = $this->getAuthService ()->authenticate ();
					if ($result->isValid ()) {
						// redirect to home route, Todo: remember orginal route and redirect there after authentification
						$authenticationService = new AuthenticationService ();
						$authenticationService = $this->getServiceLocator ()->get ( 'Zend\Authentication\AuthenticationService' );
						$loggedUser = $authenticationService->getIdentity ();
						// Si le user est blocker
						if ($loggedUser->getIsactiveuser () != 0) {
							$redirect = 'Dashboard';
							$rankUser = $loggedUser->getRankuser ();
							switch ($rankUser) {
								case 10 :
									$redirect = 'suivi_postes';
									break;
							}
							$this->getSessionStorage ()->write ( $data ['login'] );
							$responseJson = array (
									'success' => true,
									'redirect' => $redirect 
							);
						}
					}
				}
			}
		//}
		
		return $this->redirect ()->toRoute ( $redirect );
	}
	/**
	 * La fonction logout
	 */
	public function logoutAction() {
		$authenticationService = new AuthenticationService ();
		$authenticationService = $this->getServiceLocator ()->get ( 'Zend\Authentication\AuthenticationService' );
		$loggedUser = $authenticationService->getIdentity ();
		$redirect = $this->redirect ()->toRoute ( "login" );
		if($loggedUser->getRankuser () == 10){
			$redirect = $this->redirect ()->toRoute ( "connexion" );
		}
		$this->getSessionStorage ()->forgetMe ();
		$this->getAuthService ()->clearIdentity ();
		$user_session = new Container ( 'user' );
		$user_session->bip_etat = null;
		$user_session->bip_code = null;
		$user_session->bip_poste = null;
		$user_session->bip_machine = null;
		
		$this->flashMessenger ()->addMessage ( "You have been logged out!" );
		return $redirect;
	}
}

