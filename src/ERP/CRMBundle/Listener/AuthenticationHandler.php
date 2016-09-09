<?php

namespace ERP\CRMBundle\Listener;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Doctrine\ORM\EntityManager;

class AuthenticationHandler implements AuthenticationSuccessHandlerInterface, AuthenticationFailureHandlerInterface
{
    private $router;
    private $session; 
    private $em; 
    private $container;
    
    /**
     * Constructor
     *
     * @param 	RouterInterface $router
     * @param 	Session $session
     */
    public function __construct(Container $container, RouterInterface $router, Session $session, EntityManager $em)
    {
            $this->router  = $router;
            $this->session = $session;
            $this->em = $em;
            $this->container = $container;
    }
    
    /**
     * onAuthenticationFailure
     *
     * @param 	Request $request
     * @param 	AuthenticationException $exception
     * @return 	Response
     */
     public function onAuthenticationFailure( Request $request, AuthenticationException $exception )
    {
        // if AJAX login
        if ( $request->isXmlHttpRequest() ) {
            $parameters = $request->request->all();
            
            $username = $parameters['_username'];            
            $user = $this->em->getRepository('ERPCRMBundle:CtlUsuario')->findOneBy(array('username' => $username));    
            
            if(isset($parameters['g-recaptcha-response'])){
                $captcha = $parameters['g-recaptcha-response'];
                
                if(!$captcha && $user->getIntentos() > 2){
                    //No se ha seleccionado Captcha 
                    $intentos = $user->getIntentos();            
                    $array = array( 'success' => false, 'intentos' => $intentos, 'message' => 'Please check the captcha form.' );
                } else {
                    $user->setIntentos($user->getIntentos() + 1);   
                    $user->setLastAttempt(new \DateTime('now'));
                    $this->em->merge($user);
                    $this->em->flush();
                    
                    $intentos = $user->getIntentos();            
                    $array = array( 'success' => false, 'intentos' => $intentos, 'message' => $exception->getMessage() ); 
                }
            } else {
                $user->setIntentos($user->getIntentos() + 1);     
                $user->setLastAttempt(new \DateTime('now'));
                $this->em->merge($user);
                $this->em->flush();

                $intentos = $user->getIntentos();            
                $array = array( 'success' => false, 'intentos' => $intentos, 'message' => $exception->getMessage() ); 
            }
            
            $response = new Response( json_encode( $array ) );
            $response->headers->set( 'Content-Type', 'application/json' );

            return $response;        
        } // if form login 
        else {
            // set authentication exception to session
            $request->getSession()->set(SecurityContextInterface::AUTHENTICATION_ERROR, $exception);

            return new RedirectResponse( $this->router->generate( 'erpdg_login' ) );
        }                    
    }
    
    /**
     * onAuthenticationSuccess
     *
     * @param 	Request $request
     * @param 	TokenInterface $token
     * @return 	Response
     */
    public function onAuthenticationSuccess( Request $request, TokenInterface $token )
    {   
        if ( $request->isXmlHttpRequest() ) { 
            $parameters = $request->request->all();
            $att = $parameters['attempts'];
            $username = $parameters['_username'];
            $user = $this->em->getRepository('ERPCRMBundle:CtlUsuario')->findOneBy(array('username' => $username));
            
            if(isset($parameters['g-recaptcha-response'])){
                $captcha = $parameters['g-recaptcha-response'];
                    
                if(!$captcha){
                    if($user->getIntentos() > 2){
                        //No se ha seleccionado Captcha 
                        $lastAttempt = $user->getLastAttempt();
                        
                        $datetime = new \DateTime('now');
                        $interval = $lastAttempt->diff($datetime);
                        $hour = $interval->format('%h');
                        $min = $interval->format('%i');
                        $seg = $interval->format('%s');
                        
                        if((int)$hour < 2 || $att > 0){
                                $this->container->get('security.context')->setToken(null);

                                $intentos = $user->getIntentos();            
                                $array = array( 'success' => false, 'intentos' => $intentos, 'message' => 'Please check the captcha form.' );                                                               
                        } else {
                            $user->setIntentos(0);
                        
                            $this->em->merge($user);
                            $this->em->flush();

                            $array = array( 'success' => true );  
                        }                                                                                                
                    } else {
                        //No necesita captcha
                        $user->setIntentos(0);
                        
                        $this->em->merge($user);
                        $this->em->flush();

                        $array = array( 'success' => true ); 
                    }                   
                } else {
                    $response=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6Lc1QyQTAAAAAFKp2M-pijzAh-IxATFqKXsACd_G&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']);
                    $respuesta = json_decode($response);
                    
                    if($respuesta->success==false){
                        //Captcha no valido
                        $this->container->get('security.context')->setToken(null);
                        
                        $intentos = $user->getIntentos();            
                        $array = array( 'success' => false, 'intentos' => $intentos, 'message' => 'Invalid captcha.' );
                    } else {
                        //Captcha valido
                        $user->setIntentos(0);
                        
                        $this->em->merge($user);
                        $this->em->flush();

                        $array = array( 'success' => true ); 
                    }
                }
            } else {
                //Login con 0 intentos fallidos
                $user->setIntentos(0);
                        
                $this->em->merge($user);
                $this->em->flush();

                $array = array( 'success' => true ); 
            }            
                            
            $response = new Response( json_encode( $array ) );
            $response->headers->set( 'Content-Type', 'application/json' );

            return $response;        
        } // if form login   
        else { 
            if ( $this->session->get('_security.main.target_path' ) ) {
                    $url = $this->session->get( '_security.main.target_path' );
            } else {
                    $url = $this->router->generate( 'admin_dashboard' );
            } // end if

            return new RedirectResponse( $url );
        }
    } 
}

