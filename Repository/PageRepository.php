<?php
namespace SavoirFaireLinux\BusinessDirectoryBundle\Repository;
use Doctrine\ORM\EntityRepository;

use Doctrine\ORM\Tools\Pagination\Paginator;

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

class PageRepository extends EntityRepository {

    public function findByFilters($pageNo, $nElPerPage, $filters) {
        $queryBuilder = $this->createQueryBuilder('p')
                      ->where('p.isPublished = true')
                      ->orderBy('p.createdAt', 'DESC');
        foreach($filters as $key => $value) {
            if($value == null) continue;
            $queryBuilder->andWhere('p.' . $key . ' = :' . $key);
            $queryBuilder->setParameter($key, $value);
        }
        $query = $queryBuilder->getQuery();
        $query->setFirstResult($nElPerPage * ($pageNo - 1))
              ->setMaxResults($nElPerPage);
        return new Paginator($query);
    }

}
