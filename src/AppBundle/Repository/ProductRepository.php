<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Category;
use AppBundle\Entity\Product;
use Doctrine\ORM\EntityRepository;


class ProductRepository extends EntityRepository
{

    /**
     * @return Product[]
     */
    public  function findAllProducts()
    {
       return $this->createQueryBuilder('product')
             ->getQuery()
             ->getResult();

    }

    public function deleteProduct(Product $product)
    {
        $this->_em->remove($product);
        $this->_em->flush();

    }

    /**
     * @param Category $category
     * @return Product[]
     */
    public function findByCategory(Category $category)
    {
        return $this->createQueryBuilder('product')
            ->andWhere('product.category = :category')
            ->setParameter('category', $category)
            ->leftJoin('product.category', 'category_product')
            ->orderBy('product.name', 'ASC')
            ->getQuery()
            ->execute();
    }

}