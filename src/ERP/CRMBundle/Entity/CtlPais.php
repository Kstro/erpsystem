<?php

namespace ERP\CRMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CtlPais
 *
 * @ORM\Table(name="ctl_pais")
 * @ORM\Entity
 */
class CtlPais
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=200, nullable=false)
     */
    private $nombre;

    /**
     * @var integer
     *
     * @ORM\Column(name="codigo_area", type="integer", nullable=false)
     */
    private $codigoArea;

    /**
     * @var boolean
     *
     * @ORM\Column(name="estado", type="boolean", nullable=false)
     */
    private $estado;



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
     * Set nombre
     *
     * @param string $nombre
     * @return CtlPais
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set codigoArea
     *
     * @param integer $codigoArea
     * @return CtlPais
     */
    public function setCodigoArea($codigoArea)
    {
        $this->codigoArea = $codigoArea;

        return $this;
    }

    /**
     * Get codigoArea
     *
     * @return integer 
     */
    public function getCodigoArea()
    {
        return $this->codigoArea;
    }

    /**
     * Set estado
     *
     * @param boolean $estado
     * @return CtlPais
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return boolean 
     */
    public function getEstado()
    {
        return $this->estado;
    }
}
