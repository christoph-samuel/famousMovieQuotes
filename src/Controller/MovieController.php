<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Entity\Quote;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{
    /**
     * @Route("/createMovie", name="createMovie")
     */
    public function createMovie(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $movie = new Movie();
        $movie->setName('Dragan Bond');
        $movie->setYear(2018);

        $entityManager->persist($movie);
        $entityManager->flush();

        return new Response('Saved new movie with id ' . $movie->getId());
    }

    /**
     * @Route("/", name="listMovies")
     */
    public function listMovies(Request $request): Response
    {
        $filteredBy = null;

        if (!$search = $request->get('search')) {
            $movies = $this->getDoctrine()->getRepository(Movie::class)->findAll();
        } else {
            if ($result = $this->getDoctrine()->getRepository(Movie::class)->findByName($search)) {
                $movies = $result;
            } else {
                $movies = "No Results available!";
            }
            $filteredBy = $search;
        }

        $quotes = $this->getDoctrine()->getRepository(Quote::class)->findAll();

        try {
            asort($movies);
        } catch (\Exception $ex) {
        }

        return $this->render('famousMovieQuotes/movies.html.twig', [
            'movies' => $movies,
            'quotes' => $quotes,
            'filteredBy' => $filteredBy
        ]);
    }

    /**
     * @Route("/newMovie", name="newMovie")
     */
    public function newMovie(): Response
    {
        return $this->render('famousMovieQuotes/newMovie.html.twig');
    }

    /**
     * @Route("/addMovie", name="addMovie")
     */
    public function addMovie(Request $request): Response
    {
        $newName = $request->get('name');
        $newYear = $request->get('year');

        if (!(strlen($newName) >= 1)) {
            return $this->render('famousMovieQuotes/newMovie.html.twig', [
                'error' => "Name has to contain at least 1 character!"
            ]);
        }
        if (!($newYear <= date("Y"))) {
            return $this->render('famousMovieQuotes/newMovie.html.twig', [
                'error' => "Year can't be in the future!"
            ]);
        }
        if (!($newYear >= 1878)) {
            return $this->render('famousMovieQuotes/newMovie.html.twig', [
                'error' => "In this Year there wasn't even the movie \"The Horse In Motion\" released!"
            ]);
        }

        $entityManager = $this->getDoctrine()->getManager();

        $movie = new Movie();
        $movie->setName($newName);
        $movie->setYear($newYear);

        $entityManager->persist($movie);
        $entityManager->flush();

        return $this->redirectToRoute('listMovies');
    }
}
