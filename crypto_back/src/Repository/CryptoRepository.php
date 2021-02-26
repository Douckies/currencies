<?php

namespace App\Repository;

use App\Entity\Crypto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Crypto|null find($id, $lockMode = null, $lockVersion = null)
 * @method Crypto|null findOneBy(array $criteria, array $orderBy = null)
 * @method Crypto[]    findAll()
 * @method Crypto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CryptoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Crypto::class);
    }

    // /**
    //  * @return Crypto[] Returns an array of Crypto objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Crypto
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findAllCrypto()
    {
        $querySelect = $this->createQueryBuilder('q');
        $allCrypo = $querySelect
            ->from('App\Entity\Crypto', 'c')
            ->getQuery()
            ->getResult();

        return $allCrypo;
    }

    public function transform(Crypto $crypto)
    {
        return [
                'plateforme' => (string) $crypto->getPlateforme(),
                'nom' => (string) $crypto->getNom(),
                'qtt' => (string) $crypto->getQtt(),
                'investissement' => (string) $crypto->getInvestissement(),
        ];
    }

    public function transformAll()
    {
        $crypto = $this->findAll();
        $cryptoArray = [];

        foreach ($crypto as $crypto) {
            $cryptoArray[] = $this->transform($crypto);
        }

        return $cryptoArray;
    }
}
