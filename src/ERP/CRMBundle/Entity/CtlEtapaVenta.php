<?php

namespace ERP\CRMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CtlEtapaVenta
 *
 * @ORM\Table(name="ctl_etapa_venta")
 * @ORM\Entity
 */
class CtlEtapaVenta
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
     * @ORM\Column(name="nombre", type="string", length=100, nullable=false)
     */
    private $nombre;

    /**
     * @var float
     *
     * @ORM\Column(name="probabilidad", type="float", precision=10, scale=0, nullable=false)
     */
    private $probabilidad;

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
     * @return CtlEtapaVenta
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
     * Set probabilidad
     *
     * @param float $probabilidad
     * @return CtlEtapaVenta
     */
    public function setProbabilidad($probabilidad)
    {
        $this->probabilidad = $probabilidad;

        return $this;
    }

    /**
     * Get probabilidad
     *
     * @return float 
     */
    public function getProbabilidad()
    {
        return $this->probabilidad;
    }

    /**
     * Set estado
     *
     * @param boolean $estado
     * @return CtlEtapaVenta
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
