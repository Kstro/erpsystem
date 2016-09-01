<?php

namespace ERP\CRMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CrmActividadCampania
 *
 * @ORM\Table(name="crm_actividad_campania", indexes={@ORM\Index(name="fk_crm_actividad_campania_crm_actividad1_idx", columns={"actividad"}), @ORM\Index(name="fk_crm_actividad_campania_crm_campania1_idx", columns={"campania"})})
 * @ORM\Entity
 */
class CrmActividadCampania
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
     * @var \CrmCampania
     *
     * @ORM\ManyToOne(targetEntity="CrmCampania")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="campania", referencedColumnName="id")
     * })
     */
    private $campania;



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
     * @return CrmActividadCampania
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
     * Set campania
     *
     * @param \ERP\CRMBundle\Entity\CrmCampania $campania
     * @return CrmActividadCampania
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
}
