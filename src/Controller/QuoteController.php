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
     * @Route("/quote", name="quote")
     */
    public function index(): Response
    {
        // you can fetch the EntityManager via $this->getDoctrine()
        // or you can add an argument to the action: createQuote(EntityManagerInterface $entityManager)
        $entityManager = $this->getDoctrine()->getManager();

        $quote = new Quote();
        $quote->setQuote('Nicht wenn ich schneller bin!');
        $quote->setCharacter('Dragan Bond');
        $quote->setMovie($this->getDoctrine()
            ->getRepository(Movie::class)
            ->find(2));

        // tell Doctrine you want to (eventually) save the Quote (no queries yet)
        $entityManager->persist($quote);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response('Saved new quote with id '.$quote->getId());
    }
}
