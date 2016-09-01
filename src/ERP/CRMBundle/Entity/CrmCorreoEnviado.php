<?php

namespace ERP\CRMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CrmCorreoEnviado
 *
 * @ORM\Table(name="crm_correo_enviado", indexes={@ORM\Index(name="fk_crm_correo_crm_plantilla_correo1_idx", columns={"plantilla_correo"}), @ORM\Index(name="fk_crm_correo_ctl_usuario1_idx", columns={"usuario"})})
 * @ORM\Entity
 */
class CrmCorreoEnviado
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
     * @ORM\Column(name="asunto", type="string", length=100, nullable=false)
     */
    private $asunto;

    /**
     * @var string
     *
     * @ORM\Column(name="correo_aquien_envia", type="string", length=60, nullable=false)
     */
    private $correoAquienEnvia;

    /**
     * @var string
     *
     * @ORM\Column(name="cuerpo_correo", type="text", length=65535, nullable=true)
     */
    private $cuerpoCorreo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_envio", type="datetime", nullable=false)
     */
    private $fechaEnvio;

    /**
     * @var integer
     *
     * @ORM\Column(name="estado", type="integer", nullable=false)
     */
    private $estado;

    /**
     * @var \CrmPlantillaCorreo
     *
     * @ORM\ManyToOne(targetEntity="CrmPlantillaCorreo")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="plantilla_correo", referencedColumnName="id")
     * })
     */
    private $plantillaCorreo;

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
     * Set asunto
     *
     * @param string $asunto
     * @return CrmCorreoEnviado
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
     * Set correoAquienEnvia
     *
     * @param string $correoAquienEnvia
     * @return CrmCorreoEnviado
     */
    public function setCorreoAquienEnvia($correoAquienEnvia)
    {
        $this->correoAquienEnvia = $correoAquienEnvia;

        return $this;
    }

    /**
     * Get correoAquienEnvia
     *
     * @return string 
     */
    public function getCorreoAquienEnvia()
    {
        return $this->correoAquienEnvia;
    }

    /**
     * Set cuerpoCorreo
     *
     * @param string $cuerpoCorreo
     * @return CrmCorreoEnviado
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
     * Set fechaEnvio
     *
     * @param \DateTime $fechaEnvio
     * @return CrmCorreoEnviado
     */
    public function setFechaEnvio($fechaEnvio)
    {
        $this->fechaEnvio = $fechaEnvio;

        return $this;
    }

    /**
     * Get fechaEnvio
     *
     * @return \DateTime 
     */
    public function getFechaEnvio()
    {
        return $this->fechaEnvio;
    }

    /**
     * Set estado
     *
     * @param integer $estado
     * @return CrmCorreoEnviado
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

    /**
     * Set plantillaCorreo
     *
     * @param \ERP\CRMBundle\Entity\CrmPlantillaCorreo $plantillaCorreo
     * @return CrmCorreoEnviado
     */
    public function setPlantillaCorreo(\ERP\CRMBundle\Entity\CrmPlantillaCorreo $plantillaCorreo = null)
    {
        $this->plantillaCorreo = $plantillaCorreo;

        return $this;
    }

    /**
     * Get plantillaCorreo
     *
     * @return \ERP\CRMBundle\Entity\CrmPlantillaCorreo 
     */
    public function getPlantillaCorreo()
    {
        return $this->plantillaCorreo;
    }

    /**
     * Set usuario
     *
     * @param \ERP\CRMBundle\Entity\CtlUsuario $usuario
     * @return CrmCorreoEnviado
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
