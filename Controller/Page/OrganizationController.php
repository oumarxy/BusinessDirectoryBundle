<?php
namespace SavoirFaireLinux\BusinessDirectoryBundle\Controller\Page;
use SavoirFaireLinux\BusinessDirectoryBundle\Controller\PageController;

use SavoirFaireLinux\BusinessDirectoryBundle\Entity\Page\Organization;
use SavoirFaireLinux\BusinessDirectoryBundle\Form\Page\OrganizationType;

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

class OrganizationController extends PageController {

    static $name = [
        'singular' => "organization",
        'plural' => "organizations",
    ];
    static $form = OrganizationType::class;
    static $model = Organization::class;
    static $route = 'organization';

}
