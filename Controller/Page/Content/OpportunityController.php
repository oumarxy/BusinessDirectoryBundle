<?php
namespace SavoirFaireLinux\BusinessDirectoryBundle\Controller\Page\Content;
use SavoirFaireLinux\BusinessDirectoryBundle\Controller\Page\ContentController;

use SavoirFaireLinux\BusinessDirectoryBundle\Entity\Page\Content\Opportunity;
use SavoirFaireLinux\BusinessDirectoryBundle\Form\Page\Content\OpportunityType;

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

class OpportunityController extends ContentController {

    static $name = [
        'singular' => "opportunity",
        'plural' => "opportunities",
    ];
    static $form = OpportunityType::class;
    static $model = Opportunity::class;
    static $route = 'opportunity';

}
