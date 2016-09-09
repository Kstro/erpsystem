<?php

namespace ERP\CRMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CrmCuenta
 *
 * @ORM\Table(name="crm_cuenta", indexes={@ORM\Index(name="fk_crm_cuenta_crm_tipo_cuenta1_idx", columns={"tipo_cuenta"}), @ORM\Index(name="fk_crm_cuenta_ctl_industria1_idx", columns={"industria"}), @ORM\Index(name="fk_crm_cuenta_crm_cliente_potencial1_idx", columns={"cliente_potencial"}), @ORM\Index(name="fk_crm_cuenta_ctl_nivel_satisfaccion1_idx", columns={"nivel_satisfaccion"}), @ORM\Index(name="fk_crm_cuenta_ctl_tipo_entidad1_idx", columns={"tipo_entidad"})})
 * @ORM\Entity
 */
class CrmCuenta
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
     * @ORM\Column(name="descripcion", type="text", length=65535, nullable=true)
     */
    private $descripcion;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_registro", type="datetime", nullable=false)
     */
    private $fechaRegistro;

    /**
     * @var string
     *
     * @ORM\Column(name="sitio_web", type="string", length=100, nullable=true)
     */
    private $sitioWeb;

    /**
     * @var integer
     *
     * @ORM\Column(name="estado", type="integer", nullable=false)
     */
    private $estado;

    /**
     * @var \CrmTipoCuenta
     *
     * @ORM\ManyToOne(targetEntity="CrmTipoCuenta")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="tipo_cuenta", referencedColumnName="id")
     * })
     */
    private $tipoCuenta;

    /**
     * @var \CtlIndustria
     *
     * @ORM\ManyToOne(targetEntity="CtlIndustria")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="industria", referencedColumnName="id")
     * })
     */
    private $industria;

    /**
     * @var \CrmClientePotencial
     *
     * @ORM\ManyToOne(targetEntity="CrmClientePotencial")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="cliente_potencial", referencedColumnName="id")
     * })
     */
    private $clientePotencial;

    /**
     * @var \CtlNivelSatisfaccion
     *
     * @ORM\ManyToOne(targetEntity="CtlNivelSatisfaccion")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="nivel_satisfaccion", referencedColumnName="id")
     * })
     */
    private $nivelSatisfaccion;

    /**
     * @var \CtlTipoEntidad
     *
     * @ORM\ManyToOne(targetEntity="CtlTipoEntidad")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="tipo_entidad", referencedColumnName="id")
     * })
     */
    private $tipoEntidad;

     /**
     * @ORM\OneToMany(targetEntity="CrmContactoCuenta", mappedBy="cuenta", cascade={"persist", "remove"})
     */
    private $contactoCuenta;

    /**
     * @ORM\OneToMany(targetEntity="CtlCorreo", mappedBy="cuenta", cascade={"persist", "remove"})
     */
    private $correo;

    /**
     * @ORM\OneToMany(targetEntity="CtlTelefono", mappedBy="cuenta", cascade={"persist", "remove"})
     */
    private $telefono;



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
     * @return CrmCuenta
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
     * Set descripcion
     *
     * @param string $descripcion
     * @return CrmCuenta
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
     * Set fechaRegistro
     *
     * @param \DateTime $fechaRegistro
     * @return CrmCuenta
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
     * Set sitioWeb
     *
     * @param string $sitioWeb
     * @return CrmCuenta
     */
    public function setSitioWeb($sitioWeb)
    {
        $this->sitioWeb = $sitioWeb;

        return $this;
    }

    /**
     * Get sitioWeb
     *
     * @return string 
     */
    public function getSitioWeb()
    {
        return $this->sitioWeb;
    }

    /**
     * Set estado
     *
     * @param integer $estado
     * @return CrmCuenta
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
     * Set tipoCuenta
     *
     * @param \ERP\CRMBundle\Entity\CrmTipoCuenta $tipoCuenta
     * @return CrmCuenta
     */
    public function setTipoCuenta(\ERP\CRMBundle\Entity\CrmTipoCuenta $tipoCuenta = null)
    {
        $this->tipoCuenta = $tipoCuenta;

        return $this;
    }

    /**
     * Get tipoCuenta
     *
     * @return \ERP\CRMBundle\Entity\CrmTipoCuenta 
     */
    public function getTipoCuenta()
    {
        return $this->tipoCuenta;
    }

    /**
     * Set industria
     *
     * @param \ERP\CRMBundle\Entity\CtlIndustria $industria
     * @return CrmCuenta
     */
    public function setIndustria(\ERP\CRMBundle\Entity\CtlIndustria $industria = null)
    {
        $this->industria = $industria;

        return $this;
    }

    /**
     * Get industria
     *
     * @return \ERP\CRMBundle\Entity\CtlIndustria 
     */
    public function getIndustria()
    {
        return $this->industria;
    }

    /**
     * Set clientePotencial
     *
     * @param \ERP\CRMBundle\Entity\CrmClientePotencial $clientePotencial
     * @return CrmCuenta
     */
    public function setClientePotencial(\ERP\CRMBundle\Entity\CrmClientePotencial $clientePotencial = null)
    {
        $this->clientePotencial = $clientePotencial;

        return $this;
    }

    /**
     * Get clientePotencial
     *
     * @return \ERP\CRMBundle\Entity\CrmClientePotencial 
     */
    public function getClientePotencial()
    {
        return $this->clientePotencial;
    }

    /**
     * Set nivelSatisfaccion
     *
     * @param \ERP\CRMBundle\Entity\CtlNivelSatisfaccion $nivelSatisfaccion
     * @return CrmCuenta
     */
    public function setNivelSatisfaccion(\ERP\CRMBundle\Entity\CtlNivelSatisfaccion $nivelSatisfaccion = null)
    {
        $this->nivelSatisfaccion = $nivelSatisfaccion;

        return $this;
    }

    /**
     * Get nivelSatisfaccion
     *
     * @return \ERP\CRMBundle\Entity\CtlNivelSatisfaccion 
     */
    public function getNivelSatisfaccion()
    {
        return $this->nivelSatisfaccion;
    }

    /**
     * Set tipoEntidad
     *
     * @param \ERP\CRMBundle\Entity\CtlTipoEntidad $tipoEntidad
     * @return CrmCuenta
     */
    public function setTipoEntidad(\ERP\CRMBundle\Entity\CtlTipoEntidad $tipoEntidad = null)
    {
        $this->tipoEntidad = $tipoEntidad;

        return $this;
    }

    /**
     * Get tipoEntidad
     *
     * @return \ERP\CRMBundle\Entity\CtlTipoEntidad 
     */
    public function getTipoEntidad()
    {
        return $this->tipoEntidad;
    }


     /**
     * Get contactoCuenta
     *
     * @return \ERP\CRMBundle\Entity\CrmCuenta 
     */
    public function getContactoCuenta()
    {
        return $this->contactoCuenta;
    }
    
    
    /**
     * Set consulta
     *
     * @param \ERP\CRMBundle\Entity\CrmContactoCuenta 
     *
     * @return ContactoCuenta
     */
    public function setContactoCuenta(\ERP\CRMBundle\Entity\CrmContactoCuenta $contactoCuenta = null)
    {
        $this->contactoCuenta = $contactoCuenta;

        return $this;
    }



    /**
     * Get ctlCorreo
     *
     * @return \ERP\CRMBundle\Entity\CtlCorreo 
     */
    public function getCorreo()
    {
        return $this->correo;
    }
    
    
    /**
     * Set consulta
     *
     * @param \ERP\CRMBundle\Entity\CtlCorreo
     *
     * @return Correo
     */
    public function setCorreo(\ERP\CRMBundle\Entity\CrmCorreo $correo = null)
    {
        $this->correo = $correo;

        return $this;
    }


    /**
     * Get CtlTelefono
     *
     * @return \ERP\CRMBundle\Entity\CtlTelefono 
     */
    public function getCtlTelefono()
    {
        return $this->telefono;
    }
    
    
    /**
     * Set telefono
     *
     * @param \ERP\CRMBundle\Entity\CtlTelefono
     *
     * @return Telefono
     */
    public function setTelefono(\ERP\CRMBundle\Entity\CtlTelefono $telefono = null)
    {
        $this->telefono = $telefono;

        return $this;
    }



}
