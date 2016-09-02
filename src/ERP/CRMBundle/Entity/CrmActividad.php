<?php

namespace ERP\CRMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CrmActividad
 *
 * @ORM\Table(name="crm_actividad", indexes={@ORM\Index(name="fk_crm_tarea_crm_tipo_tarea1_idx", columns={"tipo_actividad"}), @ORM\Index(name="fk_crm_actividad_ctl_prioridad1_idx", columns={"prioridad"}), @ORM\Index(name="fk_crm_actividad_crm_estado_actividad1_idx", columns={"estado_actividad"}), @ORM\Index(name="fk_crm_actividad_ctl_sucursal1_idx", columns={"sucursal"})})
 * @ORM\Entity
 */
class CrmActividad
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
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_inicio", type="datetime", nullable=false)
     */
    private $fechaInicio;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_fin", type="datetime", nullable=true)
     */
    private $fechaFin;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_cancelacion", type="datetime", nullable=true)
     */
    private $fechaCancelacion;

    /**
     * @var \CrmTipoActividad
     *
     * @ORM\ManyToOne(targetEntity="CrmTipoActividad")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="tipo_actividad", referencedColumnName="id")
     * })
     */
    private $tipoActividad;

    /**
     * @var \CtlPrioridad
     *
     * @ORM\ManyToOne(targetEntity="CtlPrioridad")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="prioridad", referencedColumnName="id")
     * })
     */
    private $prioridad;

    /**
     * @var \CrmEstadoActividad
     *
     * @ORM\ManyToOne(targetEntity="CrmEstadoActividad")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="estado_actividad", referencedColumnName="id")
     * })
     */
    private $estadoActividad;

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
     * @return CrmActividad
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
     * @return CrmActividad
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
     * @return CrmActividad
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
     * Set fechaInicio
     *
     * @param \DateTime $fechaInicio
     * @return CrmActividad
     */
    public function setFechaInicio($fechaInicio)
    {
        $this->fechaInicio = $fechaInicio;

        return $this;
    }

    /**
     * Get fechaInicio
     *
     * @return \DateTime 
     */
    public function getFechaInicio()
    {
        return $this->fechaInicio;
    }

    /**
     * Set fechaFin
     *
     * @param \DateTime $fechaFin
     * @return CrmActividad
     */
    public function setFechaFin($fechaFin)
    {
        $this->fechaFin = $fechaFin;

        return $this;
    }

    /**
     * Get fechaFin
     *
     * @return \DateTime 
     */
    public function getFechaFin()
    {
        return $this->fechaFin;
    }

    /**
     * Set fechaCancelacion
     *
     * @param \DateTime $fechaCancelacion
     * @return CrmActividad
     */
    public function setFechaCancelacion($fechaCancelacion)
    {
        $this->fechaCancelacion = $fechaCancelacion;

        return $this;
    }

    /**
     * Get fechaCancelacion
     *
     * @return \DateTime 
     */
    public function getFechaCancelacion()
    {
        return $this->fechaCancelacion;
    }

    /**
     * Set tipoActividad
     *
     * @param \ERP\CRMBundle\Entity\CrmTipoActividad $tipoActividad
     * @return CrmActividad
     */
    public function setTipoActividad(\ERP\CRMBundle\Entity\CrmTipoActividad $tipoActividad = null)
    {
        $this->tipoActividad = $tipoActividad;

        return $this;
    }

    /**
     * Get tipoActividad
     *
     * @return \ERP\CRMBundle\Entity\CrmTipoActividad 
     */
    public function getTipoActividad()
    {
        return $this->tipoActividad;
    }

    /**
     * Set prioridad
     *
     * @param \ERP\CRMBundle\Entity\CtlPrioridad $prioridad
     * @return CrmActividad
     */
    public function setPrioridad(\ERP\CRMBundle\Entity\CtlPrioridad $prioridad = null)
    {
        $this->prioridad = $prioridad;

        return $this;
    }

    /**
     * Get prioridad
     *
     * @return \ERP\CRMBundle\Entity\CtlPrioridad 
     */
    public function getPrioridad()
    {
        return $this->prioridad;
    }

    /**
     * Set estadoActividad
     *
     * @param \ERP\CRMBundle\Entity\CrmEstadoActividad $estadoActividad
     * @return CrmActividad
     */
    public function setEstadoActividad(\ERP\CRMBundle\Entity\CrmEstadoActividad $estadoActividad = null)
    {
        $this->estadoActividad = $estadoActividad;

        return $this;
    }

    /**
     * Get estadoActividad
     *
     * @return \ERP\CRMBundle\Entity\CrmEstadoActividad 
     */
    public function getEstadoActividad()
    {
        return $this->estadoActividad;
    }

    /**
     * Set sucursal
     *
     * @param \ERP\CRMBundle\Entity\CtlSucursal $sucursal
     * @return CrmActividad
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
}
