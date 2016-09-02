<?php

namespace ERP\CRMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CrmActividadCaso
 *
 * @ORM\Table(name="crm_actividad_caso", indexes={@ORM\Index(name="fk_crm_actividad_caso_crm_actividad1_idx", columns={"actividad"}), @ORM\Index(name="fk_crm_actividad_caso_crm_caso1_idx", columns={"caso"})})
 * @ORM\Entity
 */
class CrmActividadCaso
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
     * @var \CrmActividad
     *
     * @ORM\ManyToOne(targetEntity="CrmActividad")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="actividad", referencedColumnName="id")
     * })
     */
    private $actividad;

    /**
     * @var \CrmCaso
     *
     * @ORM\ManyToOne(targetEntity="CrmCaso")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="caso", referencedColumnName="id")
     * })
     */
    private $caso;



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
     * Set actividad
     *
     * @param \ERP\CRMBundle\Entity\CrmActividad $actividad
     * @return CrmActividadCaso
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
     * Set caso
     *
     * @param \ERP\CRMBundle\Entity\CrmCaso $caso
     * @return CrmActividadCaso
     */
    public function setCaso(\ERP\CRMBundle\Entity\CrmCaso $caso = null)
    {
        $this->caso = $caso;

        return $this;
    }

    /**
     * Get caso
     *
     * @return \ERP\CRMBundle\Entity\CrmCaso 
     */
    public function getCaso()
    {
        return $this->caso;
    }
}
