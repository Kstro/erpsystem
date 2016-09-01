<?php

namespace ERP\CRMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CrmPersonaProyecto
 *
 * @ORM\Table(name="crm_persona_proyecto", indexes={@ORM\Index(name="fk_crm_persona_proyecto_crm_proyecto1_idx", columns={"proyecto"}), @ORM\Index(name="fk_crm_persona_proyecto_ctl_persona1_idx", columns={"persona"})})
 * @ORM\Entity
 */
class CrmPersonaProyecto
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
     * @var \CrmProyecto
     *
     * @ORM\ManyToOne(targetEntity="CrmProyecto")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="proyecto", referencedColumnName="id")
     * })
     */
    private $proyecto;

    /**
     * @var \CtlPersona
     *
     * @ORM\ManyToOne(targetEntity="CtlPersona")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="persona", referencedColumnName="id")
     * })
     */
    private $persona;



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
     * Set fechaInicio
     *
     * @param \DateTime $fechaInicio
     * @return CrmPersonaProyecto
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
     * @return CrmPersonaProyecto
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
     * Set proyecto
     *
     * @param \ERP\CRMBundle\Entity\CrmProyecto $proyecto
     * @return CrmPersonaProyecto
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

    /**
     * Set persona
     *
     * @param \ERP\CRMBundle\Entity\CtlPersona $persona
     * @return CrmPersonaProyecto
     */
    public function setPersona(\ERP\CRMBundle\Entity\CtlPersona $persona = null)
    {
        $this->persona = $persona;

        return $this;
    }

    /**
     * Get persona
     *
     * @return \ERP\CRMBundle\Entity\CtlPersona 
     */
    public function getPersona()
    {
        return $this->persona;
    }
}
