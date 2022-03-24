<?php

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * Fonctionnalités pour le processus d'authentification Keycloak
 *
 * @author carlf
 */
class KeycloakAuthenticator  extends SocialAuthenticator{
    
    /**
     * Client Registry
     * @var type 
     */
    private $clientRegistry;
    
    /**
     * Entity Manager
     * @var type 
     */
    private $em;
    
    /**
     * Router
     * @var type 
     */
    private $router;
    
    /**
     * Constructeur. Valorise ClientRegistry, EntityManagerInterface, RouterInterface
     * @param ClientRegistry $clientRegistry
     * @param EntityManagerInterface $em
     * @param RouterInterface $router
     */
    public function __construct(ClientRegistry $clientRegistry, EntityManagerInterface $em, RouterInterface $router) {
        $this->clientRegistry = $clientRegistry;
        $this->em = $em;
        $this->router = $router;
    }
    
    /**
     * Getter pour le client Keycloak
     * @return client Keycloak
     */
    private function getKeycloakClient() {
        return $this->clientRegistry->getClient('keycloak');
    }
    
    /**
     * Fonction qui récupère les informations de l'utilisateur
     * @param Request $request
     * @return Access token
     */
    public function getCredentials(Request $request) {
        return $this->fetchAccessToken($this->getKeycloakClient());
    }

    /**
     * Récupération des informations de l'utilisateur dans Keycloak
     * Si l'utilisateur existe dans la base de données il est récupéré, sinon il est ajouté
     * @param type $credentials les informations de l'utilisateur
     * @param UserProviderInterface $userProvider
     */
    public function getUser($credentials, UserProviderInterface $userProvider) {
        $keycloakUser = $this->getKeycloakClient()->fetchUserFromToken($credentials);
        
        // Le user existe et s'est déjà connecté avec Keycloak
        $existingUser = $this
                            ->em
                            ->getRepository(User::class)
                            ->findOneBy(['keycloakId' => $keycloakUser->getId()]);
        if($existingUser) {
            return $existingUser;
        }
        
        // Le user existe mais ne s'est pas encore connecté avec Keycloak
        $email = $keycloakUser->getEmail();
        $userInDatabase = $this->em->getRepository(User::class)->findOneBy(['email' => $email]);
        if($userInDatabase) {
            $userInDatabase->setKeycloakId($keycloakUser->getId());
            $this->em->persist($userInDatabase);
            $this->em->flush();
            return $userInDatabase;
        }
        
        // Le user n'existe pas encore dans la bdd, il est ajouté
        $user = new User();
        $user->setKeycloakId($keycloakUser->getId());
        $user->setEmail($keycloakUser->getEmail());
        $user->setPassword("");
        $user->setRoles(['ROLE_ADMIN']);
        $this->em->persist($user);
        $this->em->flush();
        return $user;
    }

    /**
     * Cette fonction retourne une exception en cas de problème dans une des autres méthodes de la classe
     * @param Request $request
     * @param AuthenticationException $exception
     * @return Response
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception) {
        $message = strtr($exception->getMessageKey(), $exception->getMessageData());
        return new Response($message, Response::HTTP_FORBIDDEN);
    }

    /**
     * Redirection vers la route initiale en cas de succès d'authentification
     * @param Request $request
     * @param TokenInterface $token
     * @param type $providerKey
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey) {
        $targetUrl = $this->router->generate('admin.formations');
        return new RedirectResponse($targetUrl);
    }

    /**
     * Redirection vers route temporaire spécifié dans le contrôleur
     * @param Request $request
     * @param AuthenticationException $authException
     * @return Response
     */
    public function start(Request $request, AuthenticationException $authException = null): Response {
        return new RedirectResponse(
                '/oauth/login',
                Response::HTTP_TEMPORARY_REDIRECT);
    }

    /**
     * Vérifie si l'authentification doit être faite pour l'url donnée
     * @param Request $request
     * @return bool, true si 
     */
    public function supports(Request $request): bool {
        return $request->attributes->get('_route') === 'oauth_check';
    }

}
