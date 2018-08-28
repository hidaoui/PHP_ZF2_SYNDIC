<?php

namespace Admin\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cotisation
 *
 * @ORM\Table(name="cotisation")
 * @ORM\Entity
 */
class Cotisation
{
    /**
     * @var integer
     *
     * @ORM\Column(name="idCotisation", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idcotisation;

    /**
     * @var integer
     *
     * @ORM\Column(name="idMember", type="integer", nullable=false)
     */
    private $idmember;

    /**
     * @var integer
     *
     * @ORM\Column(name="numAppt", type="integer", nullable=false)
     */
    private $numappt;

    /**
     * @var string
     *
     * @ORM\Column(name="montantCotisation", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $montantcotisation;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCotisation", type="datetime", nullable=false)
     */
    private $datecotisation;

    /**
     * @var integer
     *
     * @ORM\Column(name="mounthCotisation", type="integer", nullable=false)
     */
    private $mounthcotisation;

    /**
     * @var integer
     *
     * @ORM\Column(name="YearCotisation", type="integer", nullable=false)
     */
    private $yearcotisation;



    /**
     * Get idcotisation
     *
     * @return integer
     */
    public function getIdcotisation()
    {
        return $this->idcotisation;
    }

    /**
     * Set idmember
     *
     * @param integer $idmember
     *
     * @return Cotisation
     */
    public function setIdmember($idmember)
    {
        $this->idmember = $idmember;

        return $this;
    }

    /**
     * Get idmember
     *
     * @return integer
     */
    public function getIdmember()
    {
        return $this->idmember;
    }

    /**
     * Set numappt
     *
     * @param integer $numappt
     *
     * @return Cotisation
     */
    public function setNumappt($numappt)
    {
        $this->numappt = $numappt;

        return $this;
    }

    /**
     * Get numappt
     *
     * @return integer
     */
    public function getNumappt()
    {
        return $this->numappt;
    }

    /**
     * Set montantcotisation
     *
     * @param string $montantcotisation
     *
     * @return Cotisation
     */
    public function setMontantcotisation($montantcotisation)
    {
        $this->montantcotisation = $montantcotisation;

        return $this;
    }

    /**
     * Get montantcotisation
     *
     * @return string
     */
    public function getMontantcotisation()
    {
        return $this->montantcotisation;
    }

    /**
     * Set datecotisation
     *
     * @param \DateTime $datecotisation
     *
     * @return Cotisation
     */
    public function setDatecotisation($datecotisation)
    {
        $this->datecotisation = $datecotisation;

        return $this;
    }

    /**
     * Get datecotisation
     *
     * @return \DateTime
     */
    public function getDatecotisation()
    {
        return $this->datecotisation;
    }

    /**
     * Set mounthcotisation
     *
     * @param integer $mounthcotisation
     *
     * @return Cotisation
     */
    public function setMounthcotisation($mounthcotisation)
    {
        $this->mounthcotisation = $mounthcotisation;

        return $this;
    }

    /**
     * Get mounthcotisation
     *
     * @return integer
     */
    public function getMounthcotisation()
    {
        return $this->mounthcotisation;
    }

    /**
     * Set yearcotisation
     *
     * @param integer $yearcotisation
     *
     * @return Cotisation
     */
    public function setYearcotisation($yearcotisation)
    {
        $this->yearcotisation = $yearcotisation;

        return $this;
    }

    /**
     * Get yearcotisation
     *
     * @return integer
     */
    public function getYearcotisation()
    {
        return $this->yearcotisation;
    }
}
