<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\RegistrationType;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{//aller dans config -> packages -> packages  -> Security.yaml
    /**
     * @Route("/inscription", name="security_registration")
     */
    public function registration(Request $request,ObjectManager $manager,UserPasswordEncoderInterface $encoder)
    {//inscription
        $user=new User();
        $form=$this->createForm(RegistrationType::class,$user);//user rempli

        $form->handleRequest($request);//analyse de la requette (recu apres envoi du formulaire)
        if($form->isSubmitted() && $form->isValid()){
            $hash=$encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            $manager->persist($user);
            $manager->flush();
            return $this->redirectToRoute('security_login');
        }
        return $this->render('security/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /**
     *@Route("/connexion", name="security_login")
     */
    public function login(){ //gerer dans config packages security.yaml
        return $this->render('security/login.html.twig');
    }
     /**
     *@Route("/deconnexion", name="security_logout")
     */
    public function logout(){}
}
