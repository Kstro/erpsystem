<?php

namespace ERP\CRMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CrmActividadCuenta
 *
 * @ORM\Table(name="crm_actividad_cuenta", indexes={@ORM\Index(name="fk_crm_actividad_cuenta_crm_actividad1_idx", columns={"actividad"}), @ORM\Index(name="fk_crm_actividad_cuenta_crm_cuenta1_idx", columns={"cuenta"})})
 * @ORM\Entity
 */
class CrmActividadCuenta
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
     * @var \CrmActividad
     *
     * @ORM\ManyToOne(targetEntity="CrmActividad")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="actividad", referencedColumnName="id")
     * })
     */
    private $actividad;

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
     * Set actividad
     *
     * @param \ERP\CRMBundle\Entity\CrmActividad $actividad
     * @return CrmActividadCuenta
     */
    public function setActividad(\ERP\CRMBundle\Entity\CrmActividad $actividad = null)
    {
        $this->actividad = $actividad;

        return $this;
    }

    /**
     * Get actividad
     *
     * @return \ERP\CRMBundle\Entity\CrmActividad 
     */
    public function getActividad()
    {
        return $this->actividad;
    }

    /**
     * Set cuenta
     *
     * @param \ERP\CRMBundle\Entity\CrmCuenta $cuenta
     * @return CrmActividadCuenta
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
