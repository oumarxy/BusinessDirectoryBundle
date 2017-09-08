<?php
namespace SavoirFaireLinux\BusinessDirectoryBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

use SavoirFaireLinux\BusinessDirectoryBundle\Entity\User;

/**
 * SFL/BusinessDirectory - Symfony3 business directory
 *
 *  Copyright 2017 by Savoir-faire Linux
 *
 * This file is part of SFL/BusinessDirectory application.
 * 
 * SFL/BusinessDirectory application is free software: you can redistribute 
 * it and/or modify it under the terms of the GNU General Public 
 * License as published by the Free Software Foundation, either 
 * version 3 of the License, or (at your option) any later version.
 * 
 * SFL/BusinessDirectory application is distributed in the hope that it will 
 * be useful, but WITHOUT ANY WARRANTY; without even the implied warranty 
 * of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with SFL/BusinessDirectory.  If not, see <http://www.gnu.org/licenses/>.
 */

class UserController extends ApplicationController {

    /**
     * @Method({"GET"})
     */
    public function logoutAction() {
        if($this->getCurrentUser() != null) {
            $this->unlinkCurrentUser();
            $this->addFlashMessage('success', "The session is now securely terminated.");
        }
        return $this->redirectToRoute('user_login');
    }

    /**
     * @Method({"GET", "POST"})
     * @Template
     */
    public function loginAction(Request $request) {
        if($this->getCurrentUser() != null) {
            return $this->redirectToRoute('home_index');
        }
        $data = [];
        $form = $this->createFormBuilder($data)
            ->add('email', EmailType::class, ['label' => "Email address"])
            ->add('password', PasswordType::class, ['label' => "Password"])
            ->add('stay_connected', CheckboxType::class, ['label' => "Stay connected", 'required' => false])
            ->add('submit', SubmitType::class, ['label' => "Login"])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $user = $this->getRepository(User::class)->findOneByEmail(trim($data['email']));
            if($user == null) {
                $this->addFlashMessage('danger', "Could not find email address in the database.");
            }
            elseif($user->isPasswordValid($data['password']) == false) {
                $this->addFlashMessage('danger', "The entered password is invalid.");
            }
            else {
                $this->addFlashMessage('success', "Logged in successfully on account.");
                if((bool) $data['stay_connected'])
                    $this->setSessionExpiration(60 * 60 * 24 * 31);
                $user->setLastLoginAt(new \DateTime());
                $user->setCancelUpdatedAtTrigger(true);
                $this->getEntityManager()->flush();
                $this->setCurrentUser($user);
                $this->initSession();
                $redirect_to = $this->getAndClearRedirectTo();
                if($redirect_to == null) {
                    return $this->redirectToRoute('home_index');
                }
                else {
                    return $this->redirect($redirect_to);
                }
            }
        }
        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Method({"GET", "POST"})
     * @Template
     */
    public function signupAction(Request $request) {
        if($this->getCurrentUser() != null) {
            return $this->redirectToRoute('home_index');
        }
        $data = [];
        $form = $this->createFormBuilder($data)
            ->add('email', RepeatedType::class, [
                'type' => EmailType::class,
                'first_options'  => ['label' => "Email address"],
                'second_options' => ['label' => "Repeat email address"],
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options'  => ['label' => "Password"],
                'second_options' => ['label' => "Repeat password"],
                'required' => false
            ])
            ->add('submit', SubmitType::class, ['label' => "Signup"])
            ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() and $form->isValid()) {
            $data = $form->getData();
            if($this->getRepository(User::class)->findOneByEmail($data['email'])) {
                $this->addFlashMessage('danger', "This email address is already in use.");
            }
            else {
                $user = new User;
                $user->setEmail(trim($data['email']));
                $user->setPassword($data['password']);
                $this->getEntityManager()->persist($user);
                $this->getEntityManager()->flush();
                $this->addFlashMessage('success', "The account has been successfully created, you can now login.");
                return $this->redirectToRoute('user_login');
            }
        }
        return [
            'form' => $form->createView()
        ];
    }

}
