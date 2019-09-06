<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
     * @Route("/admin/user", name="admin_user")
     */
    public function index(Request $request,PaginatorInterface $paginator)
    {
        $repository = $this->getDoctrine()->getRepository(User::class);
        $Users = $repository->findAll();
        $allUsers = $paginator->paginate(
            $Users,
            $request->query->getInt('page', 1),
            3
        );
        return $this->render('admin_user/index.html.twig', [
            'allUsers' => $allUsers,
            'controller_name' => 'UserController',
        ]);
    }


    /**
     * @Route("admin/{id}", name="admin_user_show", methods={"GET"})
     */
    public function show(User $User): Response
    {
        return $this->render('admin_user/show.html.twig', [
            'User' => $User,
        ]);
    }

    /**
     * @Route(
     *     "/admin/users/update/{id}",
     *     name="admin_user_updateUser",
     *     requirements={"id":"\d+"}
     * )
     *
     * @param User $user
     * @param Request $request
     * @return Response
     */
    public function updateUser(
        User $user,
        Request $request
    ): Response
    {
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $user = $form->getData();





            $this->em->persist($user);
            $this->em->flush();

            $this->addFlash("success", "Modifications enregistrées");
        }


        return $this->render(
            "admin_user/update_user.html.twig",
            array(
                "form" => $form->createView(),
                "user" => $user
            )
        );
    }
    /**
     * @Route(
     *     "/admin/users/remove/{id}",
     *     name="admin_user_removeUser",
     *     requirements={"id":"\d+"}
     * )
     * @param  $user
     * @return Response
     */
    public function removeUser(User $user): Response
    {
        $this->em->remove($user);
        $this->em->flush();

        $this->addFlash("success", "L\'utilisateur a bien été supprimé");

        return $this->redirectToRoute("admin_user");
    }
}
