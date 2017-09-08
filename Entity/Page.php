<?php
namespace SavoirFaireLinux\BusinessDirectoryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
* @ORM\InheritanceType("SINGLE_TABLE")
* @ORM\DiscriminatorColumn(name="discr", type="string")
* @ORM\Entity
* @ORM\HasLifecycleCallbacks
*/
abstract class Page {

    use ModelTrait\Identifier;
    use ModelTrait\Name;
    use ModelTrait\Slug;
    use ModelTrait\Description;
    use ModelTrait\Timestamp;
    use ModelTrait\Published;

    /**
     * @ORM\ManyToOne(targetEntity="SavoirFaireLinux\BusinessDirectoryBundle\Entity\User")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="SavoirFaireLinux\BusinessDirectoryBundle\Entity\Taxonomy\Category")
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity="SavoirFaireLinux\BusinessDirectoryBundle\Entity\Taxonomy\Region")
     */
    private $region;


    public function getUser() {
        return $this->user;
    }

    public function setUser($user) {
        $this->user = $user;
        return $this;
    }

    public function getCategory() {
        return $this->category;
    }

    public function setCategory($category) {
        $this->category = $category;
        return $this;
    }

    public function getRegion() {
        return $this->region;
    }

    public function setRegion($region) {
        $this->region = $region;
        return $this;
    }


    public function getDiscr() {
        $machine = explode('\\', get_class($this));
        return strtolower($machine[sizeof($machine) - 1]);
    }

}
