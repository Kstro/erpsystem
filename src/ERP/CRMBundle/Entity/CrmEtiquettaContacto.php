<?php

namespace ERP\CRMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CrmEtiquettaContacto
 *
 * @ORM\Table(name="crm_etiquetta_contacto", indexes={@ORM\Index(name="fk_crm_etiquetta_contacto_crm_etiqueta1_idx", columns={"etiqueta"}), @ORM\Index(name="fk_crm_etiquetta_contacto_crm_contacto1_idx", columns={"contacto"})})
 * @ORM\Entity
 */
class CrmEtiquettaContacto
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
     * @var \CrmContacto
     *
     * @ORM\ManyToOne(targetEntity="CrmContacto")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="contacto", referencedColumnName="id")
     * })
     */
    private $contacto;



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
     * @return CrmEtiquettaContacto
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
     * Set contacto
     *
     * @param \ERP\CRMBundle\Entity\CrmContacto $contacto
     * @return CrmEtiquettaContacto
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
}
