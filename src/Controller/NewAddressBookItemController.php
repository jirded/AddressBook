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

#[Route('/new', name: 'app_address_book_item_new', methods: ['GET', 'POST'])]
class NewAddressBookItemController extends AbstractController
{
    public function __invoke(Request $request, EntityManagerInterface $entityManager): Response
    {
        $addressBookItem = new AddressBookItem();
        $form = $this->createForm(AddressBookItemType::class, $addressBookItem);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $addressBookItem->setCreatedAt(new \DateTimeImmutable());
            $entityManager->persist($addressBookItem);
            $entityManager->flush();

            return $this->redirectToRoute('app_address_book_item_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('address_book_item/new.html.twig', [
            'address_book_item' => $addressBookItem,
            'form' => $form->createView(),
        ]);
    }

}
