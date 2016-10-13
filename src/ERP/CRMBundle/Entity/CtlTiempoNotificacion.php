<?php

namespace ERP\CRMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CtlTiempoNotificacion
 *
 * @ORM\Table(name="ctl_tiempo_notificacion")
 * @ORM\Entity
 */
class CtlTiempoNotificacion
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
     * @ORM\Column(name="nombre", type="string", length=100, nullable=false)
     */
    private $nombre;

    /**
     * @var integer
     *
     * @ORM\Column(name="duracion", type="integer", nullable=false)
     */
    private $duracion;

    /**
     * @var string
     *
     * @ORM\Column(name="unidad_tiempo", type="string", length=20, nullable=false)
     */
    private $unidadTiempo;


    /**
     * @var string
     *
     * @ORM\Column(name="estado", type="string", length=20, nullable=false)
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
     * @return CtlTiempoNotificacion
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
     * Set duracion
     *
     * @param integer $duracion
     * @return CtlTiempoNotificacion
     */
    public function setDuracion($duracion)
    {
        $this->duracion = $duracion;

        return $this;
    }

    /**
     * Get duracion
     *
     * @return integer 
     */
    public function getDuracion()
    {
        return $this->duracion;
    }

    /**
     * Set unidadTiempo
     *
     * @param string $unidadTiempo
     * @return CtlTiempoNotificacion
     */
    public function setUnidadTiempo($unidadTiempo)
    {
        $this->unidadTiempo = $unidadTiempo;

        return $this;
    }

    /**
     * Get unidadTiempo
     *
     * @return string 
     */
    public function getUnidadTiempo()
    {
        return $this->unidadTiempo;
    }


    /**
     * Set estado
     *
     * @param boolean $estado
     * @return CtlTiempoNotificacion
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
}
