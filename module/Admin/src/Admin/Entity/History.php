<?php

namespace Admin\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * History
 *
 * @ORM\Table(name="history")
 * @ORM\Entity
 */
class History
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateHistory", type="datetime", nullable=false)
     */
    private $datehistory;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=false)
     */
    private $description;



    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set datehistory
     *
     * @param \DateTime $datehistory
     *
     * @return History
     */
    public function setDatehistory($datehistory)
    {
        $this->datehistory = $datehistory;

        return $this;
    }

    /**
     * Get datehistory
     *
     * @return \DateTime
     */
    public function getDatehistory()
    {
        return $this->datehistory;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return History
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
}
