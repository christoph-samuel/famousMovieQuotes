<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Entity\Quote;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class QuoteController extends AbstractController
{
    /**
     * @Route("/deleteQuote/{id<\d+>}", name="deleteQuote")
     */
    public function deleteQuote(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $quote = $this->getDoctrine()->getRepository(Quote::class)->find($id);

        $entityManager->remove($quote);
        $entityManager->flush();

        return $this->redirectToRoute('listMovies');
    }

    /**
     * @Route("/newQuote", name="newQuote")
     */
    public function newQuote(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $movies = $entityManager->getRepository(Movie::class)->findAll();

        return $this->render('famousMovieQuotes/newQuote.html.twig', [
            'movies' => $movies
        ]);
    }

    /**
     * @Route("/addQuote", name="addQuote")
     */
    public function addQuote(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $movies = $entityManager->getRepository(Movie::class)->findAll();

        $newQuote = $request->get('quote');
        $newCharacter = $request->get('character');
        $movie = $request->get('movie');

        if (!(strlen($newQuote) >= 3)) {
            return $this->render('famousMovieQuotes/newQuote.html.twig', [
                'movies' => $movies,
                'error' => "Quote has to contain more than 3 characters!"
            ]);
        }

        $entityManager = $this->getDoctrine()->getManager();

        $movie = $entityManager->getRepository(Movie::class)->findOneBy(['id' => $movie]);

        $quote = new Quote();
        $quote->setQuote($newQuote);
        $quote->setCharacter($newCharacter);
        $quote->setMovie($movie);

        $entityManager->persist($quote);
        $entityManager->flush();

        return $this->redirectToRoute('listMovies');
    }

    /**
     * @Route("/detailQuote/{id<\d+>}", name="detailQuote")
     */
    public function detailQuote(int $id): Response
    {
        $quote = $this->getDoctrine()->getRepository(Quote::class)->findOneBy(['id' => $id]);

        return $this->render('famousMovieQuotes/detailQuote.html.twig', [
            'quote' => $quote
        ]);
    }

    /**
     * @Route("/editQuote/{id<\d+>}", name="editQuote")
     */
    public function editQuote(Request $request, int $id): Response
    {
        $quote = $this->getDoctrine()->getRepository(Quote::class)->findOneBy(['id' => $id]);

        $newQuote = $request->get('quote');
        $newCharacter = $request->get('character');

        if (!(strlen($newQuote) >= 3)) {
            return $this->render('famousMovieQuotes/detailQuote.html.twig', [
                'quote' => $quote,
                'error' => "Quote has to contain more than 3 characters!"
            ]);
        }

        $entityManager = $this->getDoctrine()->getManager();

        $quote = $entityManager->getRepository(Quote::class)->findOneBy(['id' => $id]);

        $quote->setQuote($newQuote);
        $quote->setCharacter($newCharacter);

        $entityManager->flush();

        return $this->redirectToRoute("listMovies");
    }
}
