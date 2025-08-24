<?php

namespace App\Model\DataObject;

class News extends \Pimcore\Model\DataObject\News
{
    /**
     * @inheritdoc
     */
    public function getTitle(?string $language = null): ?string
    {
        $data = parent::getTitle($language);

        // customization
        if ($data) {
            $data = '[CUSTOM_LABEL]: ' . $data;
        }

        // Uncomment to test the custom Filter entity using Doctrine ORM
//         $this->filterDoctrineOrmDemo();

        // Uncomment to test the custom Filter entity using Pimcore DAO
//        $this->filterPimcoreDaoDemo();

        return $data;
    }

    /**
     * Showcase for a custom filter entity using Doctrine ORM
     */
    public function filterDoctrineOrmDemo()
    {
        // ! \Pimcore::getContainer() is deprecated and used only for demo purposes
        $container = \Pimcore::getContainer();
        $entityManager = $container->get('doctrine.orm.entity_manager');
        $colorRepository = $container->get(\App\Repository\ColorRepository::class);
        $filterRepository = $container->get(\App\Repository\FilterRepository::class);

        // Find a random color entity (assuming there are 10 in the database)
        $color = $colorRepository->find(rand(1,10));

        // Create a new filter entity
        $filter = new \App\Entity\Filter();
        $filter->setProductId(rand(1, 100));
        $filter->setCategoryId(rand(1, 3));
        $filter->addColor($color);
        $filter->setType(\App\Enum\FilterType::TYPE_ONE);

        // Save the filter entity
        $entityManager->persist($filter);
        $entityManager->flush();

        // Get existing filter and add a new color to it
        $foundFilter = $filterRepository->find(1);
        $foundFilter->addColor($color);

        // Save the filter entity
        $entityManager->persist($foundFilter);
        $entityManager->flush();
    }

    /**
     * Showcase for a custom filter entity using Pimcore DAO approach
     */
    public function filterPimcoreDaoDemo()
    {
        // Create a new filter entity
        $filter = new \App\Model\Filter();
        $filter->setProductId(rand(1, 100));
        $filter->setCategoryId(rand(1, 3));
        $filter->setType(\App\Enum\FilterType::TYPE_TWO->value);

        // Add a few colors to it
        $filter->addColor(\App\Model\Color::getById(3));
        $filter->addColor(\App\Model\Color::getById(4));
        $filter->addColor(\App\Model\Color::getById(7));

        // Save filter data and color relations
        $filter->save();
    }
}
