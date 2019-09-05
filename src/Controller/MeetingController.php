<?php

namespace App\Controller;

use App\Entity\Meeting;
use App\Form\MeetingType;
use App\Repository\MeetingRepository;
use App\Repository\RankingRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user/meetings")
 */
class MeetingController extends AbstractController
{
    private $em;
    private $meetingRepo;
    private $ranking;

    /**
     * MeetingController constructor.
     * @param EntityManagerInterface $em
     * @param MeetingRepository $meetingRepo
     * @param UserRepository $userRepo
     */
    public function __construct(EntityManagerInterface $em, MeetingRepository $meetingRepo, RankingRepository $ranking)
    {
        $this->em = $em;
        $this->meetingRepo = $meetingRepo;
        $this->ranking = $ranking;
    }

    /**
     * @Route("/", name="meeting_index", methods={"GET"})
     * @param MeetingRepository $meetingRepository
     * @return Response
     */
    public function index(MeetingRepository $meetingRepository): Response
    {
        return $this->render('user/meeting/index.html.twig', [
            'meetings' => $meetingRepository->findBy(['user' => $this->getUser()->getId()]),
        ]);
    }

    /**
     * @Route("/new", name="meeting_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $meeting = new Meeting();
        $meeting->setUser($this->getUser());

        $form = $this->createForm(MeetingType::class, $meeting);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($meeting);
            $entityManager->flush();

            return $this->redirectToRoute('meeting_index');
        }

        return $this->render('user/meeting/new.html.twig', [
            'meeting' => $meeting,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="meeting_show", methods={"GET"})
     */
    public function show(Meeting $meeting): Response
    {

        $user = $this->getUser();
        $ratings = $this->ranking->findBy([
            'meeting'=> $meeting->getId()
        ]);


        // --------------------  Calculate average rate
        $sum_ratings = 0;
        foreach($ratings as $rating)
        {
            $sum_ratings = $sum_ratings + $rating->getValue();
        }
        $star_rating = $sum_ratings/count($ratings);


            // VIEW RATED MEETING FOR AN USER

/*        if(!empty($this->ranking->findOneBy(['userRank' => $user->getId(), 'meeting'=> $meeting->getId()])))
        {
            $star_rating = $this->ranking->findOneBy([
                'userRank' => $user->getId(),
                'meeting'=> $meeting->getId()
            ])->getValue();
        }*/
        return $this->render('user/meeting/show.html.twig', [
            'meeting' => $meeting,
            'star_rating' => $star_rating,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="meeting_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Meeting $meeting): Response
    {
        $form = $this->createForm(MeetingType::class, $meeting);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('meeting_index');
        }

        return $this->render('user/meeting/edit.html.twig', [
            'meeting' => $meeting,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="meeting_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Meeting $meeting): Response
    {
        if ($this->isCsrfTokenValid('delete'.$meeting->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($meeting);
            $entityManager->flush();
        }

        return $this->redirectToRoute('meeting_index');
    }
}
