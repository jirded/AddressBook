<?php

declare(strict_types=1);

namespace App\Controller;

use App\Grid\AddressBookItemGridFactory;
use App\Repository\AddressBookItemRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/', name: 'app_address_book_item_index', methods: ['GET'])]
class ListAddressBookItemController extends AbstractController
{
    public function __invoke(
        AddressBookItemRepository $addressBookItemRepository,
        Request $request,
        AddressBookItemGridFactory $addressBookItemGridFactory
    ): Response {
        $grid = $addressBookItemGridFactory->create($request);

        return $this->render('address_book_item/index.html.twig', [
            'address_book_items' => $addressBookItemRepository->findAll(),
            'grid' => $grid,
        ]);
    }

}
