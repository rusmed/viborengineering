<?php

namespace App\Repository;

use App\Entity\WorkRequest;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @method WorkRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method WorkRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method WorkRequest[]    findAll()
 * @method WorkRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WorkRequestRepository extends BaseRepository
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct(
            $entityManager,
            $entityManager->getClassMetadata(WorkRequest::class)
        );
    }
}
