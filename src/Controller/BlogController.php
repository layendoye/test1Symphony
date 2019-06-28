<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
use App\Repository\ArticleRepository;

class BlogController extends AbstractController
{//la classe est crée lorsqu'on a creer notre controller en faisant php bin/console make:controller
    /**
     * @Route("/blog", name="blog")
     */
    public function index(ArticleRepository $repo){//affiche l'ensembles de nos articles
        
        //$repo=$this->getDoctrine()->getRepository(Article::class);//demander à doctrine de nous creer un repository pour gerer nos articles (et pouvoir selectionner des données)
        // remplacer par ArticleRepository $repo


        //$article=$repo->find(12);//renvoi l'article avec l'id 12
        //$article=$repo->findOneByTitle('Titre de l\'article');//un article qui a ce titre
        //$articles=$repo->findByTitle('Titre de l\'article');//tous les articles qui ont ce titre
        $articles=$repo->findAll();//tous les articles

        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'articles'=>$articles
        ]);
    }
    /**
     * @Route("/",name="home")
     */
    public function home(){
        //notre page d'accueil
        return $this->render('blog/home.html.twig');
    }

    /**
     * @Route ("/blog/{id}", name="blog_show")
     */
    public function show(Article $article){//donnera l'article avec l'id données dans le path('blog_show',{'id': article.id })
        //la fonction show permet d'afficher un article en entier
        return $this->render("blog/show.html.twig",[
            'article'=>$article
        ]);
    }
}
