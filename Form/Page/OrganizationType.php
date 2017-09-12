<?php
namespace SavoirFaireLinux\BusinessDirectoryBundle\Form\Page;
use SavoirFaireLinux\BusinessDirectoryBundle\Form\PageType;

use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;

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

class OrganizationType extends PageType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        parent::buildForm($builder, $options);
        $builder->add('emailAddress', EmailType::class, [
            'label' => "Email address",
            'required' => false,
        ])->add('websiteAddress', UrlType::class, [
            'label' => "Website address URL",
            'required' => false,
        ])->add('phoneNumber', TextType::class, [
            'label' => "Phone number",
            'required' => false,
        ])->add('officeAddress', TextType::class, [
            'label' => "Office physical address",
            'required' => false,
        ]);
    }

}
