<?php

namespace ERP\CRMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CrmComentarioCampania
 *
 * @ORM\Table(name="crm_comentario_campania", indexes={@ORM\Index(name="fk_crm_comentario_has_campania_campania1_idx", columns={"campania"}), @ORM\Index(name="fk_crm_comentario_campania_ctl_usuario1_idx", columns={"usuario"})})
 * @ORM\Entity
 */
class CrmComentarioCampania
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
     * @var \CrmCampania
     *
     * @ORM\ManyToOne(targetEntity="CrmCampania")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="campania", referencedColumnName="id")
     * })
     */
    private $campania;

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
     * @return CrmComentarioCampania
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
     * @return CrmComentarioCampania
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
     * Set campania
     *
     * @param \ERP\CRMBundle\Entity\CrmCampania $campania
     * @return CrmComentarioCampania
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
     * Set usuario
     *
     * @param \ERP\CRMBundle\Entity\CtlUsuario $usuario
     * @return CrmComentarioCampania
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
    public function setTipoComentario($tipoComentario)
    {
        $this->tipoComentario = $tipoComentario;

        return $this;
    }

    /**
     * Get tipoComentario
     *
     * @return string 
     */
    public function getTipoComentario()
    {
        return $this->tipoComentario;
    }
    
    
}
