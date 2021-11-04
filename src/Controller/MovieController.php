<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Entity\Quote;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{
    /**
     * @Route("/createMovie", name="createMovie")
     */
    public function createMovie(): Response
    {
        // you can fetch the EntityManager via $this->getDoctrine()
        // or you can add an argument to the action: createMovie(EntityManagerInterface $entityManager)
        $entityManager = $this->getDoctrine()->getManager();

        $movie = new Movie();
        $movie->setName('Dragan Bond');
        $movie->setYear(2018);

        // tell Doctrine you want to (eventually) save the Movie (no queries yet)
        $entityManager->persist($movie);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response('Saved new movie with id ' . $movie->getId());
    }

    /**
     * @Route("/movies", name="listMovies")
     */
    public function listMovies(): Response
    {
        // you can fetch the EntityManager via $this->getDoctrine()
        // or you can add an argument to the action: createMovie(EntityManagerInterface $entityManager)
        $movies = $this->getDoctrine()->getRepository(Movie::class)->findAll();
        $quotes = $this->getDoctrine()->getRepository(Quote::class)->findAll();

        asort($movies);

        return $this->render('famousMovieQuotes/movies.html.twig', [
            'movies' => $movies,
            'quotes' => $quotes
        ]);
    }
}
