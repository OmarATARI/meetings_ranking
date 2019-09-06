<?php

namespace App\Controller\Admin;

use App\Entity\Meeting;
use App\Form\MeetingType;
use App\Repository\MeetingRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class MeetingController extends AbstractController
{
    private $em;
    private $meetingRepo;
    private $userRepo;

    /**
     * MeetingController constructor.
     * @param EntityManagerInterface $em
     * @param MeetingRepository $meetingRepo
     * @param UserRepository $userRepo
     */
    public function __construct(EntityManagerInterface $em, MeetingRepository $meetingRepo, UserRepository $userRepo)
    {
        $this->em = $em;
        $this->meetingRepo = $meetingRepo;
        $this->userRepo = $userRepo;
    }


    /**
     * @Route("/admin/meeting/", name="admin_meeting")
     */
    public function index(MeetingRepository $meetingRepository): Response
    {
        return $this->render('admin_meeting/index.html.twig', [
            'meetings' => $meetingRepository->findBy(['user' => $this->getUser()->getId()]),
        ]);
    }

    /**
     * @Route("admin/meeting/{id}", name="admin_meeting_show", methods={"GET"})
     */
    public function show(Meeting $meeting): Response
    {
        return $this->render('meeting/show.html.twig', [
            'meeting' => $meeting,
        ]);
    }
    /**
     * @Route("/admin/meeting/{id}/edit", name="admin_meeting_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Meeting $meeting): Response
    {
        $form = $this->createForm(MeetingType::class, $meeting);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_meeting');
        }

        return $this->render('admin_meeting/edit.html.twig', [
            'meeting' => $meeting,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/meeting/{id}", name="admin_meeting_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Meeting $meeting): Response
    {
        if ($this->isCsrfTokenValid('delete'.$meeting->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($meeting);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_meeting');
    }

}
