<?php

namespace ERP\CRMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CtlPersona
 *
 * @ORM\Table(name="ctl_persona", indexes={@ORM\Index(name="fk_ctl_persona_ctl_sucursal1_idx", columns={"sucursal"}), @ORM\Index(name="fk_ctl_persona_ctl_tratamiento_protocolario1_idx", columns={"tratamiento_protocolario"})})
 * @ORM\Entity
 */
class CtlPersona
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
     * @ORM\Column(name="apellido", type="string", length=150, nullable=false)
     */
    private $apellido;

    /**
     * @var string
     *
     * @ORM\Column(name="genero", type="string", length=1, nullable=false)
     */
    private $genero;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_registro", type="datetime", nullable=false)
     */
    private $fechaRegistro;

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
     * @var \CtlTratamientoProtocolario
     *
     * @ORM\ManyToOne(targetEntity="CtlTratamientoProtocolario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="tratamiento_protocolario", referencedColumnName="id")
     * })
     */
    private $tratamientoProtocolario;



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
     * @return CtlPersona
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
     * Set apellido
     *
     * @param string $apellido
     * @return CtlPersona
     */
    public function setApellido($apellido)
    {
        $this->apellido = $apellido;

        return $this;
    }

    /**
     * Get apellido
     *
     * @return string 
     */
    public function getApellido()
    {
        return $this->apellido;
    }

    /**
     * Set genero
     *
     * @param string $genero
     * @return CtlPersona
     */
    public function setGenero($genero)
    {
        $this->genero = $genero;

        return $this;
    }

    /**
     * Get genero
     *
     * @return string 
     */
    public function getGenero()
    {
        return $this->genero;
    }

    /**
     * Set fechaRegistro
     *
     * @param \DateTime $fechaRegistro
     * @return CtlPersona
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
     * Set sucursal
     *
     * @param \ERP\CRMBundle\Entity\CtlSucursal $sucursal
     * @return CtlPersona
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
     * Set tratamientoProtocolario
     *
     * @param \ERP\CRMBundle\Entity\CtlTratamientoProtocolario $tratamientoProtocolario
     * @return CtlPersona
     */
    public function setTratamientoProtocolario(\ERP\CRMBundle\Entity\CtlTratamientoProtocolario $tratamientoProtocolario = null)
    {
        $this->tratamientoProtocolario = $tratamientoProtocolario;

        return $this;
    }

    /**
     * Get tratamientoProtocolario
     *
     * @return \ERP\CRMBundle\Entity\CtlTratamientoProtocolario 
     */
    public function getTratamientoProtocolario()
    {
        return $this->tratamientoProtocolario;
    }
}
