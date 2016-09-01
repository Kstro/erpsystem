<?php

namespace ERP\CRMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CtlCiudad
 *
 * @ORM\Table(name="ctl_ciudad", indexes={@ORM\Index(name="fk_ctl_ciudad_ctl_estado1_idx", columns={"estado"})})
 * @ORM\Entity
 */
class CtlCiudad
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
     * @ORM\Column(name="nombre", type="string", length=45, nullable=false)
     */
    private $nombre;

    /**
     * @var \CtlEstado
     *
     * @ORM\ManyToOne(targetEntity="CtlEstado")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="estado", referencedColumnName="id")
     * })
     */
    private $estado;



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
     * @return CtlCiudad
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
     * Set estado
     *
     * @param \ERP\CRMBundle\Entity\CtlEstado $estado
     * @return CtlCiudad
     */
    public function setEstado(\ERP\CRMBundle\Entity\CtlEstado $estado = null)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return \ERP\CRMBundle\Entity\CtlEstado 
     */
    public function getEstado()
    {
        return $this->estado;
    }
}
