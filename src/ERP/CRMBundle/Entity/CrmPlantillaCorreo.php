<?php

namespace ERP\CRMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CrmPlantillaCorreo
 *
 * @ORM\Table(name="crm_plantilla_correo", indexes={@ORM\Index(name="fk_crm_plantilla_correo_ctl_usuario1_idx", columns={"usuario_asignado"})})
 * @ORM\Entity
 */
class CrmPlantillaCorreo
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
     * @ORM\Column(name="nombre", type="string", length=150, nullable=false)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="text", length=65535, nullable=true)
     */
    private $descripcion;

    /**
     * @var string
     *
     * @ORM\Column(name="asunto", type="string", length=125, nullable=true)
     */
    private $asunto;

    /**
     * @var string
     *
     * @ORM\Column(name="cuerpo_correo", type="text", length=65535, nullable=true)
     */
    private $cuerpoCorreo;

    /**
     * @var boolean
     *
     * @ORM\Column(name="estado", type="boolean", nullable=false)
     */
    private $estado;

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
     * @return CrmPlantillaCorreo
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
     * @return CrmPlantillaCorreo
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
     * Set asunto
     *
     * @param string $asunto
     * @return CrmPlantillaCorreo
     */
    public function setAsunto($asunto)
    {
        $this->asunto = $asunto;

        return $this;
    }

    /**
     * Get asunto
     *
     * @return string 
     */
    public function getAsunto()
    {
        return $this->asunto;
    }

    /**
     * Set cuerpoCorreo
     *
     * @param string $cuerpoCorreo
     * @return CrmPlantillaCorreo
     */
    public function setCuerpoCorreo($cuerpoCorreo)
    {
        $this->cuerpoCorreo = $cuerpoCorreo;

        return $this;
    }

    /**
     * Get cuerpoCorreo
     *
     * @return string 
     */
    public function getCuerpoCorreo()
    {
        return $this->cuerpoCorreo;
    }

    /**
     * Set estado
     *
     * @param boolean $estado
     * @return CrmPlantillaCorreo
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return boolean 
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set usuarioAsignado
     *
     * @param \ERP\CRMBundle\Entity\CtlUsuario $usuarioAsignado
     * @return CrmPlantillaCorreo
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
}
