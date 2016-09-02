<?php

namespace ERP\CRMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CrmDocumentoAdjuntoCotizacion
 *
 * @ORM\Table(name="crm_documento_adjunto_cotizacion", indexes={@ORM\Index(name="fk_crm_documento_adjunto_oportunidad_ctl_usuario1_idx", columns={"usuario"}), @ORM\Index(name="fk_crm_documento_adjunto_cotizacion_crm_cotizacion1_idx", columns={"cotizacion"})})
 * @ORM\Entity
 */
class CrmDocumentoAdjuntoCotizacion
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
     * @ORM\Column(name="src", type="string", length=255, nullable=false)
     */
    private $src;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_registro", type="datetime", nullable=false)
     */
    private $fechaRegistro;

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
     * @var \CrmCotizacion
     *
     * @ORM\ManyToOne(targetEntity="CrmCotizacion")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="cotizacion", referencedColumnName="id")
     * })
     */
    private $cotizacion;



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
     * Set src
     *
     * @param string $src
     * @return CrmDocumentoAdjuntoCotizacion
     */
    public function setSrc($src)
    {
        $this->src = $src;

        return $this;
    }

    /**
     * Get src
     *
     * @return string 
     */
    public function getSrc()
    {
        return $this->src;
    }

    /**
     * Set fechaRegistro
     *
     * @param \DateTime $fechaRegistro
     * @return CrmDocumentoAdjuntoCotizacion
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
     * Set usuario
     *
     * @param \ERP\CRMBundle\Entity\CtlUsuario $usuario
     * @return CrmDocumentoAdjuntoCotizacion
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
     * Set cotizacion
     *
     * @param \ERP\CRMBundle\Entity\CrmCotizacion $cotizacion
     * @return CrmDocumentoAdjuntoCotizacion
     */
    public function setCotizacion(\ERP\CRMBundle\Entity\CrmCotizacion $cotizacion = null)
    {
        $this->cotizacion = $cotizacion;

        return $this;
    }

    /**
     * Get cotizacion
     *
     * @return \ERP\CRMBundle\Entity\CrmCotizacion 
     */
    public function getCotizacion()
    {
        return $this->cotizacion;
    }
}
