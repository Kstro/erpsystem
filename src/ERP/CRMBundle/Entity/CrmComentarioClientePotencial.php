<?php

namespace ERP\CRMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CrmComentarioClientePotencial
 *
 * @ORM\Table(name="crm_comentario_cliente_potencial", indexes={@ORM\Index(name="fk_crm_comentario_has_crm_cliente_potencial_crm_cliente_pot_idx", columns={"cliente_potencial"}), @ORM\Index(name="fk_crm_comentario_cliente_potencial_ctl_usuario1_idx", columns={"usuario"})})
 * @ORM\Entity
 */
class CrmComentarioClientePotencial
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
     * @var \CrmClientePotencial
     *
     * @ORM\ManyToOne(targetEntity="CrmClientePotencial")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="cliente_potencial", referencedColumnName="id")
     * })
     */
    private $clientePotencial;

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
     * @return CrmComentarioClientePotencial
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
     * @return CrmComentarioClientePotencial
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
     * Set clientePotencial
     *
     * @param \ERP\CRMBundle\Entity\CrmClientePotencial $clientePotencial
     * @return CrmComentarioClientePotencial
     */
    public function setClientePotencial(\ERP\CRMBundle\Entity\CrmClientePotencial $clientePotencial = null)
    {
        $this->clientePotencial = $clientePotencial;

        return $this;
    }

    /**
     * Get clientePotencial
     *
     * @return \ERP\CRMBundle\Entity\CrmClientePotencial 
     */
    public function getClientePotencial()
    {
        return $this->clientePotencial;
    }

    /**
     * Set usuario
     *
     * @param \ERP\CRMBundle\Entity\CtlUsuario $usuario
     * @return CrmComentarioClientePotencial
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
