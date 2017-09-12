<?php
namespace SavoirFaireLinux\BusinessDirectoryBundle\Form\Page;
use SavoirFaireLinux\BusinessDirectoryBundle\Form\PageType;

use SavoirFaireLinux\BusinessDirectoryBundle\Entity\Page\Organization;

use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;

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

class ContentType extends PageType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        parent::buildForm($builder, $options);
        $builder->add('organization', EntityType::class, [
            'label' => "Owner organization of the page",
            'required' => true,
            'class' => Organization::class,
            'query_builder' => function(EntityRepository $er) use ($options) {
                return $er->createQueryBuilder('c')
                          ->where('c.user = :user')
                          ->setParameter('user', $options['user']);
            },
        ]);
    }

}
