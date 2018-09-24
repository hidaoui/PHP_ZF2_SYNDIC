<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Authentication\AuthenticationService;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Admin\Entity\User;
use Admin\Entity\Member;
use Admin\Entity\Cotisation;
use Admin\Entity\TypeCharge;
use Admin\Entity\Charge;
use Admin\Entity\History;

class IndexController extends AbstractActionController {
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
	public function indexAction() {		
		// $d=\PHPExcel_Style_NumberFormat::toFormattedString(42948,\PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
		
		// var_dump($d);
		//$admin_tools = $this->AdminTools ();
		//$t=$admin_tools->getHeaderCalendar();
		//var_dump($t);exit();
		// $id=$admin_tools->getIdMember(0);
		
		// var_dump($id);
		return new ViewModel ();
	}
	public function membersAction() {
		return new ViewModel ();
	}
	public function sourcedatatablemembersAction() {
		$qb = $this->getEntityManager ()->createQueryBuilder ();
		$admin_tools = $this->AdminTools ();
		$request = $this->getRequest ();
		
		$select=$qb->select ( 's' )->from ( 'Admin\Entity\Member', 's' )->orderBy ( 's.numappt', 'ASC' );
		if ($request->isPost ()) {
			$filtre = $request->getPost ();
			$numAppartement = $filtre ['SELECT_NUM_APPT'];
			if (isset ( $numAppartement ) && $numAppartement!='TOUT') {
				$select->where ( 's.numappt = :numappt' )->setParameter ( 'numappt', $numAppartement );
			}
		}
		
		$members = $qb->getQuery ()->getResult ();
		$tabjson = array ();
		if (count ( $members ) > 0) {
			foreach ( $members as $s ) {
				// $s=new Member();
				
				$tabvalues = array ();
				$tabvalues [] = '<input type="checkbox" class="checkboxes" value="' . $s->getId () . '" />';
				// <th>Num appt</th>
				$numAppt = $s->getNumappt ();
				if ($s->getNumappt () == 0) {
					$numAppt = 'CAFÉ';
				}
				
				$span = '<span class="label label-sm bg-blue-ebonyclay">' . $numAppt . '</span>';
				
				$tabvalues [] = $span;
				// <th>Nom</th>
				$tabvalues [] = $s->getFirstname () . ' ' . $s->getLastname ();
				// <th>Tél</th>
				$tabvalues [] = $s->getTel ();
				// <th>Infos</th>
				
				$strpayed='';
				$result=$admin_tools->getMoisPayed ( $s->getNumappt () );
				if (count($result)>0){
					foreach ($result as $m){
						$strpayed.= "<span class='badge badge-success badge-roundless'>".$m."</span> ";
					}
				
				}
				
				$nbMoisPays = $admin_tools->getNbMoisPays ( $s->getNumappt () );
				$spanPayes='<a data-container="body" data-trigger="hover" data-trigger="hover" data-placement="top" data-html="true" data-content="'.$strpayed.'" data-original-title="'.$nbMoisPays.' Mois payés" class="popovers" style="cursor:pointer;"><span class="label label-sm bg-green">'.$nbMoisPays.' mois payés </span></a>';
				$tabvalues [] = $spanPayes;
				
				//<th>Mois impayés</th>
				$impayes='';
				$r=$admin_tools->getImpayedMois ( $s->getNumappt () );
				$nbMoisImpayees=count($r);
				if (count($r)>0){
					foreach ($r as $v){
						$impayes.= "<span class='badge badge-danger badge-roundless'>".$v.'</span> ';
					}
						
				}
				$spanImpayees='<a data-container="body" data-trigger="hover" data-trigger="hover" data-placement="top" data-html="true" data-content="'.$impayes.'" data-original-title="'.$nbMoisImpayees.' Mois impayés" class="popovers" style="cursor:pointer;"><span class="label label-sm label-danger">'.$nbMoisImpayees.' mois impayés </span></a>';
				$tabvalues [] = $spanImpayees;
				// <th>Total</th>
				$total = $admin_tools->getTotalCotisation ( $s->getNumappt () );
				$tabvalues [] = '<span class="label label-sm bg-green-seagreen">' . $total . '</span>';
				
				// <th>Action</th>
				$btn_add = '<button data-toggle="tooltip" title="Ajouter une cotisation" onclick="ADD_COTISATION(' . $s->getId () . ')"  class="btn btn-xs blue"><i class="fa fa-plus"></i></button>';
				
				$tabvalues [] = $btn_add;
				
				$tabjson [] = $tabvalues;
			}
		}
		$resultsajax = array (
				"aaData" => $tabjson 
		);
		echo json_encode ( $resultsajax );
		exit ();
	}
	public function formcotisationAction() {
		$idMembre = ( int ) $this->getEvent ()->getRouteMatch ()->getParam ( 'id' );
		$m = null;
		if ($idMembre > 0) {
			$m = $this->getEntityManager ()->find ( 'Admin\Entity\Member', $idMembre );
		}
		$viewModel = new ViewModel ();
		$viewModel->setVariables ( array (
				'm' => $m 
		) )->setTerminal ( true );
		return $viewModel;
	}
	public function cotisationprocessingAction() {
		$request = $this->getRequest ();
		$success = true;
		$tools_id_contact = 0;
		$title = '<i style="font-size:22px" class="fa fa-times"></i> Erreur';
		$message = '<p>Erreur d\'enregistrement.<p>';
		if ($request->isPost ()) {
			$data_posted = $request->getPost ();
			
			$data = array (
					"COTISATION_ID" => 0,
					"COTISATION_MEMBER_ID" => (isset ( $data_posted ["COTISATION_MEMBER_ID"] )) ? $data_posted ["COTISATION_MEMBER_ID"] : null,
					"COTISATION_MEMBER_APPT" => (isset ( $data_posted ["COTISATION_MEMBER_APPT"] )) ? $data_posted ["COTISATION_MEMBER_APPT"] : null,
					"COTISATION_DATE" => (isset ( $data_posted ["COTISATION_DATE"] )) ? new \DateTime ( $data_posted ["COTISATION_DATE"] ) : null,
					"COTISATION_ANNEE" => (isset ( $data_posted ["COTISATION_ANNEE"] )) ? $data_posted ["COTISATION_ANNEE"] : null,
					"COTISATION_MOIS" => (isset ( $data_posted ["COTISATION_MOIS"] )) ? $data_posted ["COTISATION_MOIS"] : null,
					"COTISATION_MONTANT" => (isset ( $data_posted ["COTISATION_MONTANT"] )) ? $data_posted ["COTISATION_MONTANT"] : null 
			);
			
			$admin_tools = $this->AdminTools ();
			$tools_id = $admin_tools->manageCotisation ( $data );
			
			if ($tools_id > 0) {
				
				// Ajouter historique
				$authenticationService = new AuthenticationService ();
				$authenticationService = $this->getServiceLocator ()->get ( 'Zend\Authentication\AuthenticationService' );
				$loggedUser = $authenticationService->getIdentity ();
				
				$str = 'de l\'appartement n° ' . $data_posted ["COTISATION_MEMBER_APPT"];
				if ($data_posted ["COTISATION_MEMBER_APPT"] == 0) {
					$str = 'du CAFÉ';
				}
				
				$desc = $loggedUser->getFirstnameuser () . ' À ajouter une cotisation de ' . $data_posted ["COTISATION_MONTANT"] . ' dhs ' . $str . ' pour le ' . $data_posted ["COTISATION_MOIS"] . '/' . $data_posted ["COTISATION_ANNEE"];
				$data_history = array (
						'HISTORY_ID' => 0,
						'HISTORY_DATE' => new \DateTime (),
						'HISTORY_DESC' => strtoupper ( $desc ) 
				);
				$tools_id_history = $admin_tools->manageHistory ( $data_history );
				
				$title = '<i style="font-size:22px" class="fa fa-check"></i> Succès';
				$message = '<p>Mise a jour avec succès.<p>';
			}
		}
		$responseJson = array (
				'success' => $success,
				'title' => $title,
				'message' => $message,
				'id' => $tools_id 
		);
		return new JsonModel ( $responseJson );
		exit ();
	}
	public function cotisationsAction() {
		$admin_tools = $this->AdminTools ();
		$tab_header = $admin_tools->getHeaderCalendar (  );
		
		$dNow=new \DateTime();
		$myNow=$dNow->format('m-Y');
		
		
		$viewModel = new ViewModel ();
		$viewModel->setVariables ( array (
				'tab_header' => $tab_header,
				'myNow' => $myNow,
		) );
		return $viewModel;
	}
	public function sourcedatatablecotisationsAction() {
		$qb = $this->getEntityManager ()->createQueryBuilder ();
		$request = $this->getRequest ();
		
		$select =$qb->select ( 'm' )->from ( 'Admin\Entity\Member', 'm' )->orderBy ( 'm.numappt', 'ASC' );
		if ($request->isPost ()) {
			$filtre = $request->getPost ();	
			$numAppartement = $filtre ['SELECT_NUM_APPT'];
			if (isset ( $numAppartement ) && $numAppartement!='TOUT') {
				$select->where ( 'm.numappt = :numappt' )->setParameter ( 'numappt', $numAppartement );
			}
		}
		$results = $qb->getQuery ()->getResult ();
		
		$tabjson = array ();
		
		$admin_tools = $this->AdminTools ();
		$tab_header = $admin_tools->getHeaderCalendar ();

		if (count ( $results ) > 0) {
			foreach ( $results as $m ) {
					//$m=new Member();
				
					$tabvalues = array ();
					//Checkbox
					$tabvalues [] = '<input type="checkbox" class="checkboxes" value="' . $m->getId() . '" />';
					//Num appt
					$numAppt = $m->getNumappt();
					if ($m->getNumappt () == 0) {
						$numAppt = 'CAFÉ';
					}
					$span = '<span class="label label-sm bg-blue-ebonyclay">' . $numAppt . '</span>';
					
					$tabvalues [] = $span;
					
					foreach ($tab_header as $h){
						
						$tab=explode('-', $h);
						$mois=$tab[0];
						$year=$tab[1];
						
						//Vérifier si le mois est payé
						$success = $admin_tools->checkIfPayedMonth ($m->getNumappt(),$mois,$year);
						$fa='<span class="label label-sm label-danger"><i class="fa fa-times"></i></span>';
						if ($success){
							$fa='<span class="label label-sm label-success"><i class="fa fa-check"></i></span>';
						}
						$tabvalues [] = $fa;
						
					}
		
				$tabjson [] = $tabvalues;
			}
		}
		$resultsajax = array (
				"aaData" => $tabjson 
		);
		echo json_encode ( $resultsajax );
		exit ();
	}
	public function getnumappartementsAction() {
		$json = array ();
		$qb = $this->getEntityManager ()->createQueryBuilder ();
		$qb->select ( 'm' )->from ( 'Admin\Entity\Member', 'm' );
		$members = $qb->getQuery ()->getResult ();
		$json ["TOUT"] = 'Tout';
		if (count ( $members ) > 0) {
			foreach ( $members as $m ) {
				// if ($m->getNumappt () != null) {
				
				$json ['"' . $m->getNumappt () . '"'] = ($m->getNumappt () == 0) ? 'CAFÉ' : $m->getNumappt ();
				// }
			}
		}
		echo json_encode ( $json );
		exit ();
	}
	public function importcotisationsAction() {
		$request = $this->getRequest ();
		$admin_tools = $this->AdminTools ();
		$success = true;
		$error = 0;
		if ($request->isPost ()) {
			$data_posted = $request->getPost ();
			$data_files = $request->getFiles ()->toArray ();
			if (count ( $data_files ) > 0) {
				$file_name = $data_files ['UPLOADED_FILES'] ['name'];
				$config = $this->getServiceLocator ()->get ( 'config' );
				$uploadPath = $config ['module_config'] ['excel_location'];
				$_destinationPath = $uploadPath . '/Importation/';
				if (! file_exists ( $_destinationPath )) {
					if (! mkdir ( $_destinationPath, 0777, true )) {
						error_log ( 'Echec lors de la création des répertoires qui contient les exports xlxs...' );
					}
				}
				$path_xlsx = $_destinationPath . $file_name;
				if (move_uploaded_file ( $data_files ["UPLOADED_FILES"] ["tmp_name"], $path_xlsx )) {
					$headerRow = 1;
					$globalArray = $admin_tools->getXlsData ( $path_xlsx, $headerRow );
					
					foreach ( $globalArray as $data ) {
						foreach ( $data as $k => $v ) {
							//var_dump($k);var_dump($v);
							
							$data_cotisation = array ();
							
							if ($k == 'APPT') {
								$numAppt = $v;
							} else {
								$date = $k;
								$m = $v;
							}
							
							if (isset ( $date ) && isset ( $m ) && isset ( $numAppt )) {
								if ($m > 0 && $date > 0) {
									// $d=\PHPExcel_Style_NumberFormat::toFormattedString($date,\PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
									$d = \PHPExcel_Style_NumberFormat::toFormattedString ( $date, \PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD );
									$dCotisation = new \DateTime ( $d );
									$data_cotisation = array (
											"COTISATION_ID" => 0,
											"COTISATION_MEMBER_ID" => $admin_tools->getIdMember ( $numAppt ),
											"COTISATION_MEMBER_APPT" => $numAppt,
											"COTISATION_DATE" => $dCotisation,
											"COTISATION_ANNEE" => $dCotisation->format ( 'Y' ),
											"COTISATION_MOIS" => $dCotisation->format ( 'm' ),
											"COTISATION_MONTANT" => $m 
									);
								}
							}
							var_dump($data_cotisation);
							// die();
							
							if (count ( $data_cotisation ) > 0) {
								$tools_id = $admin_tools->manageCotisation ( $data_cotisation );
								if (! $tools_id > 0) {
									$error ++;
								}
							}
						}
						// var_dump($data_cotisation);
						// die();
					}
				}
			}
		}
		if ($error > 0) {
			$success = false;
		}
		$responseJson = array (
				'success' => $success 
		);
		return new JsonModel ( $responseJson );
		exit ();
	}
	public function getstatsdashboardAction() {
		$admin_tools = $this->AdminTools ();
		$totalCotisation = $admin_tools->getTotalCotisation ( null );
		$totalCharge = $admin_tools->getTotalCharges ();
		$reste = $totalCotisation - $totalCharge;
		$data = array (
				'total_cotisations' => number_format($totalCotisation,2). ' dhs',
				'total_charges' => number_format($totalCharge,2). ' dhs',
				'reste' => number_format($reste,2). ' dhs' 
		);
		return new JsonModel ( $data );
		exit ();
	}
	public function sourcestatschargesamchartAction() {
		$dataSet = array ();
		$admin_tools = $this->AdminTools ();
		
		$qb = $this->getEntityManager ()->createQueryBuilder ();
		$qb->select ( 't' )->from ( 'Admin\Entity\TypeCharge', 't' );
		$result = $qb->getQuery ()->getResult ();
		
		if (count ( $result ) > 0) {
			foreach ( $result as $t ) {
				$total = $admin_tools->getTotalChargesByType ( $t->getCode () );
				$dataSet [] = array (
						"charge" => $t->getLabel (),
						"total" => $total 
				);
			}
		}
		
		echo json_encode ( $dataSet );
		exit ();
	}
	public function chargesAction() {
		return new ViewModel ();
	}
	// source_datatable_charges
	public function sourcedatatablechargesAction() {
		$qb = $this->getEntityManager ()->createQueryBuilder ();
		$request = $this->getRequest ();
		$select = $qb->select ( 'c' )->from ( 'Admin\Entity\Charge', 'c' );
		
		if ($request->isPost ()) {
			$filtre = $request->getPost ();
			if (isset ( $filtre ['TYPE'] )) {
				$select->where ( 'c.type = :type' )->setParameter ( 'type', $filtre ['TYPE'] );
			}
		}
		
		$select->orderBy ( 'c.id', 'DESC' );
		
		$charges = $qb->getQuery ()->getResult ();
		$tabjson = array ();
		if (count ( $charges ) > 0) {
			foreach ( $charges as $c ) {
				// $c=new Charge();
				
				$tabvalues = array ();
				$tabvalues [] = '<input type="checkbox" class="checkboxes" value="' . $c->getId () . '" />';
				// N°
				$tabvalues [] = $c->getId ();
				// <th>Date</th>
				$date = '';
				if ($c->getDatecharge ()) {
					$date = $c->getDatecharge ()->format ( 'd/m/Y' );
				}
				$tabvalues [] = '<span class="label label-sm label-warning">' . $date . '</span>';
				// <th>Libelle</th>
				$tabvalues [] = $c->getLabel ();
				// <th>Montant</th>
				$tabvalues [] = '<span class="label label-sm label-success">' . $c->getMontant () . '</span>';
				
				// <th>Type</th>
				$tabvalues [] = $c->getType ();
				
				// Action
				$btn_edit = '<button data-toggle="tooltip" title="Editer la charge" onclick="ADD_CHARGE(' . $c->getId () . ')" class="btn btn-xs blue"><i class="fa fa-edit"></i></button>';
				$btn_delete = '<button data-toggle="tooltip" title="Supprimer la charge" onclick="DELETE(' . $c->getId () . ')" class="btn btn-xs red"><i class="fa fa-trash"></i></button>';
				$tabvalues [] = $btn_edit . $btn_delete;
				
				$tabjson [] = $tabvalues;
			}
		}
		$resultsajax = array (
				"aaData" => $tabjson 
		);
		echo json_encode ( $resultsajax );
		exit ();
	}
	public function getselectTypechargeAction() {
		$json = array ();
		$qb = $this->getEntityManager ()->createQueryBuilder ();
		$qb->select ( 't' )->from ( 'Admin\Entity\TypeCharge', 't' );
		$result = $qb->getQuery ()->getResult ();
		
		// $json ["TOUT"] = 'Tout';
		if (count ( $result ) > 0) {
			foreach ( $result as $t ) {
				$json ['"' . $t->getCode () . '"'] = $t->getLabel ();
			}
		}
		echo json_encode ( $json );
		exit ();
	}
	public function formchargeAction() {
		$idCharge = ( int ) $this->getEvent ()->getRouteMatch ()->getParam ( 'id' );
		$charge = null;
		if ($idCharge > 0) {
			$charge = $this->getEntityManager ()->find ( 'Admin\Entity\Charge', $idCharge );
		}
		$viewModel = new ViewModel ();
		$viewModel->setVariables ( array (
				'charge' => $charge 
		) )->setTerminal ( true );
		return $viewModel;
	}
	public function chargeprocessingAction() {
		$request = $this->getRequest ();
		$success = true;
		$tools_id_contact = 0;
		$title = '<i style="font-size:22px" class="fa fa-times"></i> Erreur';
		$message = '<p>Erreur d\'enregistrement.<p>';
		if ($request->isPost ()) {
			$data_posted = $request->getPost ();
			
			$data = array (
					"CHARGE_ID" => $data_posted ["CHARGE_ID"],
					"TYPE_CHARGE" => (isset ( $data_posted ["SELECT_TYPE_CHARGE_1"] )) ? $data_posted ["SELECT_TYPE_CHARGE_1"] : null,
					"CHARGE_DATE" => (isset ( $data_posted ["CHARGE_DATE"] )) ? new \DateTime ( $data_posted ["CHARGE_DATE"] ) : null,
					"CHARGE_LABEL" => (isset ( $data_posted ["CHARGE_LABEL"] )) ? $data_posted ["CHARGE_LABEL"] : null,
					"CHARGE_MONTANT" => (isset ( $data_posted ["CHARGE_MONTANT"] )) ? $data_posted ["CHARGE_MONTANT"] : null 
			);
			
			$admin_tools = $this->AdminTools ();
			$tools_id = $admin_tools->manageCharge ( $data );
			
			if ($tools_id > 0) {
				
				// Ajouter historique
				$authenticationService = new AuthenticationService ();
				$authenticationService = $this->getServiceLocator ()->get ( 'Zend\Authentication\AuthenticationService' );
				$loggedUser = $authenticationService->getIdentity ();
				
				if ($data_posted ["CHARGE_ID"] == 0) {
					$desc = $loggedUser->getFirstnameuser () . ' À ajouter une charge de ' . $data_posted ["CHARGE_MONTANT"] . ' dhs de type ' . $data_posted ["SELECT_TYPE_CHARGE_1"] . ' le ' . $data_posted ["CHARGE_DATE"];
					$data_history = array (
							'HISTORY_ID' => 0,
							'HISTORY_DATE' => new \DateTime (),
							'HISTORY_DESC' => strtoupper ( $desc ) 
					);
					$tools_id_history = $admin_tools->manageHistory ( $data_history );
				}
				$title = '<i style="font-size:22px" class="fa fa-check"></i> Succès';
				$message = '<p>Mise a jour avec succès.<p>';
			}
		}
		$responseJson = array (
				'success' => $success,
				'title' => $title,
				'message' => $message,
				'id' => $tools_id 
		);
		return new JsonModel ( $responseJson );
		exit ();
	}
	public function deletechargeAction() {
		$success = true;
		$error = 0;
		$request = $this->getRequest ();
		if ($request->isPost ()) {
			$data_posted = $request->getPost ();
			if (count ( $data_posted ['selection'] ) > 0) {
				foreach ( $data_posted ['selection'] as $id ) {
					$charge = $this->getEntityManager ()->find ( 'Admin\Entity\Charge', $id );
					
					// Ajouter historique
					$authenticationService = new AuthenticationService ();
					$authenticationService = $this->getServiceLocator ()->get ( 'Zend\Authentication\AuthenticationService' );
					$loggedUser = $authenticationService->getIdentity ();
					
					
						$desc = $loggedUser->getFirstnameuser () . ' À supprimer la charge n° ' . $id . ' de type '.$charge->getType().' avec le contenu : "'.$charge->getLabel().'" et un montant de '.$charge->getMontant().' dhs';
						$data_history = array (
								'HISTORY_ID' => 0,
								'HISTORY_DATE' => new \DateTime (),
								'HISTORY_DESC' => strtoupper ( $desc )
						);
						$admin_tools = $this->AdminTools ();
						$tools_id_history = $admin_tools->manageHistory ( $data_history );
					
				
					try {
						$this->getEntityManager ()->remove ( $charge );
						$this->getEntityManager ()->flush ();
					} catch ( Exception $e ) {
						$error ++;
					}
				}
			}
		}
		// Succès global
		if ($error > 0) {
			$success = false;
		}
		$response = array (
				'success' => $success 
		);
		return new JsonModel ( $response );
		exit ();
	}
	public function historyAction() {
		return new ViewModel ();
	}
	public function sourcedatatablehistoryAction() {
		$qb = $this->getEntityManager ()->createQueryBuilder ();
		$request = $this->getRequest ();
		
		$select = $qb->select ( 'h' )->from ( 'Admin\Entity\History', 'h' );
	
	
		$select->orderBy ( 'h.id', 'DESC' );
	
		$histories = $qb->getQuery ()->getResult ();
		$tabjson = array ();
		if (count ( $histories ) > 0) {
			foreach ( $histories as $h ) {
				//$h=new History();
	
				$tabvalues = array ();
				$tabvalues [] = '<input type="checkbox" class="checkboxes" value="' . $h->getId() . '" />';
				// N°
				$tabvalues [] = $h->getId ();
				// <th>Date</th>
				$date = '';
				if ($h->getDatehistory()) {
					$date = $h->getDatehistory ()->format ( 'd/m/Y H:i:s' );
				}
				$tabvalues [] = $date;
				// <th>Libelle</th>
				$tabvalues [] = $h->getDescription();
				
	
				$tabjson [] = $tabvalues;
			}
		}
		$resultsajax = array (
				"aaData" => $tabjson
		);
		echo json_encode ( $resultsajax );
		exit ();
	}
	public function checkIfExistCotisationAction() {
		$numAppt = ( int ) $this->getEvent ()->getRouteMatch ()->getParam ( 'n' );
		$success = true;
		$request = $this->getRequest ();
		if ($request->isPost ()) {
			$data_posted = $request->getPost ();
			
			$requestedDateCotisation = ($data_posted ['COTISATION_VERIFICATION'])?$data_posted ['COTISATION_VERIFICATION']:null;
			
			if (isset($requestedDateCotisation)){
				
				$dt=new \DateTime($requestedDateCotisation);
				if ($dt){
					
					$m=$dt->format('m');
					$y=$dt->format('Y');
					
					//echo $m.' - '.$y;
					
					$qb = $this->getEntityManager ()->createQueryBuilder ();
					$qb->select ( 'c' )->from ( 'Admin\Entity\Cotisation', 'c' )
					->where ( 'c.numappt = :numappt' )->setParameter ( 'numappt', $numAppt )
					->andWhere ( 'c.mounthcotisation = :mounthcotisation' )->setParameter ( 'mounthcotisation', $m )
					->andWhere ( 'c.yearcotisation = :yearcotisation' )->setParameter ( 'yearcotisation', $y );

					$results = $qb->getQuery ()->getResult ();
					
					//var_dump(count ( $results ));
						
					if (count ( $results ) > 0) {
						$success = false;
					}
				}
			}
			
		}
		echo json_encode ( $success );
		exit ();
	}
}

