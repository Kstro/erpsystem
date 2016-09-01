<?php

namespace ERP\CRMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HistorialAcciones
 *
 * @ORM\Table(name="historial_acciones", indexes={@ORM\Index(name="fk_accion_tabla_ctl_tipo_accion1_idx", columns={"tipo_accion"}), @ORM\Index(name="fk_accion_tabla_ctl_usuario1_idx", columns={"usuario"}), @ORM\Index(name="fk_accion_tabla_ctl_producto1_idx", columns={"producto"}), @ORM\Index(name="fk_accion_tabla_crm_actividad1_idx", columns={"actividad"}), @ORM\Index(name="fk_accion_tabla_fac_factura1_idx", columns={"factura"}), @ORM\Index(name="fk_accion_tabla_crm_oportunidad1_idx", columns={"oportunidad"}), @ORM\Index(name="fk_accion_tabla_crm_cotizacion1_idx", columns={"cotizacion"}), @ORM\Index(name="fk_accion_tabla_crm_cuenta1_idx", columns={"cuenta"}), @ORM\Index(name="fk_accion_tabla_crm_campania1_idx", columns={"campania"}), @ORM\Index(name="fk_historial_crm_contacto1_idx", columns={"contacto"}), @ORM\Index(name="fk_historial_crm_proyecto1_idx", columns={"proyecto"})})
 * @ORM\Entity
 */
