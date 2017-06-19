<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    /**
     * @Route("/product/new")
     */
    public function indexAction()
    {
        for ($i = 0; $i <= 10; $i++) {
            $product = new Product();

            $product->setName('Product ' .$i);
            $product->setPrice(rand(10,100));

            //get url
            $contentXML = file_get_contents('http://www.lipsum.com/feed/xml?amount=1&what=paras&start=0');
            //get new crawler obj
            $crawler = new Crawler($contentXML);
            //filter obj to get lipsum
            $text = $crawler->filter('feed > lipsum')->first()->text();

            //set text to procut descrip
            $product->setDescription($text);

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();
        }


        return new Response('<html><body>10 Products created!</body></html>');
    }


    /**
     * @Route("/product/list", name="product_show")
     */
    public function listAction()
    {
        /*$em = $this->getDoctrine()->getManager();

        //pass class name to find all prod
        $products = $em->getRepository('AppBundle:Product')
            ->findAllProducts();


        dump($products); die;
        return $this->render('product/list.html.twig', [
            'products' => $products
        ]);*/

        $em = $this->getDoctrine()->getManager();
        $products = $em->getRepository('AppBundle:Product')
            ->findAll();
        return $this->render('product/list.html.twig', [
            'products' => $products
        ]);

    }

    /**
     * @param Product $product
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/product/delete/{id}", name="delete_product")
     */
    public function destroyAction(Product $product)
    {
        //get entity manager of doctrine
        $em = $this->getDoctrine()->getManager();
        $em->remove($product);
        $em->flush();


        //redirect to list
        return $this->redirectToRoute('product_show');


    }




}
