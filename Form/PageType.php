<?php
namespace SavoirFaireLinux\BusinessDirectoryBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
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

class PageType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('name', TextType::class, [
            'label' => "Title of the page",
            'required' => true,
        ])
        ->add('description', TextType::class, [
            'label' => "Description of the page",
            'required' => true,
        ]);
        foreach($options['filters'] as $key => $class) {
            $builder->add($key, EntityType::class, array(
                'class' => $class,
            ));
        }
        $builder->add('is_published', CheckboxType::class, [
            'label' => "Is published ?",
            'required' => false
        ])
        ->add('content', TextareaType::class, [
            'label' => "Main content",
            'required' => false,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'filters' => [],
            'user' => null,
        ]);
    }

}
