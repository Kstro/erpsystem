<?php

namespace ERP\CRMBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ERP\CRMBundle\Entity\CtlEmpresa;
use ERP\CRMBundle\Entity\CtlTelefono;
use ERP\CRMBundle\Entity\CtlCorreo;
use ERP\CRMBundle\Entity\CtlDireccion;
use ERP\CRMBundle\Entity\CtlPersona;
use ERP\CRMBundle\Entity\CtlUsuario;

/**
 * Wizard controller.
 *
 * @Route("/wizard")
 */
class WizardController extends Controller
{
    /**
     * Al momento de dar click en iniciar configuracion inicial
     *
     * @Route("/start", name="admin_wizard_start_configuration", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function startConfigurationAction(Request $request)
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){
            try {
                
            } catch (Exception $e) {

            }
        } else {    
            return new Response('0');              
        }  
    }
    
    /**
     * Registro de la informacion general de la empresa (Paso 1)
     *
     * @Route("/register/step1", name="admin_wizard_register_step1", options={"expose"=true})
     * @Method("POST")
     */
    public function registerStep1Action(Request $request)
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){
            try {
                $em = $this->getDoctrine()->getManager();
                $parameters = $request->request->all();
                
                //var_dump($parameters);
                //die();
                
                // Obteniendo los datos ingresados sobre la empresa
                $idEmpresa = $parameters['idEmpresa'];
                $companyName = $parameters['companyName'];
                $industriaId = $parameters['industria'];
                
                //Correos
                $emailArray = $parameters['emailCia'];

                //Telefonos
                $phoneTypeArray = $parameters['phoneTypeCia'];
                $phoneArray = $parameters['phoneCia'];
                $phoneExtArray = $parameters['phoneExtCia'];

                //Dirección
                $addressArray = $parameters['addressCia'];
                $addressCityArray = $parameters['addressCityCia'];
                $addressDepartamentoArray = $parameters['addressDepartamentoCia'];
                
                // Si no hay registro de la empresa
                if ($idEmpresa == '') {
                    $industria = $em->getRepository('ERPCRMBundle:CtlIndustria')->find($industriaId);
                    
                    // Registrando informacion gnal. de la empresa
                    $empresa = new CtlEmpresa();
                    $empresa->setNombre($companyName);
                    $empresa->setIndustria($industria);
                    $empresa->setFechaRegistro(new \DateTime('now'));
                    
                    // Se establece con el valor "1" indicando que se ha realizado
                    // el paso 1 de la configuracion inicial del sistema
                    $empresa->setEstado(1);
                    
                    // Se establece el valor como falso indicando que aun no
                    // se ha terminado la configuracion inicial del sistema
                    $empresa->setWizard(FALSE);
                    
                    $em->persist($empresa);
                    $em->flush();
                    
                    // Registrando los correos electronicos vinculados a la empresa
                    foreach ($emailArray as $key => $correo) {
                        $ctlCorreo = new CtlCorreo();
                        $ctlCorreo->setEmpresa($empresa);
                        $ctlCorreo->setPersona(null);
                        $ctlCorreo->setCuenta(null);
                        $ctlCorreo->setEmail($correo);
                        $ctlCorreo->setEstado(1);
                        $em->persist($ctlCorreo);
                        $em->flush();
                    }
                    
                    
                    $phoneLenght=count($phoneArray)-1; // Cantidad de telefono ingresados, menos 1 para index de array
                    $ctlTipoTelefonoObj = $em->getRepository('ERPCRMBundle:CtlTipoTelefono')->find($phoneTypeArray[0]); // Para definir la variable $ctlTipoTelefonoObj
                    
                    // Registrando los telefonos vinculados a la empresa
                    foreach ($phoneArray as $key => $phone) {
                                          
                        $ctlTelefono = new CtlTelefono();
                        $ctlTelefono->setEmpresa($empresa);
                        if ($key<$phoneLenght && $key!=0) {
                            if ($phoneTypeArray[$key]==$phoneTypeArray[$key-1]) {
                                //No buscar en la base el tipo de telefono
                                $ctlTelefono->setTipoTelefono($ctlTipoTelefonoObj);
                            } else {
                                //Buscar en la base el tipo de telefono
                                $ctlTipoTelefonoObj = $em->getRepository('ERPCRMBundle:CtlTipoTelefono')->find($phoneTypeArray[$key]);
                                $ctlTelefono->setTipoTelefono($ctlTipoTelefonoObj);
                            }
                         
                        } else {
                                //Buscar en la base el tipo de telefono, primera iteracion debe buscar el tipo de telefono
                                $ctlTelefono->setTipoTelefono($ctlTipoTelefonoObj);
                        }
                        $ctlTelefono->setNumTelefonico($phoneArray[$key]);
                        $ctlTelefono->setExtension($phoneExtArray[$key]);
                        $ctlTelefono->setPersona(null);
                        $ctlTelefono->setCuenta(null);
                        $ctlTelefono->setSucursal(null);
                        $em->persist($ctlTelefono);
                        $em->flush();
                    }
                    
                    $addressLenght=count($addressArray)-1; // Cantidad de direccion ingresados, menos 1 para index de array
                    
                    // Registrando las direcciones vinculadas a la empresa
                    foreach ($addressArray as $key => $val) {
                                          
                        $ctlDireccion = new CtlDireccion();
                        $ctlDireccion->setEmpresa($empresa);
                        if ($key<$addressLenght && $key!=0) {
                            if ($addressCityArray[$key]==$addressCityArray[$key-1]) {
                                //No buscar en la base ciudad
                                $ctlDireccion->setCiudad($ctlCiudadObj);
                            } else {
                                //Buscar en la base la ciudad
                                $ctlCiudadObj = $em->getRepository('ERPCRMBundle:CtlCiudad')->find($addressCityArray[$key]);
                                $ctlDireccion->setCiudad($ctlCiudadObj);
                            }
                         
                        } else {
                                //Buscar en la base la ciudad, primera iteracion debe buscar ciudad
                                $ctlCiudadObj = $em->getRepository('ERPCRMBundle:CtlCiudad')->find($addressCityArray[$key]);
                                $ctlDireccion->setCiudad($ctlCiudadObj);
                        }
                        
                        $ctlDireccion->setDireccion($addressArray[$key]);
                        $ctlDireccion->setPersona(null);
                        $ctlDireccion->setCuenta(null);
                        $ctlDireccion->setLatitud(0);
                        $ctlDireccion->setLongitud(0);
                        $ctlDireccion->setEstado(1);
                        $em->persist($ctlDireccion);
                        $em->flush();                        
                    }
                    
                    
                    
                    //var_dump($empresa);
                    //die();
                    
                    //$serverRegister = $this->getParameter('app.serverMsgUpdate');
                    //$data['msg']=$serverRegister; 
                    $data['id']=$empresa->getId();
                    
                // Si se edita la informacion de la empresa
                } else {
                    
                }
                
                //var_dump($parameters);
                //die();
                
                $response = new JsonResponse();
                $response->setData(array(
                                  'msg'   => $data
                               ));  
            
                return $response; 
            } catch (Exception $e) {
                if(method_exists($e,'getErrorCode')){
                    switch (intval($e->getErrorCode())){
                        case 2003: 
                            $data['error'] = $this->getParameter('app.serverOffline');
                        break;
                        case 1062: 
                            $data['error'] = $this->getParameter('app.serverDuplicateName');
                        break;
                        default :
                            $data['error'] = $e->getMessage();                     
                        break;
                        }      
                }
                else{
                    $data['error']=$e->getMessage();
                }
                    
                $response = new JsonResponse();
                $response->setData(array(
                                  'msg'   => $data
                               ));  
            }
        } else {    
            return new Response('0');              
        }
    }
    
    /**
     * Registro de la informacion general de la empresa (Paso 2)
     *
     * @Route("/register/step2", name="admin_wizard_register_step2", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function registerStep2Action(Request $request)
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){
            try {
                $em = $this->getDoctrine()->getManager();
                
                $parameters = $request->request->all();
                
                $response = new JsonResponse();
                $response->setData(array(
                                  'msg'   => $data
                               ));  
            
                return $response; 
            } catch (Exception $e) {
                if(method_exists($e,'getErrorCode')){
                    switch (intval($e->getErrorCode())){
                        case 2003: 
                            $data['error'] = $this->getParameter('app.serverOffline');
                        break;
                        case 1062: 
                            $data['error'] = $this->getParameter('app.serverDuplicateName');
                        break;
                        default :
                            $data['error'] = $e->getMessage();                     
                        break;
                        }      
                }
                else{
                    $data['error']=$e->getMessage();
                }
                    
                $response = new JsonResponse();
                $response->setData(array(
                                  'msg'   => $data
                               ));  
            }
        } else {    
            return new Response('0');              
        }
    }
    
    /**
     * Registro de la informacion general de la empresa (Paso 3)
     *
     * @Route("/register/step3", name="admin_wizard_register_step3", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function registerStep3Action(Request $request)
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){
            try {
                $em = $this->getDoctrine()->getManager();
                $parameters = $request->request->all();
                
                $response = new JsonResponse();
                $response->setData(array(
                                  'msg'   => $data
                               ));  
            
                return $response; 
            } catch (Exception $e) {
                if(method_exists($e,'getErrorCode')){
                    switch (intval($e->getErrorCode())){
                        case 2003: 
                            $data['error'] = $this->getParameter('app.serverOffline');
                        break;
                        case 1062: 
                            $data['error'] = $this->getParameter('app.serverDuplicateName');
                        break;
                        default :
                            $data['error'] = $e->getMessage();                     
                        break;
                        }      
                }
                else{
                    $data['error']=$e->getMessage();
                }
                    
                $response = new JsonResponse();
                $response->setData(array(
                                  'msg'   => $data
                               ));  
            }
        } else {    
            return new Response('0');              
        }
    }
    
    /**
     * Acciones luego de finalizar la configuracion inicial del sistema
     *
     * @Route("/completed", name="admin_wizard_completed", options={"expose"=true})
     * @Method("POST")
     */
    public function completedAction(Request $request)
    {
        
    }
}
