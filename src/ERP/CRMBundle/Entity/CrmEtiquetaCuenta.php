<?php

namespace ERP\CRMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CrmEtiquetaCuenta
 *
 * @ORM\Table(name="crm_etiqueta_cuenta", indexes={@ORM\Index(name="fk_crm_etiqueta_cuenta_crm_etiqueta1_idx", columns={"etiqueta"}), @ORM\Index(name="fk_crm_etiqueta_cuenta_crm_cuenta1_idx", columns={"cuenta"})})
 * @ORM\Entity
 */
class CrmEtiquetaCuenta
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
     * @var \CrmEtiqueta
     *
     * @ORM\ManyToOne(targetEntity="CrmEtiqueta")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="etiqueta", referencedColumnName="id")
     * })
     */
    private $etiqueta;

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
     * Set etiqueta
     *
     * @param \ERP\CRMBundle\Entity\CrmEtiqueta $etiqueta
     * @return CrmEtiquetaCuenta
     */
    public function setEtiqueta(\ERP\CRMBundle\Entity\CrmEtiqueta $etiqueta = null)
    {
        $this->etiqueta = $etiqueta;

        return $this;
    }

    /**
     * Get etiqueta
     *
     * @return \ERP\CRMBundle\Entity\CrmEtiqueta 
     */
    public function getEtiqueta()
    {
        return $this->etiqueta;
    }

    /**
     * Set cuenta
     *
     * @param \ERP\CRMBundle\Entity\CrmCuenta $cuenta
     * @return CrmEtiquetaCuenta
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
