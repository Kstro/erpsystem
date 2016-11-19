<?php

namespace ERP\CRMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CrmContactoCuenta
 *
 * @ORM\Table(name="crm_contacto_cuenta", indexes={@ORM\Index(name="fk_crm_contacto_cuenta_crm_cuenta1_idx", columns={"cuenta"}), @ORM\Index(name="fk_crm_contacto_cuenta_crm_contacto1_idx", columns={"contacto"})})
 * @ORM\Entity
 */
class CrmContactoCuenta
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
     * @var \CrmCuenta
     *
     * @ORM\ManyToOne(targetEntity="CrmCuenta", inversedBy="contactoCuenta", cascade={"persist", "remove"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="cuenta", referencedColumnName="id")
     * })
     */
    private $cuenta;
        
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
     * @var int
     *
     * @ORM\Column(name="titular_cuenta", type="integer", length=65535, nullable=false)
     */
    private $titular;

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
     * Set cuenta
     *
     * @param \ERP\CRMBundle\Entity\CrmCuenta $cuenta
     * @return CrmContactoCuenta
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
     * Set contacto
     *
     * @param \ERP\CRMBundle\Entity\CrmContacto $contacto
     * @return CrmContactoCuenta
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
     * Set titular
     *
     * @param string $titular
     * @return CrmContactoCuenta
     */
    public function setTitular($titular)
    {
        $this->titular= $titular;

        return $this;
    }

    /**
     * Get titular
     *
     * @return int 
     */
    public function getTitular()
    {
        return $this->titular;
    }
}
