<?php
namespace SavoirFaireLinux\BusinessDirectoryBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use SavoirFaireLinux\BusinessDirectoryBundle\Entity\Taxonomy\Category;
use SavoirFaireLinux\BusinessDirectoryBundle\Entity\Taxonomy\Region;

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

abstract class PageController extends ApplicationController {

    const N_EL_PER_PAGE = 10;
    static $filters = [];

    protected function filtersInjection() {
        static::$filters = [
            'region' => Region::class,
            'category' => Category::class,
        ];
    }

    /**
     * @Method({"GET"})
     * @Template("BusinessDirectoryBundle:Page:index.html.twig")
     */
    public function indexAction(Request $request) {
        $this->filtersInjection();
        $filtersSelected = [];
        $filtersChoices = [];
        foreach(static::$filters as $key => $filterClass) {
            $filtersSelected[$key] = $request->query->get($key);
            $filtersChoices[$key] = $this->getRepository($filterClass)->findAll();
        }
        $pageNo = $request->query->get('pageNo') ?: 1;
        $pages = $this->getRepository(static::$model)->findByFilters(
            $pageNo, self::N_EL_PER_PAGE, $filtersSelected
        );
        return [
            'nPage' => ceil($pages->count() / self::N_EL_PER_PAGE),
            'pageNo' => $pageNo,
            'modelName' => static::$name,
            'filtersChoices' => $filtersChoices,
            'filtersSelected' => $filtersSelected,
            'pages' => $pages,
            'getParameters' => $_GET,
        ];
    }

    /**
     * @Method({"GET"})
     * @Template("BusinessDirectoryBundle:Page:manage.html.twig")
     */
    public function manageAction() {
        if($this->getCurrentUser() == null) return $this->requireLogin();
        return [
            'pages' => $this->getRepository(static::$model)->findByUser($this->getCurrentUser()),
            'modelName' => static::$name,
        ];
    }

    /**
     * @Method({"GET"})
     * @Template("BusinessDirectoryBundle:Page:search.html.twig")
     */
    public function searchAction() {
        return [
            'pages' => $this->getRepository(static::$model)->findAll(),
            'modelName' => static::$name,
        ];
    }

    /**
     * @Method({"GET"})
     * @Template("BusinessDirectoryBundle:Page:read.html.twig")
     */
    public function readAction($id, $slug) {
        $page = $this->getRepository(static::$model)->find($id);
        if($page == null) throw new NotFoundHttpException("404 Not Found");
        if($page->getIsPublished() == false) {
            if($page->getUser() == $this->getCurrentUser()) {
                $this->addFlashMessage('warning', "This page is not published, only you can view it.");
            }
            else {
                throw new NotFoundHttpException("404 Not Found");
            }
        }

        if($page->getSlug() != $slug) {
            return $this->redirectToRoute(static::$route.'_read', [
                'id' => $page->getId(), 'slug' => $page->getSlug()
            ]);
        }
        return [
            'page' => $page,
            'modelName' => static::$name,
        ];
    }

    /**
     * @Method({"GET", "POST"})
     * @Template("BusinessDirectoryBundle:Page:create.html.twig")
     */
    public function createAction(Request $request) {
        if($this->getCurrentUser() == null) return $this->requireLogin();
        $page = new static::$model;
        $form = $this->generateComposeForm($page);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $page->setUser($this->getCurrentUser());
            $this->getEntityManager()->persist($page);
            $this->getEntityManager()->flush();
            $this->addFlashMessage('success', "Page successfully saved.");
            return $this->redirectToRoute(static::$route.'_update', [
                'id' => $page->getId(),
            ]);
        }
        return [
            'page' => $page,
            'form' => $form->createView(),
            'modelName' => static::$name,
        ];
    }

    /**
     * @Method({"GET", "POST"})
     * @Template("BusinessDirectoryBundle:Page:update.html.twig")
     */
    public function updateAction(Request $request, $id) {
        if($this->getCurrentUser() == null) return $this->requireLogin();
        $page = $this->getRepository(static::$model)->find($id);
        if($page == null) throw new NotFoundHttpException("404 Not Found");
        if($page->getUser() != $this->getCurrentUser())
            throw new AccessDeniedHttpException("403 Access Denied");
        $form = $this->generateComposeForm($page);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $this->getEntityManager()->flush();
            $this->addFlashMessage('success', "Page successfully saved.");
            return $this->redirectToRoute(static::$route.'_update', [
                'id' => $page->getId(),
            ]);
        }
        return [
            'page' => $page,
            'form' => $form->createView(),
            'modelName' => static::$name,
        ];
    }

    /**
     * @Method({"GET", "POST"})
     * @Template("BusinessDirectoryBundle:Page:delete.html.twig")
     */
    public function deleteAction(Request $request, $id) {
        $page = $this->getRepository(static::$model)->find($id);
        if($page == null) throw new NotFoundHttpException("404 Not Found");
        if($page->getUser() != $this->getCurrentUser())
            throw new AccessDeniedHttpException("403 Access Denied");
        $form = $this->createFormBuilder();
        $form->add('submit', SubmitType::class, [
            'label' => 'Delete page',
            'attr' => [
                'class' => 'btn-danger',
            ],
        ]);
        $form = $form->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $this->getEntityManager()->remove($page);
            $this->getEntityManager()->flush();
            $this->addFlashMessage('success', "Page successfully deleted.");
            return $this->redirectToRoute(static::$route.'_manage');
        }
        return [
            'page' => $page,
            'form' => $form->createView(),
            'modelName' => static::$name,
        ];
    }

    private function generateComposeForm($page) {
        $this->filtersInjection();
        $form = $this->createForm((string) static::$form, $page, [
            'method' => 'POST',
            'filters' => static::$filters,
        ]);
        $form->add('submit', SubmitType::class, ['label' => 'Save']);
        return $form;
    }

}
