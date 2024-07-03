<?php

namespace App\Security;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\RouterInterface;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class OAuthAuthenticator extends AbstractGuardAuthenticator
{
    private $router;
    private $session;
    private $clientRegistry;

    public function __construct(RouterInterface $router, SessionInterface $session, ClientRegistry $clientRegistry)
    {
        $this->router = $router;
        $this->session = $session;
        $this->clientRegistry = $clientRegistry;
    }

    public function supports(Request $request)
    {
        // Détermine si ce demandeur doit être utilisé pour cette demande
        // Vous pouvez ajouter des vérifications ici
        return true;
    }

    public function getCredentials(Request $request)
    {
        // Récupère les informations d'identification de la demande
        return $this->session->get('oauth_access_token');
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        // Récupère l'utilisateur à partir des informations d'identification
	$client = $this->clientRegistry->getClient('my_oauth2_client');
        if(null != ($this->session->get('oauth_access_token')))
        {
                $accessToken = $this->session->get('oauth_access_token');
                try
                {
                        //echo "access_token : ";
                        //var_dump($accessToken);
                        $user = $client->fetchUserFromToken($accessToken);
                        //echo "<br />";
                        //echo "<br />user : ";
                        //var_dump($user);
                        //echo "<br />email : ";
                        //echo $user->toArray()['email'];
                        //echo "<br />";
        		return $userProvider->loadUserByUsername($user->toArray()['email']);
                }
                catch (\Exception $e)
                {       
                        //$this->session->remove('oauth_access_token');
                        //return $this->redirectToRoute('home');
                }
        }
        return $userProvider->loadUserByUsername('oauth');
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        // Vérifie les informations d'identification
        return true;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {

        $client = $this->clientRegistry->getClient('my_oauth2_client');
        if(isset($_GET['code']) && isset($_GET['session_state']))
        {       
                try {   
                        // the exact class depends on which provider you're using
                        $redirectUri = 'https://catalogue-dev.bpi.fr';
                        $accessToken = $client->getAccessToken(['redirect_uri' => $redirectUri]);
                        $this->session->set('oauth_access_token', $accessToken);
                        
                        // do something with all this new power!
                        // e.g. $name = $user->getFirstName();
                } catch (IdentityProviderException $e) {
                        // something went wrong!
                        // probably you should return the reason to the user
                        var_dump($e->getMessage()); die;
                }
                return null;
        }



        // Action à effectuer en cas de succès d'authentification
        return null; // Laissez Symfony continuer avec la demande
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        // Action à effectuer en cas d'échec d'authentification
        //return new Response('Authentication Failed', Response::HTTP_FORBIDDEN);
	$this->session->remove('oauth_access_token');
	return null;
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        // Action à effectuer pour lancer l'authentification
        return new RedirectResponse($this->router->generate('oauth_connect'));
    }

    public function supportsRememberMe()
    {
        return false;
    }
}

