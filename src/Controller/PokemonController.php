<?php

namespace App\Controller;

use App\Repository\PokemonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PokemonController extends ApiController
{
    /**
     * @Route("/pokemons", name="pokemons", methods="GET")
     * @param PokemonRepository $pokemonRepository
     * @return Symfony\Component\HttpFoundation\JsonResponse
     */
    public function index(PokemonRepository $pokemonRepository)
    {
        $pokemons = $pokemonRepository->all();
        return $this->respond($pokemons);
    }

    /**
     * @Route("/pokemons", name="pokemonCreate", methods="POST")
     * @param Request $request
     * @param PokemonRepository $pokemonRepository
     * @param EntityManagerInterface $em
     * @return Symfony\Component\HttpFoundation\JsonResponse
     */
    public function create(Request $request, PokemonRepository $pokemonRepository, EntityManagerInterface $em)
    {
        $request = $this->transformJsonBody($request);

        if (! $request) {
            return $this->respondValidationError('Please provide a valid request!');
        }

        if (! $request->get('name')) {
            return $this->respondValidationError('Please provide a title!');
        }

        // persist the new movie
        $pokemon = new Pokemon;
        $pokemon->setName($request->get('name'));
        $pokemon->setIntelligence(0);
        $pokemon->setStrength(0);
        $em->persist($pokemon);
        $em->flush();

        return $this->respondCreated($pokemonRepository->transform($pokemon));
    }
    /**
     * @Route("/pokemons/{id}/intelligence", methods="POST")
     * @param $id
     * @param EntityManagerInterface $em
     * @param PokemonRepository $pokemonRepository
     */
    public function increaseIntelligence($id, EntityManagerInterface $em, PokemonRepository $pokemonRepository)
    {
        $pokemon = $pokemonRepository->find($id);

        if (! $pokemon) {
            return $this->respondNotFound();
        }

        $pokemon->setIntelligence($pokemon->getIntelligence() + 1);
        $em->persist($pokemon);
        $em->flush();

        return $this->respond([
            'intelligence' => $pokemon->getIntelligence()
        ]);
    }

    public function increaseStrength($id, EntityManagerInterface $em, PokemonRepository $pokemonRepository)
    {
        $pokemon = $pokemonRepository->find($id);

        if (! $pokemon) {
            return $this->respondNotFound();
        }

        $pokemon->setStrength($pokemon->getStrength() + 1);
        $em->persist($pokemon);
        $em->flush();

        return $this->respond([
            'intelligence' => $pokemon->getStrength()
        ]);
    }
}
