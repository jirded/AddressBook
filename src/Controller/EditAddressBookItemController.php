<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\AddressBookItem;
use App\Form\AddressBookItemType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/{slug}', name: 'app_address_book_item_edit', requirements: ['slug' => '(\d+)-([a-zA-Z-]+)-([a-zA-Z-]+)'], methods: ['GET', 'POST'])]
class EditAddressBookItemController extends AbstractController
{
    public function __invoke(Request $request, AddressBookItem $addressBookItem, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AddressBookItemType::class, $addressBookItem);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $addressBookItem->setUpdatedAt(new \DateTimeImmutable());
            $entityManager->flush();

            return $this->redirectToRoute('app_address_book_item_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('address_book_item/edit.html.twig', [
            'address_book_item' => $addressBookItem,
            'form' => $form->createView(),
        ]);
    }
}
