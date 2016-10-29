<?php

namespace ERP\CRMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CrmOportunidad
 *
 * @ORM\Table(name="crm_oportunidad", indexes={@ORM\Index(name="fk_crm_oportunidad_ctl_etapa_venta1_idx", columns={"etapa_venta"}), @ORM\Index(name="fk_crm_oportunidad_ctl_fuente1_idx", columns={"fuente_principal"}), @ORM\Index(name="fk_crm_oportunidad_campania1_idx", columns={"campania"}), @ORM\Index(name="fk_crm_oportunidad_crm_estado_oportunidad1_idx", columns={"estado_oportunidad"})})
 * @ORM\Entity
 */
class CrmOportunidad
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
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_registro", type="datetime", nullable=false)
     */
    private $fechaRegistro;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_cierre", type="datetime", nullable=true)
     */
    private $fechaCierre;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="text", length=65535, nullable=true)
     */
    private $descripcion;
    
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
     * @var \CtlEtapaVenta
     *
     * @ORM\ManyToOne(targetEntity="CtlEtapaVenta")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="etapa_venta", referencedColumnName="id")
     * })
     */
    private $etapaVenta;

    /**
     * @var \CtlFuente
     *
     * @ORM\ManyToOne(targetEntity="CtlFuente")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="fuente_principal", referencedColumnName="id")
     * })
     */
    private $fuentePrincipal;

    /**
     * @var \CrmCampania
     *
     * @ORM\ManyToOne(targetEntity="CrmCampania")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="campania", referencedColumnName="id")
     * })
     */
    private $campania;

    /**
     * @var \CrmEstadoOportunidad
     *
     * @ORM\ManyToOne(targetEntity="CrmEstadoOportunidad")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="estado_oportunidad", referencedColumnName="id")
     * })
     */
    private $estadoOportunidad;
    
    /**
     * @var \CrmCuenta
     *
     * @ORM\ManyToOne(targetEntity="CrmCuenta")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="cuenta", referencedColumnName="id")
     * })
     */
    private $cuenta;



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
     * @return CrmOportunidad
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
     * Set fechaRegistro
     *
     * @param \DateTime $fechaRegistro
     * @return CrmOportunidad
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
     * Set fechaCierre
     *
     * @param \DateTime $fechaCierre
     * @return CrmOportunidad
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
     * Set descripcion
     *
     * @param string $descripcion
     * @return CrmOportunidad
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string 
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }
    
    /**
     * Set probabilidad
     *
     * @param float $probabilidad
     * @return CrmOportunidad
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
     * @return CrmOportunidad
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

    /**
     * Set etapaVenta
     *
     * @param \ERP\CRMBundle\Entity\CtlEtapaVenta $etapaVenta
     * @return CrmOportunidad
     */
    public function setEtapaVenta(\ERP\CRMBundle\Entity\CtlEtapaVenta $etapaVenta = null)
    {
        $this->etapaVenta = $etapaVenta;

        return $this;
    }

    /**
     * Get etapaVenta
     *
     * @return \ERP\CRMBundle\Entity\CtlEtapaVenta 
     */
    public function getEtapaVenta()
    {
        return $this->etapaVenta;
    }

    /**
     * Set fuentePrincipal
     *
     * @param \ERP\CRMBundle\Entity\CtlFuente $fuentePrincipal
     * @return CrmOportunidad
     */
    public function setFuentePrincipal(\ERP\CRMBundle\Entity\CtlFuente $fuentePrincipal = null)
    {
        $this->fuentePrincipal = $fuentePrincipal;

        return $this;
    }

    /**
     * Get fuentePrincipal
     *
     * @return \ERP\CRMBundle\Entity\CtlFuente 
     */
    public function getFuentePrincipal()
    {
        return $this->fuentePrincipal;
    }

    /**
     * Set campania
     *
     * @param \ERP\CRMBundle\Entity\CrmCampania $campania
     * @return CrmOportunidad
     */
    public function setCampania(\ERP\CRMBundle\Entity\CrmCampania $campania = null)
    {
        $this->campania = $campania;

        return $this;
    }

    /**
     * Get campania
     *
     * @return \ERP\CRMBundle\Entity\CrmCampania 
     */
    public function getCampania()
    {
        return $this->campania;
    }

    /**
     * Set estadoOportunidad
     *
     * @param \ERP\CRMBundle\Entity\CrmEstadoOportunidad $estadoOportunidad
     * @return CrmOportunidad
     */
    public function setEstadoOportunidad(\ERP\CRMBundle\Entity\CrmEstadoOportunidad $estadoOportunidad = null)
    {
        $this->estadoOportunidad = $estadoOportunidad;

        return $this;
    }

    /**
     * Get estadoOportunidad
     *
     * @return \ERP\CRMBundle\Entity\CrmEstadoOportunidad 
     */
    public function getEstadoOportunidad()
    {
        return $this->estadoOportunidad;
    }
    
    /**
     * Set cuenta
     *
     * @param \ERP\CRMBundle\Entity\CrmCuenta $cuenta
     * @return CrmOportunidad
     */
    public function setCuenta(\ERP\CRMBundle\Entity\CrmCuenta $cuenta = null)
    {
        $this->cuenta = $cuenta;

        return $this;
    }

    /**
     * Get cuenta
     *
     * @return \ERP\CRMBundle\Entity\CrmCuenta 
     */
    public function getCuenta()
    {
        return $this->cuenta;
    }
    
    public function __toString() {
        return $this->nombre ? $this->nombre : '';
    }
}
