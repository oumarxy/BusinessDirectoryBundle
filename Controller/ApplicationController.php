<?php
namespace SavoirFaireLinux\BusinessDirectoryBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use SavoirFaireLinux\BusinessDirectoryBundle\Entity\User;
use SavoirFaireLinux\BusinessDirectoryBundle\Repository\UserRepository;

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

abstract class ApplicationController extends Controller {

    protected $userId;
    protected $currentUser;

    protected function getSession() {
        return $this->container->get('session');
    }

    protected function addFlashMessage($type, $message) {
        $this->getSession()->getFlashBag()->add($type, $message);
        return $this;
    }

    protected function getEntityManager() {
        return $this->getDoctrine()->getManager();
    }

    protected function getRepository($entityClass) {
        return $this->getEntityManager()->getRepository($entityClass);
    }

    protected function setSessionExpiration($session_expiration) {
        $this->getSession()->getMetadataBag()->stampNew($session_expiration);
        return $this;
    }

    protected function getCurrentUser() {
        $currentId = $this->getSession()->get('userId');
        if($this->userId == null and $currentId != null) {
            $this->currentUser = $this->getRepository(User::class)->find($currentId);
            $this->getSession()->set('currentUser', $this->currentUser);
        }
        return $this->currentUser;
    }

    protected function setCurrentUser($user) {
        $this->getSession()->set('userId', $user->getId());
        return $this;
    }

    protected function unlinkCurrentUser() {
        $this->getSession()->clear();
        return $this;
    }

    protected function getReferer() {
        if(isset($_SERVER['HTTP_REFERER'])) {
            return $_SERVER['HTTP_REFERER'];
        }
        return $this->generateUrl('page_index');
    }

    protected function getAndClearRedirectTo() {
        $redirect_to = $this->getSession()->get('redirect_to');
        $this->getSession()->remove('redirect_to');
        return $redirect_to;
    }

    protected function getCurrentUrl() {
        return $_SERVER['REQUEST_URI'];
    }

    protected function requireLogin($redirectTo = null) {
        if($redirectTo == null) $redirectTo = $this->getCurrentUrl();
        $this->addFlashMessage('warning', "You must be logged in in order to access this section.");
        $this->getSession()->set('redirect_to', $redirectTo);
        return $this->redirectToRoute('user_login');
    }

    public function initSession() {
        $this->getCurrentUser();
    }

}
