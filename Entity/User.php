<?php
namespace SavoirFaireLinux\BusinessDirectoryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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

/**
 * @ORM\Table("account")
 * @ORM\Entity(repositoryClass="SavoirFaireLinux\BusinessDirectoryBundle\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks
 */
class User {

    use ModelTrait\Identifier;
    use ModelTrait\Timestamp;

    /**
     * @ORM\Column(type="string", unique=true, nullable=false)
     */
    private $email;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $hashed_password;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $salt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastLoginAt;


    public function __toString() {
        return $this->getEmail();
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
        return $this;
    }

    public function getPassword() {
        return null;
    }

    public function setPassword($password) {
        $this->setHashedPassword(crypt($password, $this->generateSalt()->getSalt()));
        return $this;
    }

    public function isPasswordValid($password) {
        return crypt($password, $this->getSalt()) == $this->getHashedPassword();
    }

    public function getHashedPassword() {
        return $this->hashed_password;
    }

    private function setHashedPassword($hashed_password) {
        $this->hashed_password = $hashed_password;
        return $this;
    }

    private function getSalt() {
        return $this->salt;
    }

    private function setSalt($salt) {
        $this->salt = $salt;
        return $this;
    }

    private function generateSalt() {
        $this->setSalt(crypt(rand(0, PHP_INT_MAX), rand(0, PHP_INT_MAX)));
        return $this;
    }

    public function getLastLoginAt() {
        return $this->lastLoginAt;
    }

    public function setLastLoginAt($lastLoginAt) {
        $this->lastLoginAt = $lastLoginAt;
        return $this;
    }

}
