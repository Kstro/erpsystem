<?php

namespace ERP\CRMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CrmCasoCuenta
 *
 * @ORM\Table(name="crm_caso_cuenta", indexes={@ORM\Index(name="fk_crm_caso_cuenta_crm_cuenta1_idx", columns={"cuenta"}), @ORM\Index(name="fk_crm_caso_cuenta_crm_caso1_idx", columns={"caso"})})
 * @ORM\Entity
 */
class CrmCasoCuenta
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
     * @ORM\ManyToOne(targetEntity="CrmCuenta")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="cuenta", referencedColumnName="id")
     * })
     */
    private $cuenta;

    /**
     * @var \CrmCaso
     *
     * @ORM\ManyToOne(targetEntity="CrmCaso")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="caso", referencedColumnName="id")
     * })
     */
    private $caso;



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
     * @return CrmCasoCuenta
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
     * Set caso
     *
     * @param \ERP\CRMBundle\Entity\CrmCaso $caso
     * @return CrmCasoCuenta
     */
    public function setCaso(\ERP\CRMBundle\Entity\CrmCaso $caso = null)
    {
        $this->caso = $caso;

        return $this;
    }

    /**
     * Get caso
     *
     * @return \ERP\CRMBundle\Entity\CrmCaso 
     */
    public function getCaso()
    {
        return $this->caso;
    }
}
