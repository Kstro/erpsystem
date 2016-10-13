<?php

namespace ERP\CRMBundle\Entity;

use Symfony\Component\Security\Core\Role\RoleInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * CtlRol
 *
 * @ORM\Table(name="ctl_rol")
 * @ORM\Entity
 */
class CtlRol implements RoleInterface
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
     * @ORM\Column(name="nombre", type="string", length=75, nullable=false)
     */
    private $nombre;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="CtlUsuario", mappedBy="rol")
     */
    private $usuario;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->usuario = new \Doctrine\Common\Collections\ArrayCollection();
    }


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
     * @return CtlRol
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
    
    public function getRole() {
        return $this->getNombre();
    }
    
    public function __toString() {
        return $this->getRole();
    } 

    /**
     * Add usuario
     *
     * @param \ERP\CRMBundle\Entity\CtlUsuario $usuario
     * @return CtlRol
     */
    public function addUsuario(\ERP\CRMBundle\Entity\CtlUsuario $usuario)
    {
        $this->usuario[] = $usuario;

        return $this;
    }

    /**
     * Remove usuario
     *
     * @param \ERP\CRMBundle\Entity\CtlUsuario $usuario
     */
    public function removeUsuario(\ERP\CRMBundle\Entity\CtlUsuario $usuario)
    {
        $this->usuario->removeElement($usuario);
    }

    /**
     * Get usuario
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsuario()
    {
        return $this->usuario;
    }
}
