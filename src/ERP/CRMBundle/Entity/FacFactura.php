<?php

namespace ERP\CRMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FacFactura
 *
 * @ORM\Table(name="fac_factura", indexes={@ORM\Index(name="fk_fac_factura_crm_oportunidad1_idx", columns={"oportunidad"}), @ORM\Index(name="fk_fac_factura_ctl_usuario1_idx", columns={"usuario"}), @ORM\Index(name="fk_fac_factura_ctl_estado_factura1_idx", columns={"estado_factura"}), @ORM\Index(name="fk_fac_factura_ctl_sucursal1_idx", columns={"sucursal"}), @ORM\Index(name="fk_fac_factura_ctl_direccion1_idx", columns={"direccion"})})
 * @ORM\Entity
 */
class FacFactura
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
     * @ORM\Column(name="fecha_factura", type="date", nullable=false)
     */
    private $fechaFactura;

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
     * @var \CtlEstadoFactura
     *
     * @ORM\ManyToOne(targetEntity="CtlEstadoFactura")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="estado_factura", referencedColumnName="id")
     * })
     */
    private $estadoFactura;

    /**
     * @var \CtlSucursal
     *
     * @ORM\ManyToOne(targetEntity="CtlSucursal")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="sucursal", referencedColumnName="id")
     * })
     */
    private $sucursal;

    /**
     * @var \CtlDireccion
     *
     * @ORM\ManyToOne(targetEntity="CtlDireccion")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="direccion", referencedColumnName="id")
     * })
     */
    private $direccion;



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
     * Set fechaFactura
     *
     * @param \DateTime $fechaFactura
     * @return FacFactura
     */
    public function setFechaFactura($fechaFactura)
    {
        $this->fechaFactura = $fechaFactura;

        return $this;
    }

    /**
     * Get fechaFactura
     *
     * @return \DateTime 
     */
    public function getFechaFactura()
    {
        return $this->fechaFactura;
    }

    /**
     * Set oportunidad
     *
     * @param \ERP\CRMBundle\Entity\CrmOportunidad $oportunidad
     * @return FacFactura
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
     * @return FacFactura
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
     * Set estadoFactura
     *
     * @param \ERP\CRMBundle\Entity\CtlEstadoFactura $estadoFactura
     * @return FacFactura
     */
    public function setEstadoFactura(\ERP\CRMBundle\Entity\CtlEstadoFactura $estadoFactura = null)
    {
        $this->estadoFactura = $estadoFactura;

        return $this;
    }

    /**
     * Get estadoFactura
     *
     * @return \ERP\CRMBundle\Entity\CtlEstadoFactura 
     */
    public function getEstadoFactura()
    {
        return $this->estadoFactura;
    }

    /**
     * Set sucursal
     *
     * @param \ERP\CRMBundle\Entity\CtlSucursal $sucursal
     * @return FacFactura
     */
    public function setSucursal(\ERP\CRMBundle\Entity\CtlSucursal $sucursal = null)
    {
        $this->sucursal = $sucursal;

        return $this;
    }

    /**
     * Get sucursal
     *
     * @return \ERP\CRMBundle\Entity\CtlSucursal 
     */
    public function getSucursal()
    {
        return $this->sucursal;
    }

    /**
     * Set direccion
     *
     * @param \ERP\CRMBundle\Entity\CtlDireccion $direccion
     * @return FacFactura
     */
    public function setDireccion(\ERP\CRMBundle\Entity\CtlDireccion $direccion = null)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get direccion
     *
     * @return \ERP\CRMBundle\Entity\CtlDireccion 
     */
    public function getDireccion()
    {
        return $this->direccion;
    }
}
