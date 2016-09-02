<?php

namespace ERP\CRMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CrmClientePotencial
 *
 * @ORM\Table(name="crm_cliente_potencial", indexes={@ORM\Index(name="fk_crm_cliente_potencial_ctl_nivel_interes1_idx", columns={"nivel_interes"}), @ORM\Index(name="fk_crm_cliente_potencial_ctl_fuente1_idx", columns={"fuente_principal"}), @ORM\Index(name="fk_crm_cliente_potencial_campania1_idx", columns={"campania"}), @ORM\Index(name="fk_crm_cliente_potencial_crm_estado_cliente_potencial1_idx", columns={"estado_cliente_potencial"}), @ORM\Index(name="fk_crm_cliente_potencial_ctl_persona1_idx", columns={"persona"})})
 * @ORM\Entity
 */
class CrmClientePotencial
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
     * @var \CtlNivelInteres
     *
     * @ORM\ManyToOne(targetEntity="CtlNivelInteres")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="nivel_interes", referencedColumnName="id")
     * })
     */
    private $nivelInteres;

    /**
     * @var \CtlFuente
     *
     * @ORM\ManyToOne(targetEntity="CtlFuente")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="fuente_principal", referencedColumnName="id")
     * })
     */
    private $fuentePrincipal;

    /**
     * @var \CrmCampania
     *
     * @ORM\ManyToOne(targetEntity="CrmCampania")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="campania", referencedColumnName="id")
     * })
     */
    private $campania;

    /**
     * @var \CrmEstadoClientePotencial
     *
     * @ORM\ManyToOne(targetEntity="CrmEstadoClientePotencial")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="estado_cliente_potencial", referencedColumnName="id")
     * })
     */
    private $estadoClientePotencial;

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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nivelInteres
     *
     * @param \ERP\CRMBundle\Entity\CtlNivelInteres $nivelInteres
     * @return CrmClientePotencial
     */
    public function setNivelInteres(\ERP\CRMBundle\Entity\CtlNivelInteres $nivelInteres = null)
    {
        $this->nivelInteres = $nivelInteres;

        return $this;
    }

    /**
     * Get nivelInteres
     *
     * @return \ERP\CRMBundle\Entity\CtlNivelInteres 
     */
    public function getNivelInteres()
    {
        return $this->nivelInteres;
    }

    /**
     * Set fuentePrincipal
     *
     * @param \ERP\CRMBundle\Entity\CtlFuente $fuentePrincipal
     * @return CrmClientePotencial
     */
    public function setFuentePrincipal(\ERP\CRMBundle\Entity\CtlFuente $fuentePrincipal = null)
    {
        $this->fuentePrincipal = $fuentePrincipal;

        return $this;
    }

    /**
     * Get fuentePrincipal
     *
     * @return \ERP\CRMBundle\Entity\CtlFuente 
     */
    public function getFuentePrincipal()
    {
        return $this->fuentePrincipal;
    }

    /**
     * Set campania
     *
     * @param \ERP\CRMBundle\Entity\CrmCampania $campania
     * @return CrmClientePotencial
     */
    public function setCampania(\ERP\CRMBundle\Entity\CrmCampania $campania = null)
    {
        $this->campania = $campania;

        return $this;
    }

    /**
     * Get campania
     *
     * @return \ERP\CRMBundle\Entity\CrmCampania 
     */
    public function getCampania()
    {
        return $this->campania;
    }

    /**
     * Set estadoClientePotencial
     *
     * @param \ERP\CRMBundle\Entity\CrmEstadoClientePotencial $estadoClientePotencial
     * @return CrmClientePotencial
     */
    public function setEstadoClientePotencial(\ERP\CRMBundle\Entity\CrmEstadoClientePotencial $estadoClientePotencial = null)
    {
        $this->estadoClientePotencial = $estadoClientePotencial;

        return $this;
    }

    /**
     * Get estadoClientePotencial
     *
     * @return \ERP\CRMBundle\Entity\CrmEstadoClientePotencial 
     */
    public function getEstadoClientePotencial()
    {
        return $this->estadoClientePotencial;
    }

    /**
     * Set persona
     *
     * @param \ERP\CRMBundle\Entity\CtlPersona $persona
     * @return CrmClientePotencial
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
}
