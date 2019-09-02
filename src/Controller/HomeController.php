<?php

namespace App\Controller;

use App\Entity\Meeting;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    /**
     * @Route("/home", name="home")
     */
    public function index(Request $request, PaginatorInterface $paginator)
    {
        $repository = $this->getDoctrine()->getRepository(Meeting::class);

        $Meetings = $repository->findAll();
        $paginator  = $this->get('knp_paginator');
        $rndMeeting = $paginator->paginate(
            $Meetings,
            $request->query->getInt('page',1),10);

        return $this->render('home/index.html.twig', [
            "rndMeeting" => $rndMeeting,

        ]);
    }



}
