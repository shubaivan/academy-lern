<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Users;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        
        $users = $em->getRepository('AppBundle:Users')
            ->getAllUsers();
        
        return $this->render(
            'default/index.html.twig',
            [
                'users' => $users
            ]
        );
    }

    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request)
    {
        $user = $this->getUser();
        if ($user instanceof UserInterface) {
            return $this->redirectToRoute('homepage');
        }

        /** @var AuthenticationException $exception */
        $exception = $this->get('security.authentication_utils')
            ->getLastAuthenticationError();

        return $this->render('@App/default/login.html.twig', [
            'error' => $exception ? $exception->getMessage() : NULL,
        ]);
    }
}
