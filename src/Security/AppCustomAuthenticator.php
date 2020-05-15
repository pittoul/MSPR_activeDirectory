<?php

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Guard\PasswordAuthenticatedInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class AppCustomAuthenticator extends AbstractFormLoginAuthenticator implements PasswordAuthenticatedInterface
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    private $entityManager;
    private $urlGenerator;
    private $csrfTokenManager;
    private $passwordEncoder;

    public function __construct(EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator, CsrfTokenManagerInterface $csrfTokenManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->entityManager = $entityManager;
        $this->urlGenerator = $urlGenerator;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function supports(Request $request)
    {
        return self::LOGIN_ROUTE === $request->attributes->get('_route')
            && $request->isMethod('POST');
    }

    public function getCredentials(Request $request)
    {
        $credentials = [
            'email' => $request->request->get('email'),
            'password' => $request->request->get('password'),
            'csrf_token' => $request->request->get('_csrf_token'),
            'infosUser' => $request->request->get('infosUser'),
        ];
        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['email']
        );

        return $credentials;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $token = new CsrfToken('authenticate', $credentials['csrf_token']);
        if (!$this->csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException();
        }

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $credentials['email']]);

        // $roleArrayFromAPI = [];
        //  METTRE ICI LE CURL DE L'API qui retournera la valeur du role (et du $passwordActiveDirectoryEncoded ?)
        // $user->setRoles($roleArrayFromAPI);

        // FAIRE IDEM POUR L'IP !!!!
        // et pour LA DATE DE CONNEXION (creer table historique.... et table clinique)
        // et NOMBRE DE TENTATIVES

        /**
         * 
         * Comparer les IP, 
         * 
         */

        // if ($user->getIpUser() != $credentials['ipUser']) {
            // Envoyer un simple mail d'information
        // }
        // if(ADRESSE IP HORS FRANCE){
        // envoyer le mail de connexion
        // https://symfony.com/doc/current/email.html
        // }

        /**
         * Enregistrement des infos du navigateur et envoie d'un mail avec un code pour la connexion :
         * Dans le mail, inclure un lien vers lapage de connexion assorti d'un code unique dans l'url 
         * qui servira à valider que la personne peut se connecter.
         * 
         * Ensuite seulement, on pourra enregistrer les infos du navigateur
         */
        if ($user->getInfosNavigateur()) {
            if (sizeof(array_diff_assoc(json_decode($credentials['infosUser'], true), $user->getInfosNavigateur())) > 0) {
                // Changement de navigateur : envoyer un mail pour la connexion
                // https://symfony.com/doc/current/email.html
                // CREER FONCTION : envoyerMailDeConnexion($codeUnique, $user)

                // $user->setInfosNavigateur(json_decode($credentials['infosUser'], true));
                // $this->entityManager->persist($user);
                // $this->entityManager->flush();
                return false;
            }
        } else {
            $user->setInfosNavigateur(json_decode($credentials['infosUser'], true));
            // Mise à jour des infos du user, notemment le role et les infos du navigateur
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }
        if (!$user) {
            // fail authentication with a custom error
            throw new CustomUserMessageAuthenticationException('Quelque chose s\'est mal passé...');
        }

        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        $passwordSaisiEncoded = $this->passwordEncoder->encodePassword($user, $credentials['password']);
        $passwordActiveDirectoryEncoded = "";

        // METTRE ICI LE CURL DE L'API qui retournera la valeur de $passwordActiveDirectoryEncoded (et le ROLE ?)

        // lignes à décommenter quand l'API sera fonctionnelle
        // if ($passwordActiveDirectoryEncoded === $passwordSaisiEncoded) {
        //     return true;
        // }
        // return false;


        // ligne à commenter quand l'API sera fonctionnelle
        return $this->passwordEncoder->isPasswordValid($user, $credentials['password']);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function getPassword($credentials): ?string
    {
        return $credentials['password'];
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {
            return new RedirectResponse($targetPath);
        }

        // Quand on est correctement loggué :

        // if ($user->getRoles() === ['medecin']) {
        //  return new RedirectResponse($this->urlGenerator->generate('app_medecin'));
        // }

        // if ($user->getRoles() === ['secretaire']) {
        //  return new RedirectResponse($this->urlGenerator->generate('app_secretaire'));
        // }

        // ligne à commenter quand l'API sera fonctionnelle
        return new RedirectResponse($this->urlGenerator->generate('app_home'));
        // throw new \Exception('TODO: provide a valid redirect inside '.__FILE__);
    }

    protected function getLoginUrl()
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
