<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\AddressBookItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AddressBookItem>
 *
 * @method AddressBookItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method AddressBookItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method AddressBookItem[]    findAll()
 * @method AddressBookItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AddressBookItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AddressBookItem::class);
    }

}
