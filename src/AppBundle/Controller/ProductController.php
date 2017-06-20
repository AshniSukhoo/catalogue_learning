<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\Product;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Response;
use \Symfony\Component\HttpFoundation\Request ;

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

        //delete product
        $em->remove($product);
        $em->flush();


        //redirect to list
        return $this->redirectToRoute('product_show');


    }

    /**
     * @Route("/category/product/{id}", name="show_category_products")
     */
    public function searchAction(Category $category)
    {
        $em = $this->getDoctrine()->getManager();


        $products = $em->getRepository('AppBundle:Product')->findByCategory($category);


        return $this->render('category/list_cat_prod.html.twig', [
            'products' => $products,
            'category_id' => $category->getId()
        ]);

    }

    /**
     * @Route("/category/product/id/{id}", name="show_specific_cat_prod")
     */
    public function showSpecifyItemAction(Product $product)
    {
        //get entity manager of doctrine
        $em = $this->getDoctrine()->getManager();

        //get prod
        $specify_product = $em->getRepository('AppBundle:Product')->find($product);


        return $this->render('category/show.html.twig', [
            'specific_products' => $specify_product,
        ]);

    }

    /**
     * @Route("/product/getform/{id}", name="get_prod_from")
     */
    public  function getFormAction(Category $category)
    {
        return $this->render('product/getform.html.twig', [
            'category' => $category->getId()
        ]);
    }

    /**
     * @Route("/product/addproduct/{id}", name="add_product")
     *
     */
    public function addAction(Category $category, Request $request)
    {

        //new cat obj
        $product = new Product();

        //retrieve values from form
        $name = $request->request->get('name');
        $price = $request->request->get('price');
        $description = $request->request->get('des');


        //set values to field
        $product->setName($name);
        $product->setPrice($price);
        $product->setDescription($description);

        //set cat id
        $id = $category->getId();
        $product->setCategory($category);


//        dump($id);die();

        $em = $this->get('doctrine')->getManager();

        // tells Doctrine you want to save obj
        $em->persist($product);
        $em->flush();

        return new Response('Saved new product!!');


    }




}
