<?php

namespace ERP\CRMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CrmProyecto
 *
 * @ORM\Table(name="crm_proyecto", indexes={@ORM\Index(name="fk_crm_proyecto_ctl_prioridad1_idx", columns={"prioridad"}), @ORM\Index(name="fk_crm_proyecto_ctl_tipo_proyecto1_idx", columns={"tipo_proyecto"})})
 * @ORM\Entity
 */
class CrmProyecto
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
     * @ORM\Column(name="nombre", type="string", length=250, nullable=false)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="text", length=65535, nullable=true)
     */
    private $descripcion;

    /**
     * @var string
     *
     * @ORM\Column(name="observaciones", type="text", length=65535, nullable=true)
     */
    private $observaciones;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_registro", type="datetime", nullable=false)
     */
    private $fechaRegistro;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_fin_esperada", type="datetime", nullable=false)
     */
    private $fechaFinEsperada;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_fin", type="datetime", nullable=true)
     */
    private $fechaFin;

    /**
     * @var integer
     *
     * @ORM\Column(name="estado", type="integer", nullable=false)
     */
    private $estado;

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
     * @var \CtlTipoProyecto
     *
     * @ORM\ManyToOne(targetEntity="CtlTipoProyecto")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="tipo_proyecto", referencedColumnName="id")
     * })
     */
    private $tipoProyecto;



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
     * @return CrmProyecto
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
     * @return CrmProyecto
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
     * Set observaciones
     *
     * @param string $observaciones
     * @return CrmProyecto
     */
    public function setObservaciones($observaciones)
    {
        $this->observaciones = $observaciones;

        return $this;
    }

    /**
     * Get observaciones
     *
     * @return string 
     */
    public function getObservaciones()
    {
        return $this->observaciones;
    }

    /**
     * Set fechaRegistro
     *
     * @param \DateTime $fechaRegistro
     * @return CrmProyecto
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
     * Set fechaFinEsperada
     *
     * @param \DateTime $fechaFinEsperada
     * @return CrmProyecto
     */
    public function setFechaFinEsperada($fechaFinEsperada)
    {
        $this->fechaFinEsperada = $fechaFinEsperada;

        return $this;
    }

    /**
     * Get fechaFinEsperada
     *
     * @return \DateTime 
     */
    public function getFechaFinEsperada()
    {
        return $this->fechaFinEsperada;
    }

    /**
     * Set fechaFin
     *
     * @param \DateTime $fechaFin
     * @return CrmProyecto
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
     * Set estado
     *
     * @param integer $estado
     * @return CrmProyecto
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
     * Set prioridad
     *
     * @param \ERP\CRMBundle\Entity\CtlPrioridad $prioridad
     * @return CrmProyecto
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
     * Set tipoProyecto
     *
     * @param \ERP\CRMBundle\Entity\CtlTipoProyecto $tipoProyecto
     * @return CrmProyecto
     */
    public function setTipoProyecto(\ERP\CRMBundle\Entity\CtlTipoProyecto $tipoProyecto = null)
    {
        $this->tipoProyecto = $tipoProyecto;

        return $this;
    }

    /**
     * Get tipoProyecto
     *
     * @return \ERP\CRMBundle\Entity\CtlTipoProyecto 
     */
    public function getTipoProyecto()
    {
        return $this->tipoProyecto;
    }
}
