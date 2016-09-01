<?php

namespace ERP\CRMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CrmProductoOportunidad
 *
 * @ORM\Table(name="crm_producto_oportunidad", indexes={@ORM\Index(name="fk_crm_oportunidad_has_ctl_producto_ctl_producto1_idx", columns={"producto"}), @ORM\Index(name="fk_crm_oportunidad_has_ctl_producto_crm_oportunidad1_idx", columns={"oportunidad"})})
 * @ORM\Entity
 */
class CrmProductoOportunidad
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
     * @var integer
     *
     * @ORM\Column(name="cantidad", type="integer", nullable=false)
     */
    private $cantidad;

    /**
     * @var \CrmOportunidad
     *
     * @ORM\ManyToOne(targetEntity="CrmOportunidad")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="oportunidad", referencedColumnName="id")
     * })
     */
    private $oportunidad;

    /**
     * @var \CtlProducto
     *
     * @ORM\ManyToOne(targetEntity="CtlProducto")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="producto", referencedColumnName="id")
     * })
     */
    private $producto;



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
     * Set cantidad
     *
     * @param integer $cantidad
     * @return CrmProductoOportunidad
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    /**
     * Get cantidad
     *
     * @return integer 
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Set oportunidad
     *
     * @param \ERP\CRMBundle\Entity\CrmOportunidad $oportunidad
     * @return CrmProductoOportunidad
     */
    public function setOportunidad(\ERP\CRMBundle\Entity\CrmOportunidad $oportunidad = null)
    {
        $this->oportunidad = $oportunidad;

        return $this;
    }

    /**
     * Get oportunidad
     *
     * @return \ERP\CRMBundle\Entity\CrmOportunidad 
     */
    public function getOportunidad()
    {
        return $this->oportunidad;
    }

    /**
     * Set producto
     *
     * @param \ERP\CRMBundle\Entity\CtlProducto $producto
     * @return CrmProductoOportunidad
     */
    public function setProducto(\ERP\CRMBundle\Entity\CtlProducto $producto = null)
    {
        $this->producto = $producto;

        return $this;
    }

    /**
     * Get producto
     *
     * @return \ERP\CRMBundle\Entity\CtlProducto 
     */
    public function getProducto()
    {
        return $this->producto;
    }
}
