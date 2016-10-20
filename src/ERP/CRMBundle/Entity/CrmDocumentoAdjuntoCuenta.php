<?php

namespace ERP\CRMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CrmDocumentoAdjuntoCuenta
 *
 * @ORM\Table(name="crm_documento_adjunto_cuenta", indexes={@ORM\Index(name="fk_crm_documento_adjunto_usuario", columns={"usuario"}), @ORM\Index(name="fk_crm_documento_adjunto_cuenta", columns={"cuenta"})})
 * @ORM\Entity
 */
class CrmDocumentoAdjuntoCuenta
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
     * @var integer
     *
     * @ORM\Column(name="estado", type="integer", nullable=false)
     */
    private $estado;

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
     * @var \CrmCuenta
     *
     * @ORM\ManyToOne(targetEntity="CrmCuenta")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="cuenta", referencedColumnName="id")
     * })
     */
    private $cuenta;



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
     * @return CrmDocumentoAdjuntoCuenta
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
     * @return CrmDocumentoAdjuntoCuenta
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
     * @return CrmDocumentoAdjuntoCuenta
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
     * @param \ERP\CRMBundle\Entity\CrmCuenta $cuenta
     * @return CrmDocumentoAdjuntoCuenta
     */
    public function setCuenta(\ERP\CRMBundle\Entity\CrmCuenta $cuenta= null)
    {
        $this->cuenta= $cuenta;

        return $this;
    }

    /**
     * Get cotizacion
     *
     * @return \ERP\CRMBundle\Entity\CrmCuenta
     */
    public function getCuenta()
    {
        return $this->cuenta;
    }
    
    
    
    
    /**
     * Set estado
     *
     * @param integer $estado
     * @return CrmDocumentoAdjuntoCuenta
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return CrmDocumentoAdjuntoCuenta
     */
    public function getEstado()
    {
        return $this->estado;
    }
    
    
    
    
    
}
