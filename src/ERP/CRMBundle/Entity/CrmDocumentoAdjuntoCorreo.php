<?php

namespace ERP\CRMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CrmDocumentoAdjuntoCorreo
 *
 * @ORM\Table(name="crm_documento_adjunto_correo", indexes={@ORM\Index(name="fk_crm_documento_adjunto_correo_crm_correo1_idx", columns={"correo"})})
 * @ORM\Entity
 */
class CrmDocumentoAdjuntoCorreo
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
     * @var \CrmCorreoEnviado
     *
     * @ORM\ManyToOne(targetEntity="CrmCorreoEnviado")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="correo", referencedColumnName="id")
     * })
     */
    private $correo;



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
     * @return CrmDocumentoAdjuntoCorreo
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
     * Set correo
     *
     * @param \ERP\CRMBundle\Entity\CrmCorreoEnviado $correo
     * @return CrmDocumentoAdjuntoCorreo
     */
    public function setCorreo(\ERP\CRMBundle\Entity\CrmCorreoEnviado $correo = null)
    {
        $this->correo = $correo;

        return $this;
    }

    /**
     * Get correo
     *
     * @return \ERP\CRMBundle\Entity\CrmCorreoEnviado 
     */
    public function getCorreo()
    {
        return $this->correo;
    }
}
