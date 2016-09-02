<?php

namespace ERP\CRMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CrmAsignadoOportunidad
 *
 * @ORM\Table(name="crm_asignado_oportunidad", indexes={@ORM\Index(name="fk_crm_asignado_oportunidad_crm_oportunidad1_idx", columns={"oportunidad"}), @ORM\Index(name="fk_crm_asignado_oportunidad_ctl_usuario1_idx", columns={"usuario_asignado"}), @ORM\Index(name="fk_crm_asignado_oportunidad_ctl_prioridad1_idx", columns={"prioridad"})})
 * @ORM\Entity
 */
class CrmAsignadoOportunidad
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
     * @ORM\Column(name="fecha_limite", type="date", nullable=true)
     */
    private $fechaLimite;

    /**
     * @var \CrmOportunidad
     *
     * @ORM\ManyToOne(targetEntity="CrmOportunidad")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="oportunidad", referencedColumnName="id")
     * })
     */
    private $oportunidad;

    /**
     * @var \CtlUsuario
     *
     * @ORM\ManyToOne(targetEntity="CtlUsuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="usuario_asignado", referencedColumnName="id")
     * })
     */
    private $usuarioAsignado;

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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set fechaLimite
     *
     * @param \DateTime $fechaLimite
     * @return CrmAsignadoOportunidad
     */
    public function setFechaLimite($fechaLimite)
    {
        $this->fechaLimite = $fechaLimite;

        return $this;
    }

    /**
     * Get fechaLimite
     *
     * @return \DateTime 
     */
    public function getFechaLimite()
    {
        return $this->fechaLimite;
    }

    /**
     * Set oportunidad
     *
     * @param \ERP\CRMBundle\Entity\CrmOportunidad $oportunidad
     * @return CrmAsignadoOportunidad
     */
    public function setOportunidad(\ERP\CRMBundle\Entity\CrmOportunidad $oportunidad = null)
    {
        $this->oportunidad = $oportunidad;

        return $this;
    }

    /**
     * Get oportunidad
     *
     * @return \ERP\CRMBundle\Entity\CrmOportunidad 
     */
    public function getOportunidad()
    {
        return $this->oportunidad;
    }

    /**
     * Set usuarioAsignado
     *
     * @param \ERP\CRMBundle\Entity\CtlUsuario $usuarioAsignado
     * @return CrmAsignadoOportunidad
     */
    public function setUsuarioAsignado(\ERP\CRMBundle\Entity\CtlUsuario $usuarioAsignado = null)
    {
        $this->usuarioAsignado = $usuarioAsignado;

        return $this;
    }

    /**
     * Get usuarioAsignado
     *
     * @return \ERP\CRMBundle\Entity\CtlUsuario 
     */
    public function getUsuarioAsignado()
    {
        return $this->usuarioAsignado;
    }

    /**
     * Set prioridad
     *
     * @param \ERP\CRMBundle\Entity\CtlPrioridad $prioridad
     * @return CrmAsignadoOportunidad
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
}
