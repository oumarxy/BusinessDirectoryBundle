<?php
namespace SavoirFaireLinux\BusinessDirectoryBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Yaml\Yaml;

use SavoirFaireLinux\BusinessDirectoryBundle\Entity\Taxonomy\Region;
use SavoirFaireLinux\BusinessDirectoryBundle\Entity\Taxonomy\Category;

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

class Fixtures extends Fixture {

    public function load(ObjectManager $manager) {
        $pathChunks = explode('/', __FILE__);
        unset($pathChunks[sizeof($pathChunks) - 1]);
        $this->path = implode('/', $pathChunks).'/..';

        $this->loadTaxonomy($manager);
        $manager->flush();
    }

    private function loadTaxonomy(ObjectManager $manager) {
        $entries = Yaml::parse(file_get_contents($this->path . '/taxonomy.yml'));
        foreach($entries as $entry) {
            $type = null;
            switch($entry['type']) {
                case 'Region':
                    $type = Region::class;
                    break;
                case 'Category':
                    $type = Category::class;
                    break;
            }
            $entity = new $type;
            foreach($entry['fields'] as $key => $value) {
                $getterFuncName = 'set' . ucfirst($key);
                $entity->$getterFuncName($value);
                $manager->persist($entity);
            }
        }
    }

}
