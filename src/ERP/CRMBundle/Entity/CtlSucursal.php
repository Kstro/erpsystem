<?php

namespace ERP\CRMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CtlSucursal
 *
 * @ORM\Table(name="ctl_sucursal", indexes={@ORM\Index(name="fk_ctl_sucursal_ctl_empresa1_idx", columns={"empresa"}), @ORM\Index(name="fk_ctl_sucursal_ctl_ciudad1_idx", columns={"ciudad"})})
 * @ORM\Entity
 */
class CtlSucursal
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
     * @ORM\Column(name="nombre", type="string", length=150, nullable=false)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="direccion", type="text", length=65535, nullable=false)
     */
    private $direccion;

    /**
     * @var integer
     *
     * @ORM\Column(name="estado", type="integer", nullable=false)
     */
    private $estado;

    /**
     * @var \CtlEmpresa
     *
     * @ORM\ManyToOne(targetEntity="CtlEmpresa")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="empresa", referencedColumnName="id")
     * })
     */
    private $empresa;

    /**
     * @var \CtlCiudad
     *
     * @ORM\ManyToOne(targetEntity="CtlCiudad")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ciudad", referencedColumnName="id")
     * })
     */
    private $ciudad;



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
     * @return CtlSucursal
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
     * Set direccion
     *
     * @param string $direccion
     * @return CtlSucursal
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get direccion
     *
     * @return string 
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set estado
     *
     * @param integer $estado
     * @return CtlSucursal
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return integer 
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set empresa
     *
     * @param \ERP\CRMBundle\Entity\CtlEmpresa $empresa
     * @return CtlSucursal
     */
    public function setEmpresa(\ERP\CRMBundle\Entity\CtlEmpresa $empresa = null)
    {
        $this->empresa = $empresa;

        return $this;
    }

    /**
     * Get empresa
     *
     * @return \ERP\CRMBundle\Entity\CtlEmpresa 
     */
    public function getEmpresa()
    {
        return $this->empresa;
    }

    /**
     * Set ciudad
     *
     * @param \ERP\CRMBundle\Entity\CtlCiudad $ciudad
     * @return CtlSucursal
     */
    public function setCiudad(\ERP\CRMBundle\Entity\CtlCiudad $ciudad = null)
    {
        $this->ciudad = $ciudad;

        return $this;
    }

    /**
     * Get ciudad
     *
     * @return \ERP\CRMBundle\Entity\CtlCiudad 
     */
    public function getCiudad()
    {
        return $this->ciudad;
    }
}
