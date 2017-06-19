<?php

namespace AppBundle\Repository;

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

}