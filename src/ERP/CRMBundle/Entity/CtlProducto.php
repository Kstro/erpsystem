<?php

namespace ERP\CRMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CtlProducto
 *
 * @ORM\Table(name="ctl_producto", indexes={@ORM\Index(name="fk_ctl_producto_ctl_categoria_producto1_idx", columns={"categoria_producto"}), @ORM\Index(name="tipo_producto", columns={"tipo_producto"})})
 * @ORM\Entity
 */
class CtlProducto
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
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=150, nullable=false)
     */
    private $nombre;
    
    /**
     * @var string
     *
     * @ORM\Column(name="codigo", type="string", length=100, nullable=true)
     */
    private $codigo;
    
    /**
     * @var string
     *
     * @ORM\Column(name="costo", type="decimal", precision=15, scale=2, nullable=true)
     */
    private $costo;
    
    /**
     * @var string
     *
     * @ORM\Column(name="precio", type="decimal", precision=15, scale=2, nullable=true)
     */
    private $precio;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_registro", type="datetime", nullable=false)
     */
    private $fechaRegistro;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="text", length=65535, nullable=true)
     */
    private $descripcion;

    /**
     * @var boolean
     *
     * @ORM\Column(name="estado", type="boolean", nullable=false)
     */
    private $estado;
   
    /**
     * @var \CtlCategoriaProducto
     *
     * @ORM\ManyToOne(targetEntity="CtlCategoriaProducto")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="categoria_producto", referencedColumnName="id")
     * })
     */
    private $categoriaProducto;
    
    /**
     * @var \CtlTipoProducto
     *
     * @ORM\ManyToOne(targetEntity="CtlTipoProducto")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="tipo_producto", referencedColumnName="id")
     * })
     */
    private $tipoProducto;

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
     * Set nombre
     *
     * @param string $nombre
     * @return CtlProducto
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }
        
    /**
     * Set codigo
     *
     * @param string $codigo
     * @return CtlProducto
     */
    public function setCodigo($codigo) {
        $this->codigo = $codigo;

        return $this;
    }

    /**
     * Get codigo
     *
     * @return string 
     */
    public function getCodigo() {
        return $this->codigo;
    }
    
    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return CtlProducto
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string 
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }
    
    /**
     * Set costo
     *
     * @param string $costo
     * @return CtlProducto
     */
    public function setCosto($costo) {
        $this->costo = $costo;

        return $this;
    }

    /**
     * Get costo
     *
     * @return string 
     */
    public function getCosto() {
        return $this->costo;
    }
    
    /**
     * Set precio
     *
     * @param string $precio
     * @return CtlProducto
     */
    public function setPrecio($precio) {
        $this->precio = $precio;

        return $this;
    }

    /**
     * Get precio
     *
     * @return string 
     */
    public function getPrecio() {
        return $this->precio;
    }
        
    /**
     * Set fechaRegistro
     *
     * @param string $fechaRegistro
     * @return CtlProducto
     */
    public function setFechaRegistro($fechaRegistro) {
        $this->fechaRegistro = $fechaRegistro;

        return $this;
    }

    /**
     * Get fechaRegistro
     *
     * @return string 
     */
    public function getFechaRegistro() {
        return $this->fechaRegistro;
    }
            
    /**
     * Set estado
     *
     * @param boolean $estado
     * @return CtlProducto
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return boolean 
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set categoriaProducto
     *
     * @param \ERP\CRMBundle\Entity\CtlCategoriaProducto $categoriaProducto
     * @return CtlProducto
     */
    public function setCategoriaProducto(\ERP\CRMBundle\Entity\CtlCategoriaProducto $categoriaProducto = null)
    {
        $this->categoriaProducto = $categoriaProducto;

        return $this;
    }

    /**
     * Get categoriaProducto
     *
     * @return \ERP\CRMBundle\Entity\CtlCategoriaProducto 
     */
    public function getCategoriaProducto()
    {
        return $this->categoriaProducto;
    }
        
    /**
     * Set tipoProducto
     *
     * @param \ERP\CRMBundle\Entity\CtlTipoProducto $tipoProducto
     * @return CtlProducto
     */
    public function setTipoProducto(\ERP\CRMBundle\Entity\CtlTipoProducto $tipoProducto = null) {
        $this->tipoProducto = $tipoProducto;

        return $this;
    }

    /**
     * Get tipoProducto
     *
     * @return \ERP\CRMBundle\Entity\CtlTipoProducto 
     */
    public function getTipoProducto() {
        return $this->tipoProducto;
    }        
}
