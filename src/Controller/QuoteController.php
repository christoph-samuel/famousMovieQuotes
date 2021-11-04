<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Entity\Quote;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuoteController extends AbstractController
{
    /**
     * @Route("/deleteQuote/{id<\d+>}", name="deleteQuote")
     */
    public function deleteQuote(int $id): Response
    {
        // you can fetch the EntityManager via $this->getDoctrine()
        // or you can add an argument to the action: createQuote(EntityManagerInterface $entityManager)
        $entityManager = $this->getDoctrine()->getManager();

        $quote = $this->getDoctrine()->getRepository(Quote::class)->find($id);

        // tell Doctrine you want to (eventually) save the Quote (no queries yet)
        $entityManager->remove($quote);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return $this->redirectToRoute('listMovies');
    }
}
