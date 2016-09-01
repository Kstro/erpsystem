<?php

namespace ERP\CRMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FacDetalleFactura
 *
 * @ORM\Table(name="fac_detalle_factura", indexes={@ORM\Index(name="fk_fac_detalle_factuta_ctl_producto1_idx", columns={"producto"}), @ORM\Index(name="fk_fac_detalle_factuta_fac_factura1_idx", columns={"factura"})})
 * @ORM\Entity
 */
class FacDetalleFactura
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
     * @var float
     *
     * @ORM\Column(name="valor_unitario", type="float", precision=10, scale=0, nullable=false)
     */
    private $valorUnitario;

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
     * @var \FacFactura
     *
     * @ORM\ManyToOne(targetEntity="FacFactura")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="factura", referencedColumnName="id")
     * })
     */
    private $factura;



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
     * @return FacDetalleFactura
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
     * Set valorUnitario
     *
     * @param float $valorUnitario
     * @return FacDetalleFactura
     */
    public function setValorUnitario($valorUnitario)
    {
        $this->valorUnitario = $valorUnitario;

        return $this;
    }

    /**
     * Get valorUnitario
     *
     * @return float 
     */
    public function getValorUnitario()
    {
        return $this->valorUnitario;
    }

    /**
     * Set producto
     *
     * @param \ERP\CRMBundle\Entity\CtlProducto $producto
     * @return FacDetalleFactura
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

    /**
     * Set factura
     *
     * @param \ERP\CRMBundle\Entity\FacFactura $factura
     * @return FacDetalleFactura
     */
    public function setFactura(\ERP\CRMBundle\Entity\FacFactura $factura = null)
    {
        $this->factura = $factura;

        return $this;
    }

    /**
     * Get factura
     *
     * @return \ERP\CRMBundle\Entity\FacFactura 
     */
    public function getFactura()
    {
        return $this->factura;
    }
}
