<?php

namespace ERP\CRMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CrmPersonalCampania
 *
 * @ORM\Table(name="crm_personal_campania", indexes={@ORM\Index(name="fk_crm_asignacion_campania_crm_campania1_idx", columns={"campania"}), @ORM\Index(name="fk_crm_personal_campania_ctl_persona1_idx", columns={"persona_asignada"})})
 * @ORM\Entity
 */
class CrmPersonalCampania
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
     * @var integer
     *
     * @ORM\Column(name="estado", type="integer", nullable=false)
     */
    private $estado;

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
     * @var \CtlPersona
     *
     * @ORM\ManyToOne(targetEntity="CtlPersona")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="persona_asignada", referencedColumnName="id")
     * })
     */
    private $personaAsignada;



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
     * Set estado
     *
     * @param integer $estado
     * @return CrmPersonalCampania
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
     * Set campania
     *
     * @param \ERP\CRMBundle\Entity\CrmCampania $campania
     * @return CrmPersonalCampania
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
     * Set personaAsignada
     *
     * @param \ERP\CRMBundle\Entity\CtlPersona $personaAsignada
     * @return CrmPersonalCampania
     */
    public function setPersonaAsignada(\ERP\CRMBundle\Entity\CtlPersona $personaAsignada = null)
    {
        $this->personaAsignada = $personaAsignada;

        return $this;
    }

    /**
     * Get personaAsignada
     *
     * @return \ERP\CRMBundle\Entity\CtlPersona 
     */
    public function getPersonaAsignada()
    {
        return $this->personaAsignada;
    }
}
