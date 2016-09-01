<?php

namespace ERP\CRMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CrmEtiquetaClientePotencial
 *
 * @ORM\Table(name="crm_etiqueta_cliente_potencial", indexes={@ORM\Index(name="fk_crm_etiqueta_cliente_potencial_crm_etiqueta1_idx", columns={"etiqueta"}), @ORM\Index(name="fk_crm_etiqueta_cliente_potencial_crm_cliente_potencial1_idx", columns={"cliente_potencial"})})
 * @ORM\Entity
 */
class CrmEtiquetaClientePotencial
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
     * @var \CrmClientePotencial
     *
     * @ORM\ManyToOne(targetEntity="CrmClientePotencial")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="cliente_potencial", referencedColumnName="id")
     * })
     */
    private $clientePotencial;



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
     * @return CrmEtiquetaClientePotencial
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
     * Set clientePotencial
     *
     * @param \ERP\CRMBundle\Entity\CrmClientePotencial $clientePotencial
     * @return CrmEtiquetaClientePotencial
     */
    public function setClientePotencial(\ERP\CRMBundle\Entity\CrmClientePotencial $clientePotencial = null)
    {
        $this->clientePotencial = $clientePotencial;

        return $this;
    }

    /**
     * Get clientePotencial
     *
     * @return \ERP\CRMBundle\Entity\CrmClientePotencial 
     */
    public function getClientePotencial()
    {
        return $this->clientePotencial;
    }
}
