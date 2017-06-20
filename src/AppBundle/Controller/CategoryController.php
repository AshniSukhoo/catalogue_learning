<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\Product;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use \Symfony\Component\HttpFoundation\Request ;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{
    public function indexAction($name)
    {

        return $this->render('', array('name' => $name));
    }


    /**
     * @Route("/category/new", name="new_category")
     */
    public function newAction()
    {
        for ($i = 0; $i <= 10; $i++) {
            //new cat obj
            $category = new Category();
            $category->setName('Category'.$i);


            //new product obj
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

            //set product category
            $product->setCategory($category);

            $category->setProduct($product);

            //perfrom action
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->persist($product);
            $em->flush();
        }

        return new Response('<html><body>10 Categories created!</body></html>');
    }


    /**
     * @Route("/category/list", name="show_category")
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('AppBundle:Category')
            ->findAll();
        return $this->render('category/list.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/category/addcategory", name="add_category")
     *
     */
    public function addAction(Request $request)
    {

        //new cat obj
        $category = new Category();

        //retrieve name from form
        $name = $request->request->get('name');
        //set name to field
        $category->setName($name);


        $em = $this->get('doctrine')->getManager();

        // tells Doctrine you want to save obj
        $em->persist($category);
        $em->flush();

        return new Response('Saved new product!!');


    }

    /**
     * @Route("/category/getform", name="get_from")
     */
    public  function getFormAction()
    {
        return $this->render('category/getform.html.twig', [
        ]);
    }





}
