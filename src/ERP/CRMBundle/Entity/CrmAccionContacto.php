<?php

namespace ERP\CRMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CrmAccionContacto
 *
 * @ORM\Table(name="crm_accion_contacto", indexes={@ORM\Index(name="fk_crm_accion_cotizacion_accion_tabla1_idx", columns={"accion"})})
 * @ORM\Entity
 */
class CrmAccionContacto
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
     * @var \HistorialAcciones
     *
     * @ORM\ManyToOne(targetEntity="HistorialAcciones")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="accion", referencedColumnName="id")
     * })
     */
    private $accion;



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
     * Set accion
     *
     * @param \ERP\CRMBundle\Entity\HistorialAcciones $accion
     * @return CrmAccionContacto
     */
    public function setAccion(\ERP\CRMBundle\Entity\HistorialAcciones $accion = null)
    {
        $this->accion = $accion;

        return $this;
    }

    /**
     * Get accion
     *
     * @return \ERP\CRMBundle\Entity\HistorialAcciones 
     */
    public function getAccion()
    {
        return $this->accion;
    }
}
