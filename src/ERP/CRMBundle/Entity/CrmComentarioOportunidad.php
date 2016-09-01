<?php

namespace ERP\CRMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CrmComentarioOportunidad
 *
 * @ORM\Table(name="crm_comentario_oportunidad", indexes={@ORM\Index(name="fk_crm_comentario_has_crm_oportunidad_crm_oportunidad1_idx", columns={"oportunidad"}), @ORM\Index(name="fk_crm_comentario_oportunidad_ctl_usuario1_idx", columns={"usuario"})})
 * @ORM\Entity
 */
class CrmComentarioOportunidad
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
     * @ORM\Column(name="comentario", type="text", length=65535, nullable=false)
     */
    private $comentario;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_registro", type="datetime", nullable=false)
     */
    private $fechaRegistro;

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
     *   @ORM\JoinColumn(name="usuario", referencedColumnName="id")
     * })
     */
    private $usuario;



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
     * Set comentario
     *
     * @param string $comentario
     * @return CrmComentarioOportunidad
     */
    public function setComentario($comentario)
    {
        $this->comentario = $comentario;

        return $this;
    }

    /**
     * Get comentario
     *
     * @return string 
     */
    public function getComentario()
    {
        return $this->comentario;
    }

    /**
     * Set fechaRegistro
     *
     * @param \DateTime $fechaRegistro
     * @return CrmComentarioOportunidad
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
     * Set oportunidad
     *
     * @param \ERP\CRMBundle\Entity\CrmOportunidad $oportunidad
     * @return CrmComentarioOportunidad
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
     * Set usuario
     *
     * @param \ERP\CRMBundle\Entity\CtlUsuario $usuario
     * @return CrmComentarioOportunidad
     */
    public function setUsuario(\ERP\CRMBundle\Entity\CtlUsuario $usuario = null)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return \ERP\CRMBundle\Entity\CtlUsuario 
     */
    public function getUsuario()
    {
        return $this->usuario;
    }
}
