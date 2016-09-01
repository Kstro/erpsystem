<?php

namespace ERP\CRMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CrmEtiquetaProyecto
 *
 * @ORM\Table(name="crm_etiqueta_proyecto", indexes={@ORM\Index(name="fk_crm_etiqueta_proyecto_crm_etiqueta1_idx", columns={"etiqueta"}), @ORM\Index(name="fk_crm_etiqueta_proyecto_crm_proyecto1_idx", columns={"proyecto"})})
 * @ORM\Entity
 */
class CrmEtiquetaProyecto
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
     * @var \CrmProyecto
     *
     * @ORM\ManyToOne(targetEntity="CrmProyecto")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="proyecto", referencedColumnName="id")
     * })
     */
    private $proyecto;



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
     * @return CrmEtiquetaProyecto
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
     * Set proyecto
     *
     * @param \ERP\CRMBundle\Entity\CrmProyecto $proyecto
     * @return CrmEtiquetaProyecto
     */
    public function setProyecto(\ERP\CRMBundle\Entity\CrmProyecto $proyecto = null)
    {
        $this->proyecto = $proyecto;

        return $this;
    }

    /**
     * Get proyecto
     *
     * @return \ERP\CRMBundle\Entity\CrmProyecto 
     */
    public function getProyecto()
    {
        return $this->proyecto;
    }
}
