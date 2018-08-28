<?php

namespace Admin\Tools;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Admin\Entity\HdSupplier;
use Admin\Entity\Contact;
use Admin\Entity\HdFilesSupplier;
use Admin\Entity\HdProductSupplier;
use Admin\Entity\RfqFamily;
use Admin\Entity\User;
use Admin\Entity\Cotisation;
use Admin\Entity\Member;
use Admin\Entity\Charge;
use Admin\Entity\History;

class AdminTools extends AbstractPlugin {
	protected $em = null;
	public function getEntityManager() {
		if (null === $this->em) {
			$this->em = $this->getController ()->getServiceLocator ()->get ( 'doctrine.entitymanager.orm_default' );
		}
		return $this->em;
	}
	public function manageCotisation(array $data) {
		$c = new Cotisation();
		if ($data ['COTISATION_ID'] > 0)
			$c = $this->getEntityManager ()->find ( 'Admin\Entity\Cotisation', $data ['COTISATION_ID'] );

		try {
			(isset ( $data ['COTISATION_MEMBER_ID'] )) ? $c->setIdmember( $data ['COTISATION_MEMBER_ID'] ) : '';
			(isset ( $data ['COTISATION_MEMBER_APPT'] )) ? $c->setNumappt( $data ['COTISATION_MEMBER_APPT'] ) : '';
			(isset ( $data ['COTISATION_DATE'] )) ? $c->setDatecotisation( $data ['COTISATION_DATE'] ) : '';
			(isset ( $data ['COTISATION_ANNEE'] )) ? $c->setYearcotisation( $data ['COTISATION_ANNEE'] ) : '';
			(isset ( $data ['COTISATION_MOIS'] )) ? $c->setMounthcotisation( $data ['COTISATION_MOIS'] ) : '';
			(isset ( $data ['COTISATION_MONTANT'] )) ? $c->setMontantcotisation( $data ['COTISATION_MONTANT'] ) : '';


			
			$this->getEntityManager ()->persist ( $c );
			$this->getEntityManager ()->flush ();
			$tools_id = $c->getIdcotisation();
		} catch ( Exception $e ) {
			$tools_id = 0;
		}
		
		return $tools_id;
	}
	public function manageCharge(array $data) {
		$c = new Charge();
		
		if ($data ['CHARGE_ID'] > 0)
			$c = $this->getEntityManager ()->find ( 'Admin\Entity\Charge', $data ['CHARGE_ID'] );
	
			try {
				(isset ( $data ['TYPE_CHARGE'] )) ? $c->setType( $data ['TYPE_CHARGE'] ) : '';
				(isset ( $data ['CHARGE_DATE'] )) ? $c->setDatecharge( $data ['CHARGE_DATE'] ) : '';
				(isset ( $data ['CHARGE_LABEL'] )) ? $c->setLabel( $data ['CHARGE_LABEL'] ) : '';
				(isset ( $data ['CHARGE_MONTANT'] )) ? $c->setMontant( $data ['CHARGE_MONTANT'] ) : '';

				$this->getEntityManager ()->persist ( $c );
				$this->getEntityManager ()->flush ();
				$tools_id = $c->getId();
			} catch ( Exception $e ) {
				$tools_id = 0;
			}
	
			return $tools_id;
	}
	public function manageHistory(array $data) {
		$h = new History();
	
		if ($data ['HISTORY_ID'] > 0)
			$h = $this->getEntityManager ()->find ( 'Admin\Entity\History', $data ['HISTORY_ID'] );
	
			try {
				(isset ( $data ['HISTORY_DATE'] )) ? $h->setDatehistory( $data ['HISTORY_DATE'] ) : '';
				(isset ( $data ['HISTORY_DESC'] )) ? $h->setDescription( $data ['HISTORY_DESC'] ) : '';
	
				$this->getEntityManager ()->persist ( $h );
				$this->getEntityManager ()->flush ();
				$tools_id = $h->getId();
			} catch ( Exception $e ) {
				$tools_id = 0;
			}
	
			return $tools_id;
	}
	public function getIdMember($numAppt) {
		$qb = $this->getEntityManager ()->createQueryBuilder ();
		$qb->select ( 'm.id' )->from ( 'Admin\Entity\Member', 'm' )->where ('m.numappt = '.$numAppt);
		$result = $qb->getQuery ()->getResult ();
		$id=(isset($result[0]["id"]))?$result[0]["id"]:0;
		return $id;
	}
	public function getTotalCotisation($numAppt=null) {
		$qb = $this->getEntityManager ()->createQueryBuilder ();
		if (isset($numAppt)){
			$qb->select ( 'SUM(c.montantcotisation) as total' )->from ( 'Admin\Entity\Cotisation', 'c' )->where ('c.numappt = :numappt')->setParameter ( 'numappt',$numAppt );
		}else {
			$qb->select ( 'SUM(c.montantcotisation) as total' )->from ( 'Admin\Entity\Cotisation', 'c' );
		}
		$result = $qb->getQuery ()->getResult ();
		
		$montant=(isset($result[0]["total"]))?$result[0]["total"]:0;
		
		return $montant;
	}
	public function getNbMoisPays($numAppt) {
		
		if ($numAppt==null && $numAppt!=0) return 0;
		
		$nb=0;
		$qb = $this->getEntityManager ()->createQueryBuilder ();
		$qb->select ( 'c.idcotisation' )->from ( 'Admin\Entity\Cotisation', 'c' )->where ('c.numappt = :numappt')->setParameter ( 'numappt',$numAppt );
		$results = $qb->getQuery ()->getResult ();
	
		$nb=count($results);
	
		return $nb;
	}
	public function getImpayedMois($numAppt) {
	
		if ($numAppt==null && $numAppt!=0) return array();
		
		
		$start    = (new \DateTime('2017-08-22'))->modify('first day of this month');
		$end      = (new \DateTime())->modify('first day of this month');
		
		$interval = \DateInterval::createFromDateString('1 month');
		$period   = new \DatePeriod($start, $interval, $end);
		
		//var_dump($period);
		
		$tab_period=array();
		foreach ($period as $dt) {
			//echo $dt->format("m-Y") . "<br>\n";
			$tab_period[]=$dt->format("m-Y");
		}
	
		$qb = $this->getEntityManager ()->createQueryBuilder ();
		$qb->select ( 'c' )->from ( 'Admin\Entity\Cotisation', 'c' )->where ('c.numappt = :numappt')->setParameter ( 'numappt',$numAppt );
		$results = $qb->getQuery ()->getResult ();
		//Les mois payées
		$tab_payed=array();
		if (count($results)>0){
			foreach ($results as $c){
				
				$m=($c->getMounthcotisation()<10 && $c->getMounthcotisation()>0)?'0'.$c->getMounthcotisation():$c->getMounthcotisation();
				
				$tab_payed[]=$m.'-'.$c->getYearcotisation();
			}
		}
		
		//var_dump($tab_payed);
		
		$result = array_diff($tab_period, $tab_payed);
	
		return $result;
	}
	public function getMoisPayed($numAppt) {
	
		if ($numAppt==null && $numAppt!=0) return array();
		
		$qb = $this->getEntityManager ()->createQueryBuilder ();
		$qb->select ( 'c' )->from ( 'Admin\Entity\Cotisation', 'c' )->where ('c.numappt = :numappt')->setParameter ( 'numappt',$numAppt )->orderBy ( 'c.idcotisation', 'Desc' );
		$results = $qb->getQuery ()->getResult ();
		//Les mois payées
		$tab_payed=array();
		if (count($results)>0){
			foreach ($results as $c){
	
				$m=($c->getMounthcotisation()<10 && $c->getMounthcotisation()>0)?'0'.$c->getMounthcotisation():$c->getMounthcotisation();
	
				$tab_payed[]=$m.'-'.$c->getYearcotisation();
			}
		}	
		return $tab_payed;
	}
	public function getHeaderCalendar() {
		
		$dNow=new \DateTime();
		$dNow->modify('first day of this month');
		$dNow->modify('-5 month');
		$str=$dNow->format('Y-m-d');
		
		$start    = (new \DateTime($str));
		$end      = (new \DateTime())->modify('first day of this month');
		
		
		$end->modify('+6 month');
	
		$interval = \DateInterval::createFromDateString('1 month');
		$period   = new \DatePeriod($start, $interval, $end);
	
		$tab_period=array();
		foreach ($period as $dt) {
			$tab_period[]=$dt->format("m-Y");
		}
	
		return $tab_period;
	}
	public function getTotalCharges() {
		$qb = $this->getEntityManager ()->createQueryBuilder ();
		$qb->select ( 'SUM(c.montant) as total' )->from ( 'Admin\Entity\Charge', 'c' );
		$result = $qb->getQuery ()->getResult ();
		$montant=(isset($result[0]["total"]))?$result[0]["total"]:0;
		return $montant;
	}
	public function getTotalChargesByType($type) {
		$qb = $this->getEntityManager ()->createQueryBuilder ();
		$qb->select ( 'SUM(c.montant) as total' )->from ( 'Admin\Entity\Charge', 'c' )->where ('c.type = :type')->setParameter ( 'type',$type );
		$result = $qb->getQuery ()->getResult ();
		$montant=(isset($result[0]["total"]))?$result[0]["total"]:0;
		return $montant;
	}
	public function checkIfPayedMonth ($numAppt,$mois,$year){
		$success=false;
		$qb = $this->getEntityManager ()->createQueryBuilder ();
		$qb->select ( 'c' )->from ( 'Admin\Entity\Cotisation', 'c' )
		->where ('c.numappt = :numappt')->setParameter ( 'numappt',$numAppt )
		->andWhere ('c.mounthcotisation = :mounthcotisation')->setParameter ( 'mounthcotisation',$mois )
		->andWhere ('c.yearcotisation = :yearcotisation')->setParameter ( 'yearcotisation',$year );
		$results = $qb->getQuery ()->getResult ();
		if (count($results)>0){
			$success=true;
		}	
		return $success;
	}
	/**
	 * return array from xls
	 * @param string $path
	 */
	public function getXlsData($path, $headerRow=1){
		\PHPExcel_Settings::setZipClass ( \PHPExcel_Settings::PCLZIP );
		$doc = new \PHPExcel ();
		$objPHPExcel = \PHPExcel_IOFactory::load ( $path );
	
		$flag = true;
		foreach ( $objPHPExcel->getWorksheetIterator () as $worksheet ) {
			$worksheetTitle = $worksheet->getTitle ();
			$globalArray=null;
			$titres=null;
			$headCol = 0;
			$firstDateRow = 0;
			$dateDebut = array ();
			$dateFin = array ();
			if ($flag) {
				$highestRow = 0;
				$highestColumn = 'A';
				$flag = false;
			}
			$curRow = $worksheet->getHighestRow ();
			$curColumn = $worksheet->getHighestColumn ( '' );
			$curColumnIndex = \PHPExcel_Cell::columnIndexFromString ( $curColumn );
			$highestRow = ($highestRow < $curRow) ? $curRow : $highestRow;
			$highestColumn = ($highestColumn <= $curColumn) ? $curColumn : $highestColumn;
			$highestColumnIndex = \PHPExcel_Cell::columnIndexFromString ( $highestColumn );
			for($row = 0; $row <= $curRow; $row ++) {
				$rowArray = null;
				for($col = 0; $col < $curColumnIndex; $col ++) {
	
					$cell = $worksheet->getCellByColumnAndRow ( $col, $row );
					$val = $cell->getCalculatedValue ();
	
					if ($row == $headerRow) {
						$titres [$col] = ( string ) $val;
						if ($titres [$col] === "") {
							$curColumnIndex = $col;
						}
					} else if($titres != null){
						$rowArray [$titres [$col]] = ( string ) $val;
					}
				}
	
				if($rowArray != null)
					$globalArray[] = $rowArray;
	
	
			}
			unlink($path);
			return $globalArray;
		}
	}
	
}