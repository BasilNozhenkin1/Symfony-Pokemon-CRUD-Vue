<?php

namespace App\Repository;

use App\Entity\Pokemon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Pokemon|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pokemon|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pokemon[]    findAll()
 * @method Pokemon[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PokemonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pokemon::class);
    }

    /**
     * @param Pokemon $pokemon
     */
    public function transform(Pokemon $pokemon) {
        return [
            'pokemonId' => $pokemon->getId(),
            'pokemonName' => $pokemon->getName(),
            'pokemonIntelligence' => $pokemon->getIntelligence(),
            'pokemonStrength'  => $pokemon->getStrength()
        ];
    }
    public function all() {
        $pokemons = $this->findAll();
        $pokemonsData = [];

        foreach ($pokemons as $pokemon) {
            $pokemonsData[] = $this->transform($pokemon);
        }

        return $pokemonsData;
    }
}
