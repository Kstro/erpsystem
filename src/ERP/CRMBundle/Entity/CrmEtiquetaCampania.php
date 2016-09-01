<?php

namespace ERP\CRMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CrmEtiquetaCampania
 *
 * @ORM\Table(name="crm_etiqueta_campania", indexes={@ORM\Index(name="fk_crm_etiqueta_campania_crm_etiqueta1_idx", columns={"etiqueta"}), @ORM\Index(name="fk_crm_etiqueta_campania_campania1_idx", columns={"campania"})})
 * @ORM\Entity
 */
class CrmEtiquetaCampania
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
     * @var \CrmCampania
     *
     * @ORM\ManyToOne(targetEntity="CrmCampania")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="campania", referencedColumnName="id")
     * })
     */
    private $campania;



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
     * @return CrmEtiquetaCampania
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
     * Set campania
     *
     * @param \ERP\CRMBundle\Entity\CrmCampania $campania
     * @return CrmEtiquetaCampania
     */
    public function setCampania(\ERP\CRMBundle\Entity\CrmCampania $campania = null)
    {
        $this->campania = $campania;

        return $this;
    }

    /**
     * Get campania
     *
     * @return \ERP\CRMBundle\Entity\CrmCampania 
     */
    public function getCampania()
    {
        return $this->campania;
    }
}
