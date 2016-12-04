<?php
namespace AppBundle\Security;

use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Doctrine\Security\User\EntityUserProvider;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\InMemoryUserProvider;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class FormAuthenticator extends AbstractGuardAuthenticator
{
    /**
     * @var \Symfony\Component\Routing\RouterInterface
     */
    private $router;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var UserPasswordEncoder
     */
    private $upe;

    /**
     * Default message for authentication failure.
     *
     * @var string
     */
    private $failMessage = 'Invalid credentials';

    /**
     * FormAuthenticator constructor.
     * @param RouterInterface $router
     * @param EntityManager $em
     * @param UserPasswordEncoder $upe
     */
    public function __construct(
        RouterInterface $router,
        EntityManager $em,
        UserPasswordEncoder $upe
    ) {
        $this->router = $router;
        $this->em = $em;
        $this->upe = $upe;
    }

    /**
     * {@inheritdoc}
     */
    public function getCredentials(Request $request)
    {
        if ($request->getPathInfo() != '/login' || !$request->isMethod('POST')) {
            return;
        }

        return array(
            'username' => $request->request->get('username'),
            'password' => $request->request->get('password'),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        if (!$userProvider instanceof InMemoryUserProvider && !$userProvider instanceof EntityUserProvider ) {
            return;
        }

        try {
            return $userProvider->loadUserByUsername($credentials['username']);
        }
        catch (UsernameNotFoundException $e) {
            $user = $this->em->getRepository('AppBundle:Users')
                ->findOneBy([
                    'email' => $credentials['username']
                ]);
            if ($user) {
                return $user;
            } else {
                throw new CustomUserMessageAuthenticationException($this->failMessage);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        $isPasswordValid = $this->upe->isPasswordValid($user, $credentials['password']);
        if ($user->getPassword() === $credentials['password']
            || $isPasswordValid) {
            return true;
        }
        throw new CustomUserMessageAuthenticationException($this->failMessage);
    }

    /**
     * {@inheritdoc}
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        $url = $this->router->generate('homepage');
        return new RedirectResponse($url);
    }

    /**
     * {@inheritdoc}
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);
        $url = $this->router->generate('login');
        return new RedirectResponse($url);
    }

    /**
     * {@inheritdoc}
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        $url = $this->router->generate('login');
        return new RedirectResponse($url);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsRememberMe()
    {
        return false;
    }
}