<?php
namespace SavoirFaireLinux\BusinessDirectoryBundle\Entity\ModelTrait;

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

/*
 * @ORM\HasLifecycleCallbacks
 */
trait Slug {

    /**
     * @ORM\Column(type="string")
     */
    protected $slug;


    public function getSlug() {
        return $this->slug;
    }

    public function setSlug($slug) {
        $this->slug = $slug;
        return $this;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function slugPreCommit() {
        $this->setSlug($this->generateSlug($this->getName()));
    }

    private function generateSlug($name) {
        $separator = '-';
        $slug = str_replace('\'', null, $this->getName());
        $slug = str_replace('"', null, $slug);
        $accents_regex = '~&([a-zA-Z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i';
        $special_cases = array( '&' => 'and');
        $slug = mb_strtolower(trim($slug), 'UTF-8');
        $slug = str_replace(array_keys($special_cases), array_values($special_cases), $slug);
        $slug = preg_replace($accents_regex, '$1', htmlentities($slug, ENT_QUOTES, 'UTF-8'));
        $slug = preg_replace("/[^a-z0-9]/u", $separator, $slug);
        $slug = preg_replace("/[$separator]+/u", $separator, $slug);
        if(substr($slug, -1) == '-') $slug = substr($slug, 0, -1);
        return $slug;
    }

}
