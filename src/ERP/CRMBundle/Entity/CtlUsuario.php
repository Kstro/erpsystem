<?php

namespace ERP\CRMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CtlUsuario
 *
 * @ORM\Table(name="ctl_usuario", indexes={@ORM\Index(name="fk_ctl_usuario_ctl_persona1_idx", columns={"persona"})})
 * @ORM\Entity
 */
class CtlUsuario
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
     * @ORM\Column(name="username", type="string", length=150, nullable=false)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=false)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=255, nullable=false)
     */
    private $salt;

    /**
     * @var boolean
     *
     * @ORM\Column(name="estado", type="boolean", nullable=false)
     */
    private $estado;

    /**
     * @var \CtlPersona
     *
     * @ORM\ManyToOne(targetEntity="CtlPersona")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="persona", referencedColumnName="id")
     * })
     */
    private $persona;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="CtlRol", inversedBy="usuario")
     * @ORM\JoinTable(name="ctl_rol_usuario",
     *   joinColumns={
     *     @ORM\JoinColumn(name="usuario", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="rol", referencedColumnName="id")
     *   }
     * )
     */
    private $rol;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->rol = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set username
     *
     * @param string $username
     * @return CtlUsuario
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return CtlUsuario
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return CtlUsuario
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set estado
     *
     * @param boolean $estado
     * @return CtlUsuario
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
     * Set persona
     *
     * @param \ERP\CRMBundle\Entity\CtlPersona $persona
     * @return CtlUsuario
     */
    public function setPersona(\ERP\CRMBundle\Entity\CtlPersona $persona = null)
    {
        $this->persona = $persona;

        return $this;
    }

    /**
     * Get persona
     *
     * @return \ERP\CRMBundle\Entity\CtlPersona 
     */
    public function getPersona()
    {
        return $this->persona;
    }

    /**
     * Add rol
     *
     * @param \ERP\CRMBundle\Entity\CtlRol $rol
     * @return CtlUsuario
     */
    public function addRol(\ERP\CRMBundle\Entity\CtlRol $rol)
    {
        $this->rol[] = $rol;

        return $this;
    }

    /**
     * Remove rol
     *
     * @param \ERP\CRMBundle\Entity\CtlRol $rol
     */
    public function removeRol(\ERP\CRMBundle\Entity\CtlRol $rol)
    {
        $this->rol->removeElement($rol);
    }

    /**
     * Get rol
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRol()
    {
        return $this->rol;
    }
}
