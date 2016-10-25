<?php

namespace ERP\CRMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CtlDireccion
 *
 * @ORM\Table(name="ctl_direccion", indexes={@ORM\Index(name="fk_crm_direccion_cuenta_ctl_ciudad1_idx", columns={"ciudad"}), @ORM\Index(name="fk_crm_direccion_persona_ctl_persona1_idx", columns={"persona"}), @ORM\Index(name="fk_ctl_direccion_ctl_empresa1_idx", columns={"empresa"}), @ORM\Index(name="fk_ctl_direccion_crm_cuenta1_idx", columns={"cuenta"})})
 * @ORM\Entity
 */
class CtlDireccion
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
     * @ORM\Column(name="direccion", type="text", length=65535, nullable=false)
     */
    private $direccion;
    /**
     * @var string
     *
     * @ORM\Column(name="city", type="text", length=65535, nullable=false)
     */
    private $city;
    /**
     * @var string
     *
     * @ORM\Column(name="state", type="text", length=65535, nullable=false)
     */
    private $state;
    /**
     * @var string
     *
     * @ORM\Column(name="zipcode", type="text", length=65535, nullable=false)
     */
    private $zipcode;

    /**
     * @var float
     *
     * @ORM\Column(name="latitud", type="float", precision=10, scale=0, nullable=true)
     */
    private $latitud;

    /**
     * @var float
     *
     * @ORM\Column(name="longitud", type="float", precision=10, scale=0, nullable=true)
     */
    private $longitud;

    /**
     * @var integer
     *
     * @ORM\Column(name="estado", type="integer", nullable=false)
     */
    private $estado;

    /**
     * @var \CtlCiudad
     *
     * @ORM\ManyToOne(targetEntity="CtlCiudad")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ciudad", referencedColumnName="id")
     * })
     */
    private $ciudad;

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
     * Set direccion
     *
     * @param string $direccion
     * @return CtlDireccion
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get direccion
     *
     * @return string 
     */
    public function getDireccion()
    {
        return $this->direccion;
    }
    
    /**
     * Set state
     *
     * @param string $state
     * @return CtlDireccion
     */
    public function setState($state)
    {
        $this->state= $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return string 
     */
    public function getState()
    {
        return $this->state;
    }

    
    
    /**
     * Set city
     *
     * @param string Scity
     * @return CtlDireccion
     */
    public function setCity($city)
    {
        $this->city= $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }
    
    
    /**
     * Set zipcode
     *
     * @param string $zipcode
     * @return CtlDireccion
     */
    public function setZipCode($zipcode)
    {
        $this->zipcode= $zipcode;

        return $this;
    }

    /**
     * Get zipcode
     *
     * @return string 
     */
    public function getZipCode()
    {
        return $this->zipcode;
    }
    
    
    /**
     * Set latitud
     *
     * @param float $latitud
     * @return CtlDireccion
     */
    public function setLatitud($latitud)
    {
        $this->latitud = $latitud;

        return $this;
    }

    /**
     * Get latitud
     *
     * @return float 
     */
    public function getLatitud()
    {
        return $this->latitud;
    }

    /**
     * Set longitud
     *
     * @param float $longitud
     * @return CtlDireccion
     */
    public function setLongitud($longitud)
    {
        $this->longitud = $longitud;

        return $this;
    }

    /**
     * Get longitud
     *
     * @return float 
     */
    public function getLongitud()
    {
        return $this->longitud;
    }

    /**
     * Set estado
     *
     * @param integer $estado
     * @return CtlDireccion
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
     * Set ciudad
     *
     * @param \ERP\CRMBundle\Entity\CtlCiudad $ciudad
     * @return CtlDireccion
     */
    public function setCiudad(\ERP\CRMBundle\Entity\CtlCiudad $ciudad = null)
    {
        $this->ciudad = $ciudad;

        return $this;
    }

    /**
     * Get ciudad
     *
     * @return \ERP\CRMBundle\Entity\CtlCiudad 
     */
    public function getCiudad()
    {
        return $this->ciudad;
    }

    /**
     * Set persona
     *
     * @param \ERP\CRMBundle\Entity\CtlPersona $persona
     * @return CtlDireccion
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
     * @return CtlDireccion
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
     * Set cuenta
     *
     * @param \ERP\CRMBundle\Entity\CrmCuenta $cuenta
     * @return CtlDireccion
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
}
