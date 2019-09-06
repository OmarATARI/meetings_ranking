<?php


namespace App\Controller;


use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class UserController extends AbstractController
{
    private $em;
    private $userRepo;

    public function __construct(EntityManagerInterface $em, UserRepository $userRepository)
    {
        $this->em = $em;
        $this->userRepo = $userRepository;
    }

    /**
     * @Route("/profile", name="profile_user")
     * @return Response
     */
    public function index()
    {
        $current_user = $this->getUser();

        return $this->render('user/profile.html.twig', array(
            'user' => $current_user
        ));
    }
}