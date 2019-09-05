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
     * @Route("/", name="home")
     */
    public function index(Request $request, PaginatorInterface $paginator)
    {
        $repository = $this->getDoctrine()->getRepository(Meeting::class);
        $Meetings = $repository->findAll();
        $allMeetings = $paginator->paginate(
           $Meetings,
           $request->query->getInt('page', 1),
           3
        );

        return $this->render('home/index.html.twig', [
                'allMeetings' => $allMeetings,
                'current_menu' => 'home'
        ]);
    }
}
