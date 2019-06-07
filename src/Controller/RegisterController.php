<?php

namespace App\Controller;

use App\Form\UserRegistrationFormType;
use App\Security\LoginFormAuthenticator;
use App\Service\RegisterService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;


class RegisterController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     */

    public function register(Request $request, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $formAuthenticator, RegisterService $registerService)
    {
        $form = $this->createForm(UserRegistrationFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $form->getData();
            $registerService->registerUser($user, $form['password']->getData(), $form['agreeTerms']->getData());
//            $user->setPassword($passwordEncoder->encodePassword(
//                $user,
//                $form['password']->getData()
//                //$request->request->get('password')
//            ));
//
//            // be absolutely sure they agree
//            if (true === $form['agreeTerms']->getData()) {
//                $user->agreeToTerms();
//            }
//
//            $em = $this->getDoctrine()->getManager();
//            $em->persist($user);
//            $em->flush();

            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $formAuthenticator,
                'main'
            );


        }


        return $this->render('login/register.html.twig', array(
            'registration_form' => $form->createView(),
        ));
    }

    /*
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        if ($request->isMethod('POST')) {
            $user = new User();
            $user->setEmail($request->request->get('email'));
            $user->setPassword($passwordEncoder->encodePassword(
                $user,
                $request->request->get('password')
            ));
            $user->agreeToTerms();
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('author_list');
        }
        return $this->render('login/register.html.twig');
    }*/

    /**
     * @Route("/api/account", name="api_account")
     */
    public function accountApi()
    {
        $user = $this->getUser();
        return $this->json($user, 200, [], [
            'groups' => ['main'],
        ]);
    }

}
