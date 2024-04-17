<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\AddressBookItem;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/{slug}/delete', name: 'app_address_book_item_delete', methods: ['GET'])]
class DeleteAddressBookItemController extends AbstractController
{
    public function __invoke(Request $request, AddressBookItem $addressBookItem, EntityManagerInterface $entityManager): Response
    {
        $addressBookItem->setDeletedAt(new \DateTimeImmutable());
        $entityManager->flush();

        return $this->redirectToRoute('app_address_book_item_index', [], Response::HTTP_SEE_OTHER);
    }
}
