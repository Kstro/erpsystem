<?php

namespace ERP\CRMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CrmCotizacion
 *
 * @ORM\Table(name="crm_cotizacion", indexes={@ORM\Index(name="fk_crm_cotizacion_crm_oportunidad1_idx", columns={"oportunidad"}), @ORM\Index(name="fk_crm_cotizacion_ctl_usuario1_idx", columns={"usuario"}), @ORM\Index(name="fk_crm_cotizacion_crm_estado_cotizacion1_idx", columns={"estado_cotizacion"})})
 * @ORM\Entity
 */
class CrmCotizacion
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
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_registro", type="datetime", nullable=false)
     */
    private $fechaRegistro;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_vencimiento", type="date", nullable=false)
     */
    private $fechaVencimiento;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_cierre", type="datetime", nullable=true)
     */
    private $fechaCierre;

    /**
     * @var string
     *
     * @ORM\Column(name="condiciones_generales", type="text", length=65535, nullable=true)
     */
    private $condicionesGenerales;

    /**
     * @var \CrmOportunidad
     *
     * @ORM\ManyToOne(targetEntity="CrmOportunidad")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="oportunidad", referencedColumnName="id")
     * })
     */
    private $oportunidad;

    /**
     * @var \CtlUsuario
     *
     * @ORM\ManyToOne(targetEntity="CtlUsuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="usuario", referencedColumnName="id")
     * })
     */
    private $usuario;

    /**
     * @var \CrmEstadoCotizacion
     *
     * @ORM\ManyToOne(targetEntity="CrmEstadoCotizacion")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="estado_cotizacion", referencedColumnName="id")
     * })
     */
    private $estadoCotizacion;



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
     * Set fechaRegistro
     *
     * @param \DateTime $fechaRegistro
     * @return CrmCotizacion
     */
    public function setFechaRegistro($fechaRegistro)
    {
        $this->fechaRegistro = $fechaRegistro;

        return $this;
    }

    /**
     * Get fechaRegistro
     *
     * @return \DateTime 
     */
    public function getFechaRegistro()
    {
        return $this->fechaRegistro;
    }

    /**
     * Set fechaVencimiento
     *
     * @param \DateTime $fechaVencimiento
     * @return CrmCotizacion
     */
    public function setFechaVencimiento($fechaVencimiento)
    {
        $this->fechaVencimiento = $fechaVencimiento;

        return $this;
    }

    /**
     * Get fechaVencimiento
     *
     * @return \DateTime 
     */
    public function getFechaVencimiento()
    {
        return $this->fechaVencimiento;
    }

    /**
     * Set fechaCierre
     *
     * @param \DateTime $fechaCierre
     * @return CrmCotizacion
     */
    public function setFechaCierre($fechaCierre)
    {
        $this->fechaCierre = $fechaCierre;

        return $this;
    }

    /**
     * Get fechaCierre
     *
     * @return \DateTime 
     */
    public function getFechaCierre()
    {
        return $this->fechaCierre;
    }

    /**
     * Set condicionesGenerales
     *
     * @param string $condicionesGenerales
     * @return CrmCotizacion
     */
    public function setCondicionesGenerales($condicionesGenerales)
    {
        $this->condicionesGenerales = $condicionesGenerales;

        return $this;
    }

    /**
     * Get condicionesGenerales
     *
     * @return string 
     */
    public function getCondicionesGenerales()
    {
        return $this->condicionesGenerales;
    }

    /**
     * Set oportunidad
     *
     * @param \ERP\CRMBundle\Entity\CrmOportunidad $oportunidad
     * @return CrmCotizacion
     */
    public function setOportunidad(\ERP\CRMBundle\Entity\CrmOportunidad $oportunidad = null)
    {
        $this->oportunidad = $oportunidad;

        return $this;
    }

    /**
     * Get oportunidad
     *
     * @return \ERP\CRMBundle\Entity\CrmOportunidad 
     */
    public function getOportunidad()
    {
        return $this->oportunidad;
    }

    /**
     * Set usuario
     *
     * @param \ERP\CRMBundle\Entity\CtlUsuario $usuario
     * @return CrmCotizacion
     */
    public function setUsuario(\ERP\CRMBundle\Entity\CtlUsuario $usuario = null)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return \ERP\CRMBundle\Entity\CtlUsuario 
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set estadoCotizacion
     *
     * @param \ERP\CRMBundle\Entity\CrmEstadoCotizacion $estadoCotizacion
     * @return CrmCotizacion
     */
    public function setEstadoCotizacion(\ERP\CRMBundle\Entity\CrmEstadoCotizacion $estadoCotizacion = null)
    {
        $this->estadoCotizacion = $estadoCotizacion;

        return $this;
    }

    /**
     * Get estadoCotizacion
     *
     * @return \ERP\CRMBundle\Entity\CrmEstadoCotizacion 
     */
    public function getEstadoCotizacion()
    {
        return $this->estadoCotizacion;
    }
}
