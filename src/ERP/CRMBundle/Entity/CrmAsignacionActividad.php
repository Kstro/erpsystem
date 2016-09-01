<?php

namespace ERP\CRMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CrmAsignacionActividad
 *
 * @ORM\Table(name="crm_asignacion_actividad", indexes={@ORM\Index(name="fk_crm_asignado_tarea_crm_tarea1_idx", columns={"actividad"}), @ORM\Index(name="fk_crm_asignado_tarea_ctl_usuario1_idx", columns={"usuario_asignado"}), @ORM\Index(name="fk_crm_asignacion_actividad_ctl_tiempo_notificacion1_idx", columns={"tiempo_notificacion"}), @ORM\Index(name="fk_crm_asignacion_actividad_ctl_tipo_recordatorio1_idx", columns={"tipo_recordatorio"})})
 * @ORM\Entity
 */
class CrmAsignacionActividad
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
     * @var \CtlUsuario
     *
     * @ORM\ManyToOne(targetEntity="CtlUsuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="usuario_asignado", referencedColumnName="id")
     * })
     */
    private $usuarioAsignado;

    /**
     * @var \CtlTiempoNotificacion
     *
     * @ORM\ManyToOne(targetEntity="CtlTiempoNotificacion")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="tiempo_notificacion", referencedColumnName="id")
     * })
     */
    private $tiempoNotificacion;

    /**
     * @var \CtlTipoRecordatorio
     *
     * @ORM\ManyToOne(targetEntity="CtlTipoRecordatorio")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="tipo_recordatorio", referencedColumnName="id")
     * })
     */
    private $tipoRecordatorio;



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
     * @return CrmAsignacionActividad
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
     * Set usuarioAsignado
     *
     * @param \ERP\CRMBundle\Entity\CtlUsuario $usuarioAsignado
     * @return CrmAsignacionActividad
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
     * Set tiempoNotificacion
     *
     * @param \ERP\CRMBundle\Entity\CtlTiempoNotificacion $tiempoNotificacion
     * @return CrmAsignacionActividad
     */
    public function setTiempoNotificacion(\ERP\CRMBundle\Entity\CtlTiempoNotificacion $tiempoNotificacion = null)
    {
        $this->tiempoNotificacion = $tiempoNotificacion;

        return $this;
    }

    /**
     * Get tiempoNotificacion
     *
     * @return \ERP\CRMBundle\Entity\CtlTiempoNotificacion 
     */
    public function getTiempoNotificacion()
    {
        return $this->tiempoNotificacion;
    }

    /**
     * Set tipoRecordatorio
     *
     * @param \ERP\CRMBundle\Entity\CtlTipoRecordatorio $tipoRecordatorio
     * @return CrmAsignacionActividad
     */
    public function setTipoRecordatorio(\ERP\CRMBundle\Entity\CtlTipoRecordatorio $tipoRecordatorio = null)
    {
        $this->tipoRecordatorio = $tipoRecordatorio;

        return $this;
    }

    /**
     * Get tipoRecordatorio
     *
     * @return \ERP\CRMBundle\Entity\CtlTipoRecordatorio 
     */
    public function getTipoRecordatorio()
    {
        return $this->tipoRecordatorio;
    }
}
