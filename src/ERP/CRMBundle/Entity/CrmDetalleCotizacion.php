<?php

namespace ERP\CRMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CrmDetalleCotizacion
 *
 * @ORM\Table(name="crm_detalle_cotizacion", indexes={@ORM\Index(name="fk_crm_detalle_cotizacion_crm_cotizacion1_idx", columns={"cotizacion"}), @ORM\Index(name="fk_crm_detalle_cotizacion_ctl_producto1_idx", columns={"producto"})})
 * @ORM\Entity
 */
class CrmDetalleCotizacion
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
     * @var string
     *
     * @ORM\Column(name="valor_unitario", type="decimal", precision=15, scale=2, nullable=false)
     */
    private $valorUnitario;
    
    /**
     * @var string
     *
     * @ORM\Column(name="tax", type="decimal", precision=15, scale=2, nullable=true)
     */
    private $tax;

    /**
     * @var \CrmCotizacion
     *
     * @ORM\ManyToOne(targetEntity="CrmCotizacion")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="cotizacion", referencedColumnName="id")
     * })
     */
    private $cotizacion;

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
     * @return CrmDetalleCotizacion
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
     * @param string $valorUnitario
     * @return CrmDetalleCotizacion
     */
    public function setValorUnitario($valorUnitario)
    {
        $this->valorUnitario = $valorUnitario;

        return $this;
    }

    /**
     * Get valorUnitario
     *
     * @return string 
     */
    public function getValorUnitario()
    {
        return $this->valorUnitario;
    }
    
    /**
     * Set tax
     *
     * @param string $tax
     * @return CrmDetalleCotizacion
     */
    public function setTax($tax)
    {
        $this->tax = $tax;

        return $this;
    }

    /**
     * Get tax
     *
     * @return string 
     */
    public function getTax()
    {
        return $this->tax;
    }

    /**
     * Set cotizacion
     *
     * @param \ERP\CRMBundle\Entity\CrmCotizacion $cotizacion
     * @return CrmDetalleCotizacion
     */
    public function setCotizacion(\ERP\CRMBundle\Entity\CrmCotizacion $cotizacion = null)
    {
        $this->cotizacion = $cotizacion;

        return $this;
    }

    /**
     * Get cotizacion
     *
     * @return \ERP\CRMBundle\Entity\CrmCotizacion 
     */
    public function getCotizacion()
    {
        return $this->cotizacion;
    }

    /**
     * Set producto
     *
     * @param \ERP\CRMBundle\Entity\CtlProducto $producto
     * @return CrmDetalleCotizacion
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
