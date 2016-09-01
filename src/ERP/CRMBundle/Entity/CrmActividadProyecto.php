<?php

namespace ERP\CRMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CrmActividadProyecto
 *
 * @ORM\Table(name="crm_actividad_proyecto", indexes={@ORM\Index(name="fk_crm_actividad_proyecto_crm_actividad1_idx", columns={"actividad"}), @ORM\Index(name="fk_crm_actividad_proyecto_crm_proyecto1_idx", columns={"proyecto"})})
 * @ORM\Entity
 */
class CrmActividadProyecto
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
     * @ORM\Column(name="fecha_registro", type="datetime", nullable=false)
     */
    private $fechaRegistro;

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
     * @var \CrmProyecto
     *
     * @ORM\ManyToOne(targetEntity="CrmProyecto")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="proyecto", referencedColumnName="id")
     * })
     */
    private $proyecto;



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
     * Set fechaRegistro
     *
     * @param \DateTime $fechaRegistro
     * @return CrmActividadProyecto
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
     * Set actividad
     *
     * @param \ERP\CRMBundle\Entity\CrmActividad $actividad
     * @return CrmActividadProyecto
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
     * Set proyecto
     *
     * @param \ERP\CRMBundle\Entity\CrmProyecto $proyecto
     * @return CrmActividadProyecto
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
}
