<?php

namespace ERP\CRMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CtlProducto
 *
 * @ORM\Table(name="ctl_producto", indexes={@ORM\Index(name="fk_ctl_producto_ctl_categoria_producto1_idx", columns={"categoria_producto"})})
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
}
