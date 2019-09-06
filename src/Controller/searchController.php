<?php


namespace App\Controller;


use App\Entity\Meeting;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;



class searchController extends AbstractController
{
    /**
     * @Route ("/search")
     */
    public function searchBar(Request $request)
    {

                $form = $this->createFormBuilder(null)
            ->add('titreDuMeeting', TextType::class)
            ->add('search',SubmitType::class
                )
            ->getForm();




            return $this->render('search/index.html.twig',[
                'form'=> $form->createView()
            ]);
 }


}