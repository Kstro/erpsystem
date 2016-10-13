<?php

namespace ERP\CRMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CtlTelefono
 *
 * @ORM\Table(name="ctl_telefono", indexes={@ORM\Index(name="fk_ctl_telefono_crm_cuenta1_idx", columns={"cuenta"}), @ORM\Index(name="fk_ctl_telefono_ctl_persona1_idx", columns={"persona"}), @ORM\Index(name="fk_ctl_telefono_ctl_empresa1_idx", columns={"empresa"}), @ORM\Index(name="fk_ctl_telefono_ctl_sucursal1_idx", columns={"sucursal"}), @ORM\Index(name="fk_ctl_telefono_ctl_tipo_telefono1_idx", columns={"tipo_telefono"})})
 * @ORM\Entity
 */
class CtlTelefono
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
     * @ORM\Column(name="num_telefonico", type="string", length=25, nullable=false)
     */
    private $numTelefonico;

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
     * @var \CtlPersona
     *
     * @ORM\ManyToOne(targetEntity="CtlPersona")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="persona", referencedColumnName="id")
     * })
     */
    private $persona;

    /**
     * @var \CtlEmpresa
     *
     * @ORM\ManyToOne(targetEntity="CtlEmpresa")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="empresa", referencedColumnName="id")
     * })
     */
    private $empresa;

    /**
     * @var \CtlSucursal
     *
     * @ORM\ManyToOne(targetEntity="CtlSucursal")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="sucursal", referencedColumnName="id")
     * })
     */
    private $sucursal;

    /**
     * @var \CtlTipoTelefono
     *
     * @ORM\ManyToOne(targetEntity="CtlTipoTelefono")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="tipo_telefono", referencedColumnName="id")
     * })
     */
    private $tipoTelefono;

     /**
     * @var string
     *
     * @ORM\Column(name="extension", type="string", length=25, nullable=false)
     */
    private $extension;


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
     * Set numTelefonico
     *
     * @param string $numTelefonico
     * @return CtlTelefono
     */
    public function setNumTelefonico($numTelefonico)
    {
        $this->numTelefonico = $numTelefonico;

        return $this;
    }

    /**
     * Get numTelefonico
     *
     * @return string 
     */
    public function getNumTelefonico()
    {
        return $this->numTelefonico;
    }

    /**
     * Set cuenta
     *
     * @param \ERP\CRMBundle\Entity\CrmCuenta $cuenta
     * @return CtlTelefono
     */
    public function setCuenta(\ERP\CRMBundle\Entity\CrmCuenta $cuenta = null)
    {
        $this->cuenta = $cuenta;

        return $this;
    }

    /**
     * Get cuenta
     *
     * @return \ERP\CRMBundle\Entity\CrmCuenta 
     */
    public function getCuenta()
    {
        return $this->cuenta;
    }

    /**
     * Set persona
     *
     * @param \ERP\CRMBundle\Entity\CtlPersona $persona
     * @return CtlTelefono
     */
    public function setPersona(\ERP\CRMBundle\Entity\CtlPersona $persona = null)
    {
        $this->persona = $persona;

        return $this;
    }

    /**
     * Get persona
     *
     * @return \ERP\CRMBundle\Entity\CtlPersona 
     */
    public function getPersona()
    {
        return $this->persona;
    }

    /**
     * Set empresa
     *
     * @param \ERP\CRMBundle\Entity\CtlEmpresa $empresa
     * @return CtlTelefono
     */
    public function setEmpresa(\ERP\CRMBundle\Entity\CtlEmpresa $empresa = null)
    {
        $this->empresa = $empresa;

        return $this;
    }

    /**
     * Get empresa
     *
     * @return \ERP\CRMBundle\Entity\CtlEmpresa 
     */
    public function getEmpresa()
    {
        return $this->empresa;
    }

    /**
     * Set sucursal
     *
     * @param \ERP\CRMBundle\Entity\CtlSucursal $sucursal
     * @return CtlTelefono
     */
    public function setSucursal(\ERP\CRMBundle\Entity\CtlSucursal $sucursal = null)
    {
        $this->sucursal = $sucursal;

        return $this;
    }

    /**
     * Get sucursal
     *
     * @return \ERP\CRMBundle\Entity\CtlSucursal 
     */
    public function getSucursal()
    {
        return $this->sucursal;
    }

    /**
     * Set tipoTelefono
     *
     * @param \ERP\CRMBundle\Entity\CtlTipoTelefono $tipoTelefono
     * @return CtlTelefono
     */
    public function setTipoTelefono(\ERP\CRMBundle\Entity\CtlTipoTelefono $tipoTelefono = null)
    {
        $this->tipoTelefono = $tipoTelefono;

        return $this;
    }

    /**
     * Get tipoTelefono
     *
     * @return \ERP\CRMBundle\Entity\CtlTipoTelefono 
     */
    public function getTipoTelefono()
    {
        return $this->tipoTelefono;
    }

    /**
     * Set extension
     *
     * @param string $extension
     * @return CtlTelefono
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * Get extension
     *
     * @return string 
     */
    public function getExtension()
    {
        return $this->extension;
    }

}
