<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Tuto;
use App\Repository\TutoRepository;

class TutoController extends AbstractController
{
    #[Route('/tuto/{slug}', name: 'app_tuto_details')]
    public function view(EntityManagerInterface $entityManager, string $slug): Response
    {
        $tuto = $entityManager->getRepository(Tuto::class)->findOneBySlug($slug);

        if(!$tuto){
            return $this->redirectToRoute('app_home');
        }

        return $this->render('tuto/details.html.twig', [
            'tuto' => $tuto
        ]);
    }

    #[Route('/tuto/{id}', name: 'app_tuto')]
    public function index(TutoRepository $productRepository, int $id): Response
    {
        $product = $productRepository->findOneByID($id);

        if(!$product){
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        return $this->render('tuto/index.html.twig', [
            'controller_name' => 'TutoController',
            'name' => $product->getName()
        ]);
    }

    #[Route('/add_tuto', name: 'create_tuto')]
    public function createTuto(EntityManagerInterface $entityManager): Response
    {
        $product = new Tuto();
        $product->setName('unity');
        $product->setSlug('tuto-unity');
        $product->setSlugtitle('lorem ipsum dolor site amet');
        $product->setDescription('lorem ipsum dolor sit amet.');
        $product->setImage('img1.jpeg');
        $product->setVideo('B6eq-1IoQw');
        $product->setLink('google.fr');

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($product);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response('Saved new product with id '.$product->getId());
    }
}
