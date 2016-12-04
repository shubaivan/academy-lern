<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Users;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class LearnController extends Controller
{
    /**
     * @Route("/first", name="first_lesson")
     * @Template()
     */
    public function firstAction(Request $request)
    {
        /** @var Users $user */
        $user = $this->getUser();

        $data = array();
        $form = $this->createFormBuilder($data)
            ->add('name', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(array('min' => 3)),
                ],
            ])

            ->getForm();

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            $data = $form->getData();
            if (isset($data['name']) && $data['name']) {
                $user->setName($data['name']);
                $user->setScore($user->getScore() + 1);
                $user->setStartLearn(new \DateTime());
                $em = $this->getDoctrine()->getManager();
                $em->flush();

                return $this->redirectToRoute('second_lesson');
            }
        }

        return [
            'form' => $form->createView(),
            'user' => $user
        ];
    }
    
    /**
     * @Route("/second", name="second_lesson")
     * @Method("GET")
     * @Template()
     */
    public function secondAction(Request $request)
    {
        /** @var Users $user */
        $user = $this->getUser();
        $firstDigit = rand(1, 20);
        $secondDigit = rand(1, 20);
        $em = $this->getDoctrine()->getManager();
        $user->setSecondSum($firstDigit + $secondDigit);
        $em->flush();

        return [
            'user' => $user,
            'firstDigit' => $firstDigit,
            'secondDigit' => $secondDigit,
        ];
    }

    /**
     * @Route("/second_result", name="second_result_lesson")
     * @Method("POST")
     * @Template()
     */
    public function secondResultAction(Request $request)
    {
        /** @var Users $user */
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        if ($request->request->get('sum')) {
            if ($user->getSecondSum() === (integer)$request->request->get('sum')) {
                $user->setScore($user->getScore() + 1);
                $em->flush();
            }
            return $this->redirectToRoute('third_lesson');
        } else {
            $this->addFlash('danger', 'sum required true');
            return $this->redirectToRoute('second_lesson');
        }
    }

    /**
     * @Route("/third", name="third_lesson")
     * @Template()
     */
    public function thirdAction(Request $request)
    {
        /** @var Users $user */
        $user = $this->getUser();
        $skills = ['php' => 'PHP', 'python' => 'Python', 'js' => 'JS', '.net' => '.net', 'visual_basic' => 'Visual Basic'];
        $data = array();
        $form = $this->createFormBuilder($data)
            ->add('skills', 'choice', [
                'choices' => $skills,
                'multiple' => true,
                'expanded' => true
            ])
            ->getForm();

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            $data = $form->getData();
            if (isset($data['skills']) && $data['skills']) {
                $em = $this->getDoctrine()->getManager();
                if (!in_array('visual_basic', $data['skills'])) {
                    $user->setScore($user->getScore() + 1);
                    $em->flush();
                }
                return $this->redirectToRoute('fourth_lesson');
            } else {
                $this->addFlash('danger', 'skills required true');
            }
        }

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/fourth", name="fourth_lesson")
     * @Template()
     */
    public function fourthAction(Request $request)
    {
        /** @var Users $user */
        $user = $this->getUser();
        $days = [mb_strtolower(date('l')) => date('l'), 'wednesday' => 'Wednesday', 'tuesday' => 'Tuesday', 'monday' => 'Monday'];
        $data = array();
        $form = $this->createFormBuilder($data)
            ->add('days', 'choice', [
                'choices' => $days,
                'multiple' => false,
                'expanded' => true,
                'required' => true
            ])

            ->getForm();

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            $data = $form->getData();
            if (isset($data['days']) && $data['days']) {
                $em = $this->getDoctrine()->getManager();
                if (mb_strtolower(date('l')) == $data['days']) {
                    $user->setScore($user->getScore() + 1);
                    $em->flush();
                }
                return $this->redirectToRoute('fifth_lesson');
            } else {
                $this->addFlash('danger', 'skills required true');
            }
        }

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/fifth", name="fifth_lesson")
     * @Template()
     */
    public function fifthAction(Request $request)
    {
        /** @var Users $user */
        $user = $this->getUser();

        return [
            
        ];
    }

    /**
     * @Route("/finish", name="finish_lesson")
     * @Template()
     */
    public function finishAction(Request $request)
    {
        /** @var Users $user */
        $user = $this->getUser();
        $user->setEndLearn(new \DateTime());
        $em = $this->getDoctrine()->getManager();
        $em->flush();

        $scoreTime = (new \DateTime())->getTimestamp() - $user->getStartLearn()->getTimestamp();

        return [
            'user' => $user,
            'scoreTime' => $scoreTime
        ];
    }
}