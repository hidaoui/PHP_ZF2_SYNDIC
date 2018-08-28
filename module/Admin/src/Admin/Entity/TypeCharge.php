<?php

namespace Admin\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TypeCharge
 *
 * @ORM\Table(name="type_charge")
 * @ORM\Entity
 */
class TypeCharge
{
    /**
     * @var integer
     *
     * @ORM\Column(name="idType", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idtype;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=45, nullable=false)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="label", type="string", length=255, nullable=false)
     */
    private $label;



    /**
     * Get idtype
     *
     * @return integer
     */
    public function getIdtype()
    {
        return $this->idtype;
    }

    /**
     * Set code
     *
     * @param string $code
     *
     * @return TypeCharge
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set label
     *
     * @param string $label
     *
     * @return TypeCharge
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }
}