class HistorialAcciones
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
     * @var string
     *
     * @ORM\Column(name="accion", type="text", length=65535, nullable=false)
     */
    private $accion;

    /**
     * @var string
     *
     * @ORM\Column(name="src_archivo", type="string", length=255, nullable=true)
     */
    private $srcArchivo;

    /**
     * @var \CtlTipoAccion
     *
     * @ORM\ManyToOne(targetEntity="CtlTipoAccion")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="tipo_accion", referencedColumnName="id")
     * })
     */
    private $tipoAccion;

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
     * @var \CtlProducto
     *
     * @ORM\ManyToOne(targetEntity="CtlProducto")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="producto", referencedColumnName="id")
     * })
     */
    private $producto;

    /**
     * @var \CrmActividad
     *
     * @ORM\ManyToOne(targetEntity="CrmActividad")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="actividad", referencedColumnName="id")
     * })
     */
    private $actividad;

    /**
     * @var \FacFactura
     *
     * @ORM\ManyToOne(targetEntity="FacFactura")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="factura", referencedColumnName="id")
     * })
     */
    private $factura;

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
     * @var \CrmCotizacion
     *
     * @ORM\ManyToOne(targetEntity="CrmCotizacion")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="cotizacion", referencedColumnName="id")
     * })
     */
    private $cotizacion;

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
     * @var \CrmCampania
     *
     * @ORM\ManyToOne(targetEntity="CrmCampania")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="campania", referencedColumnName="id")
     * })
     */
    private $campania;

    /**
     * @var \CrmContacto
     *
     * @ORM\ManyToOne(targetEntity="CrmContacto")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="contacto", referencedColumnName="id")
     * })
     */
    private $contacto;

    /**
     * @var \CrmProyecto
     *
     * @ORM\ManyToOne(targetEntity="CrmProyecto")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="proyecto", referencedColumnName="id")
     * })
     */
    private $proyecto;



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
     * @return HistorialAcciones
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
     * Set accion
     *
     * @param string $accion
     * @return HistorialAcciones
     */
    public function setAccion($accion)
    {
        $this->accion = $accion;

        return $this;
    }

    /**
     * Get accion
     *
     * @return string 
     */
    public function getAccion()
    {
        return $this->accion;
    }

    /**
     * Set srcArchivo
     *
     * @param string $srcArchivo
     * @return HistorialAcciones
     */
    public function setSrcArchivo($srcArchivo)
    {
        $this->srcArchivo = $srcArchivo;

        return $this;
    }

    /**
     * Get srcArchivo
     *
     * @return string 
     */
    public function getSrcArchivo()
    {
        return $this->srcArchivo;
    }

    /**
     * Set tipoAccion
     *
     * @param \ERP\CRMBundle\Entity\CtlTipoAccion $tipoAccion
     * @return HistorialAcciones
     */
    public function setTipoAccion(\ERP\CRMBundle\Entity\CtlTipoAccion $tipoAccion = null)
    {
        $this->tipoAccion = $tipoAccion;

        return $this;
    }

    /**
     * Get tipoAccion
     *
     * @return \ERP\CRMBundle\Entity\CtlTipoAccion 
     */
    public function getTipoAccion()
    {
        return $this->tipoAccion;
    }

    /**
     * Set usuario
     *
     * @param \ERP\CRMBundle\Entity\CtlUsuario $usuario
     * @return HistorialAcciones
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
     * Set producto
     *
     * @param \ERP\CRMBundle\Entity\CtlProducto $producto
     * @return HistorialAcciones
     */
    public function setProducto(\ERP\CRMBundle\Entity\CtlProducto $producto = null)
    {
        $this->producto = $producto;

        return $this;
    }

    /**
     * Get producto
     *
     * @return \ERP\CRMBundle\Entity\CtlProducto 
     */
    public function getProducto()
    {
        return $this->producto;
    }

    /**
     * Set actividad
     *
     * @param \ERP\CRMBundle\Entity\CrmActividad $actividad
     * @return HistorialAcciones
     */
    public function setActividad(\ERP\CRMBundle\Entity\CrmActividad $actividad = null)
    {
        $this->actividad = $actividad;

        return $this;
    }

    /**
     * Get actividad
     *
     * @return \ERP\CRMBundle\Entity\CrmActividad 
     */
    public function getActividad()
    {
        return $this->actividad;
    }

    /**
     * Set factura
     *
     * @param \ERP\CRMBundle\Entity\FacFactura $factura
     * @return HistorialAcciones
     */
    public function setFactura(\ERP\CRMBundle\Entity\FacFactura $factura = null)
    {
        $this->factura = $factura;

        return $this;
    }

    /**
     * Get factura
     *
     * @return \ERP\CRMBundle\Entity\FacFactura 
     */
    public function getFactura()
    {
        return $this->factura;
    }

    /**
     * Set oportunidad
     *
     * @param \ERP\CRMBundle\Entity\CrmOportunidad $oportunidad
     * @return HistorialAcciones
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
     * Set cotizacion
     *
     * @param \ERP\CRMBundle\Entity\CrmCotizacion $cotizacion
     * @return HistorialAcciones
     */
    public function setCotizacion(\ERP\CRMBundle\Entity\CrmCotizacion $cotizacion = null)
    {
        $this->cotizacion = $cotizacion;

        return $this;
    }

    /**
     * Get cotizacion
     *
     * @return \ERP\CRMBundle\Entity\CrmCotizacion 
     */
    public function getCotizacion()
    {
        return $this->cotizacion;
    }

    /**
     * Set cuenta
     *
     * @param \ERP\CRMBundle\Entity\CrmCuenta $cuenta
     * @return HistorialAcciones
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

    /**
     * Set campania
     *
     * @param \ERP\CRMBundle\Entity\CrmCampania $campania
     * @return HistorialAcciones
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
     * Set contacto
     *
     * @param \ERP\CRMBundle\Entity\CrmContacto $contacto
     * @return HistorialAcciones
     */
    public function setContacto(\ERP\CRMBundle\Entity\CrmContacto $contacto = null)
    {
        $this->contacto = $contacto;

        return $this;
    }

    /**
     * Get contacto
     *
     * @return \ERP\CRMBundle\Entity\CrmContacto 
     */
    public function getContacto()
    {
        return $this->contacto;
    }

    /**
     * Set proyecto
     *
     * @param \ERP\CRMBundle\Entity\CrmProyecto $proyecto
     * @return HistorialAcciones
     */
    public function setProyecto(\ERP\CRMBundle\Entity\CrmProyecto $proyecto = null)
    {
        $this->proyecto = $proyecto;

        return $this;
    }

    /**
     * Get proyecto
     *
     * @return \ERP\CRMBundle\Entity\CrmProyecto 
     */
    public function getProyecto()
    {
        return $this->proyecto;
    }
}
