<?php

namespace ERP\CRMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CtlCorreo
 *
 * @ORM\Table(name="ctl_correo", indexes={@ORM\Index(name="fk_ctl_correo_ctl_empresa1_idx", columns={"empresa"}), @ORM\Index(name="fk_ctl_correo_ctl_persona1_idx", columns={"persona"}), @ORM\Index(name="fk_ctl_correo_crm_cuenta1_idx", columns={"cuenta"})})
 * @ORM\Entity
 */
class CtlCorreo
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
     * @ORM\Column(name="email", type="string", length=150, nullable=false)
     */
    private $email;

    /**
     * @var integer
     *
     * @ORM\Column(name="estado", type="integer", nullable=false)
     */
    private $estado;

    /**
     * @var \CtlEmpresa
     *
     * @ORM\ManyToOne(targetEntity="CtlEmpresa")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="empresa", referencedColumnName="id")
     * })
     */
    private $empresa;

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
     * Set email
     *
     * @param string $email
     * @return CtlCorreo
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set estado
     *
     * @param integer $estado
     * @return CtlCorreo
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return integer 
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set empresa
     *
     * @param \ERP\CRMBundle\Entity\CtlEmpresa $empresa
     * @return CtlCorreo
     */
    public function setEmpresa(\ERP\CRMBundle\Entity\CtlEmpresa $empresa = null)
    {
        $this->empresa = $empresa;

        return $this;
    }

    /**
     * Get empresa
     *
     * @return \ERP\CRMBundle\Entity\CtlEmpresa 
     */
    public function getEmpresa()
    {
        return $this->empresa;
    }

    /**
     * Set persona
     *
     * @param \ERP\CRMBundle\Entity\CtlPersona $persona
     * @return CtlCorreo
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
     * Set cuenta
     *
     * @param \ERP\CRMBundle\Entity\CrmCuenta $cuenta
     * @return CtlCorreo
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
