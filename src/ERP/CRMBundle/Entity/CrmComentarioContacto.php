<?php

namespace ERP\CRMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CrmComentarioContacto
 *
 * @ORM\Table(name="crm_comentario_contacto", indexes={@ORM\Index(name="fk_crm_comentario_has_crm_contacto_crm_contacto1_idx", columns={"contacto"}), @ORM\Index(name="fk_crm_comentario_contacto_ctl_usuario1_idx", columns={"usuario"})})
 * @ORM\Entity
 */
class CrmComentarioContacto
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
     * @var string
     *
     * @ORM\Column(name="tipo_comentario", type="integer", nullable=false)
     */
    private $tipoComentario;

    /**
     * @var \CrmContacto
     *
     * @ORM\ManyToOne(targetEntity="CrmContacto")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="contacto", referencedColumnName="id")
     * })
     */
    private $contacto;

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
     * @return CrmComentarioContacto
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
     * @return CrmComentarioContacto
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
     * Set contacto
     *
     * @param \ERP\CRMBundle\Entity\CrmContacto $contacto
     * @return CrmComentarioContacto
     */
    public function setContacto(\ERP\CRMBundle\Entity\CrmContacto $contacto = null)
    {
        $this->contacto = $contacto;

        return $this;
    }

    /**
     * Get contacto
     *
     * @return \ERP\CRMBundle\Entity\CrmContacto 
     */
    public function getContacto()
    {
        return $this->contacto;
    }

    /**
     * Set usuario
     *
     * @param \ERP\CRMBundle\Entity\CtlUsuario $usuario
     * @return CrmComentarioContacto
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
    
    /**
     * Set tipoComentario
     *
     * @param string $tipoComentario
     * @return CrmComentarioActividad
     */
    public function setTipoComentario($tipoComentario) {
        $this->tipoComentario = $tipoComentario;

        return $this;
    }

    /**
     * Get tipoComentario
     *
     * @return string 
     */
    public function getTipoComentario() {
        return $this->tipoComentario;
    }
}
