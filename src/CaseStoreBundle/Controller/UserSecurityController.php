<?php

namespace CaseStoreBundle\Controller;

use CaseStoreBundle\Entity\User;
use CaseStoreBundle\Form\Type\UserChangePasswordType;
use CaseStoreBundle\Form\Type\UserRegisterType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;

/**
 *  @license 3-clause BSD
 *  @link https://github.com/CaseStore/CaseStore-Core
 */
class UserSecurityController extends Controller
{
    public function loginAction()
    {

        if ($this->getUser()) {
            return $this->redirect($this->generateUrl('case_store_homepage', array()));
        }

        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('CaseStoreBundle:UserSecurity:login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));

    }



    public function registerAction(Request $request)
    {
        
        if ($this->getUser()) {
            return $this->redirect($this->generateUrl('case_store_homepage', array()));
        }

        $user = new User();

        $doctrine = $this->getDoctrine()->getManager();

        $form = $this->createForm(new UserRegisterType(), $user);
        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {


                if ($form->get('password_1')->getData() != $form->get('password_2')->getData()) {
                    $form->addError(new FormError('Passwords Don\'t Match!'));
                } else if (strlen($form->get('password_1')->getData()) < 3) {
                    $form->addError(new FormError('Please choose a longer password'));
                } else {

                    $encoder = $this->container->get('security.password_encoder');
                    $user->setPassword($encoder->encodePassword($user, $form->get('password_1')->getData()));

                    $doctrine->persist($user);
                    $doctrine->flush();

                    $request->getSession()
                        ->getFlashBag()
                        ->add('notice', 'Thank you - you can now log in!');

                    return $this->redirect($this->generateUrl('case_store_login', array()));
                }
            }
        }

        return $this->render('CaseStoreBundle:UserSecurity:register.html.twig', array(
            'form' => $form->createView(),
        ));

    }

    public function yourAccountAction(Request $request)
    {
        return $this->render('CaseStoreBundle:UserSecurity:yourAccount.html.twig', array(
        ));
    }

    public function changePasswordAction(Request $request)
    {
        $user = $this->getUser();

        $doctrine = $this->getDoctrine()->getManager();

        $form = $this->createForm(new UserChangePasswordType());
        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {



                $encoder = $this->container->get('security.password_encoder');


                if (!$encoder->isPasswordValid($user, $form->get('old_password')->getData() )) {
                    $form->addError(new FormError('Old password wrong!'));
                } else if ($form->get('new_password_1')->getData() != $form->get('new_password_2')->getData()) {
                    $form->addError(new FormError('Passwords Don\'t Match!'));
                } else if (strlen($form->get('new_password_1')->getData()) < 3) {
                    $form->addError(new FormError('Please choose a longer password'));
                } else {

                    $user->setPassword($encoder->encodePassword($user, $form->get('new_password_1')->getData()));
                    $doctrine->persist($user);
                    $doctrine->flush();

                    $request->getSession()
                        ->getFlashBag()
                        ->add('notice', 'Password changed!');

                    return $this->redirect($this->generateUrl('case_store_homepage', array()));
                }
            }
        }

        return $this->render('CaseStoreBundle:UserSecurity:changePassword.html.twig', array(
            'form' => $form->createView(),
        ));

    }
}


