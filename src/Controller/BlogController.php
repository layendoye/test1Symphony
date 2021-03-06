<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ArticleType;
use App\Form\CommentType;
use App\Entity\Comment;

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
     *@Route("/blog/new", name="blog_create")
     *@Route("/blog/{id}/edit", name="blog_edit")
    */
    public function form(Request $request, ObjectManager $manager,Article $article=null){//$request contiendra les données du formulaire
        if(!$article){
           $article=new Article(); //si je n'est pas d article on cree un article vide car si on modifie il y aura un article (celui donné par Article $article)
        }
        $form=$this->createForm(ArticleType::class,$article);//on appel le formulaire créé via la console et on le lie avec $article

        $form->handleRequest($request);//analyse de la requette (recu apres envoi du formulaire)
        
        if($form->isSubmitted() && $form->isValid()){
            if(!$article->getId()){//si l article n a pas d Id ca veut dire que c est un nouveau on fait ca pour ne pas modifier la date de création lorsqu on modifie un article
               $article->setCreatedAt(new \DateTime()); 
            }
            $manager->persist($article);
            $manager->flush();

            return $this->redirectToRoute('blog_show',['id'=>$article->getId()]);
        }
        return $this->render('blog/create.html.twig',[
            'formArticle'=>$form->createView(),
            'editMode' => ($article->getId() !==null) //si lorsqu on recharge la page pour une premier fois l'id existe editMod=true 
        ]);
    }

    /**
     * @Route ("/blog/{id}", name="blog_show")
     */
    public function show(Article $article, Request $request,ObjectManager $manager){//donnera l'article avec l'id données dans le path('blog_show',{'id': article.id })
                                          //la fonction show permet d'afficher un article en entier
        
        $comment=new Comment();
        
        $form= $this->createForm(CommentType::class,$comment);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $comment->setCreatedAt(new \DateTime())
                    ->setArticle($article);
            $manager->persist($comment);
            $manager->flush();
            return $this->redirectToRoute('blog_show',['id' => $article->getId()]);
        }
        return $this->render("blog/show.html.twig",[
            'article' => $article,
            'commentForm'=> $form->createView()
        ]);
    }

    
}
