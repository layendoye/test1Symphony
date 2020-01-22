<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;

class ArticlesFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');
        for($i=1;$i<=3;$i++){
            $category=new Category();
            $category->setTitle($faker->sentence())
                     ->setDescription($faker->paragraph());
            $manager->persist($category);
            for($j=1;$j<=mt_rand(4,6);$j++){
                $article=new Article();
                
                $article->setTitle($faker->sentence())
                        ->setContent('<p>'.join($faker->paragraphs(5),'</p><p>').'</p>')
                        ->setImage($faker->imageUrl())
                        ->setCreatedAt($faker->dateTimeBetween('-6 months'))
                        ->setCategory($category);
                $manager->persist($article);//prepare les articles
                
                $now=new \DateTime();
                $interval=$now->diff($article->getCreatedAt());
                $days=$interval->days;
                $minimum='-'.$days.' days';//exemple -100 days
                
                for($k=1;$k<=mt_rand(4,10);$k++){
                    $comment=new Comment();
                    $comment->setAuthor($faker->name)
                            ->setContent('<p>'.join($faker->paragraphs(2),'</p><p>').'</p>')
                            ->setCreatedAt($faker->dateTimeBetween($minimum))
                            ->setArticle($article);
                    $manager->persist($comment);
                }
            }
        }
        $manager->flush();//execute la préparation
    }
}
