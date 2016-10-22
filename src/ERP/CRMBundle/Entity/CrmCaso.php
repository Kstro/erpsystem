<?php

namespace ERP\CRMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CrmCaso
 *
 * @ORM\Table(name="crm_caso", indexes={@ORM\Index(name="fk_crm_caso_crm_tipo_caso1_idx", columns={"tipo_caso"}), @ORM\Index(name="fk_crm_caso_ctl_usuario1_idx", columns={"usuario_asignado"}), @ORM\Index(name="fk_crm_caso_ctl_prioridad1_idx", columns={"prioridad"})})
 * @ORM\Entity
 */
class CrmCaso
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
     * @var \CrmTipoCaso
     *
     * @ORM\ManyToOne(targetEntity="CrmTipoCaso")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="tipo_caso", referencedColumnName="id")
     * })
     */
    private $tipoCaso;

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
     * @var boolean
     *
     * @ORM\Column(name="estado", type="integer", nullable=true)
     */
    private $estado;

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
     * @return CrmCaso
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
     * @return CrmCaso
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
     * Set tipoCaso
     *
     * @param \ERP\CRMBundle\Entity\CrmTipoCaso $tipoCaso
     * @return CrmCaso
     */
    public function setTipoCaso(\ERP\CRMBundle\Entity\CrmTipoCaso $tipoCaso = null)
    {
        $this->tipoCaso = $tipoCaso;

        return $this;
    }

    /**
     * Get tipoCaso
     *
     * @return \ERP\CRMBundle\Entity\CrmTipoCaso 
     */
    public function getTipoCaso()
    {
        return $this->tipoCaso;
    }

    /**
     * Set usuarioAsignado
     *
     * @param \ERP\CRMBundle\Entity\CtlUsuario $usuarioAsignado
     * @return CrmCaso
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
     * @return CrmCaso
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
    
     /**
     * Set estado
     *
     * @param boolean $estado
     * @return CrmCaso
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
}
