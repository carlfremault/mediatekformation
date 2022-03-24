<?php

namespace App\Controller;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Client\Provider\KeycloakClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Contrôleur pour l'authentification Keycloak
 */
class OAuthController extends AbstractController
{
    /**
     * Route principale pour l'authentification, récupère et redirige vers le client Keycloak
     * @Route("/oauth/login", name="oauth_login")
     */
    public function index(ClientRegistry $clientRegistry): RedirectResponse
    {
        /** @var KeycloakClient $client */
        $client = $clientRegistry->getClient('keycloak');
        return $client->redirect();
    }
    
    /**
     * @route("/oauth/callback", name="oauth_check")
     */
    public function check()
    {        
    }
    
    /**
     * Route pour la déconnexion du back office. Fonction vide car le firewall s'occupe des fonctionnalités
     * @Route("/logout", name="logout")
     */
    public function logout()
    {        
    }
}
