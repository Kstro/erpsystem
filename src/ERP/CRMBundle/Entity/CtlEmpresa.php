<?php

namespace ERP\CRMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CtlEmpresa
 *
 * @ORM\Table(name="ctl_empresa", indexes={@ORM\Index(name="fk_ctl_empresa_ctl_industria1_idx", columns={"industria"})})
 * @ORM\Entity
 */
class CtlEmpresa
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
     * @var boolean
     *
     * @ORM\Column(name="wizard", type="boolean", nullable=true)
     */
    private $wizard;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="estado", type="integer", nullable=false)
     */
    private $estado;

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
     * @return CtlEmpresa
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
     * @return CtlEmpresa
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
     * Set wizard
     *
     * @param boolean $wizard
     * @return CtlEmpresa
     */
    public function setWizard($wizard)
    {
        $this->wizard = $wizard;

        return $this;
    }

    /**
     * Get wizard
     *
     * @return boolean 
     */
    public function getWizard()
    {
        return $this->wizard;
    }
    
    /**
     * Set estado
     *
     * @param integer $estado
     * @return CtlEmpresa
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
     * Set industria
     *
     * @param \ERP\CRMBundle\Entity\CtlIndustria $industria
     * @return CtlEmpresa
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
}
