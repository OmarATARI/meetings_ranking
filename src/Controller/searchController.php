<?php


namespace App\Controller;


use App\Entity\Meeting;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;


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
 /**
  * @Route("/search", name="handleSearch")
  */
    public function handleSearch(Request $request, $_query)
    {
        $em = $this->getDoctrine()->getManager();
        if ($_query)
        {
            $data = $em->getRepository(Meeting::class)->findByName($_query);
        } else {
            $data = $em->getRepository(Meeting::class)->findAll();
        }
        // iterate over all the resuls and 'inject' the image inside
        for ($index = 0; $index < count($data); $index++)
        {
            $object = $data[$index];
            // http://via.placeholder.com/35/0000FF/ffffff
            $object->setImage("http://via.placeholder.com/35/0000FF/ffffff");
        }
        // setting up the serializer
        $normalizers = [
            new ObjectNormalizer()
        ];
        $encoders =  [
            new JsonEncoder()
        ];
        $serializer = new Serializer($normalizers, $encoders);
        $data = $serializer->serialize($data, 'json');
        return new JsonResponse($data, 200, [], true);
        
 }

}