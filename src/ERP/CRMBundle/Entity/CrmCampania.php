<?php

namespace ERP\CRMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CrmCampania
 *
 * @ORM\Table(name="crm_campania", indexes={@ORM\Index(name="fk_crm_campania_crm_tipo_campania1_idx", columns={"tipo_campania"})})
 * @ORM\Entity
 */
class CrmCampania
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
     * @ORM\Column(name="fecha_fin", type="datetime", nullable=false)
     */
    private $fechaFin;

    /**
     * @var boolean
     *
     * @ORM\Column(name="estado", type="boolean", nullable=false)
     */
    private $estado;

    /**
     * @var boolean
     *
     * @ORM\Column(name="estado_campania", type="string", nullable=false)
     */
    private $estadoCampania;

    /**
     * @var \CrmTipoCampania
     *
     * @ORM\ManyToOne(targetEntity="CrmTipoCampania")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="tipo_campania", referencedColumnName="id")
     * })
     */
    private $tipoCampania;



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
     * @return CrmCampania
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
     * @return CrmCampania
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
     * @return CrmCampania
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
     * @return CrmCampania
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
     * @return CrmCampania
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
     * @param boolean $estado
     * @return CrmCampania
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
     * Set tipoCampania
     *
     * @param \ERP\CRMBundle\Entity\CrmTipoCampania $tipoCampania
     * @return CrmCampania
     */
    public function setTipoCampania(\ERP\CRMBundle\Entity\CrmTipoCampania $tipoCampania = null)
    {
        $this->tipoCampania = $tipoCampania;

        return $this;
    }

    /**
     * Get tipoCampania
     *
     * @return \ERP\CRMBundle\Entity\CrmTipoCampania 
     */
    public function getTipoCampania()
    {
        return $this->tipoCampania;
    }




    /**
     * Set estadoCampania
     *
     * @param string $estadoCampania
     * @return CrmCampania
     */
    public function setEstadoCampania($estadoCampania)
    {
        $this->estadoCampania = $estadoCampania;

        return $this;
    }

    /**
     * Get estadoCampania
     *
     * @return string 
     */
    public function getEstadoCampania()
    {
        return $this->estadoCampania;
    }
}
