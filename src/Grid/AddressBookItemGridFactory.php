<?php

declare(strict_types=1);

namespace App\Grid;

use App\Entity\AddressBookItem;
use App\Repository\AddressBookItemRepository;
use Doctrine\ORM\QueryBuilder;
use Kibatic\DatagridBundle\Grid\Grid;
use Kibatic\DatagridBundle\Grid\GridBuilder;
use Kibatic\DatagridBundle\Grid\Template;
use Kibatic\DatagridBundle\Grid\Theme;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use function Symfony\Component\String\u;

final readonly class AddressBookItemGridFactory
{
    public function __construct(
        private AddressBookItemRepository $addressBookItemRepository,
        private GridBuilder $gridBuilder,
        private TranslatorInterface $translator,
        private RouterInterface $router
    ) {

    }

    public function create(Request $request): Grid
    {
        $queryBuilder = $this->addressBookItemRepository->createQueryBuilder('abi')
            ->where('abi.deletedAt IS NULL')
            ->orderBy('abi.createdAt', 'DESC');

        return $this->gridBuilder
            ->initialize($request, $queryBuilder)
            ->setTheme(Theme::BOOTSTRAP5)
            ->setItemsPerPage(10)
            ->addColumn($this->translator->trans('Name'), 'name', sortable: 'abi.name')
            ->addColumn($this->translator->trans('Surname'), 'surname', sortable: 'abi.surname')
            ->addColumn($this->translator->trans('Phone'), 'phone')
            ->addColumn($this->translator->trans('Email'), 'email')
            ->addColumn(
                $this->translator->trans('Note'),
                function (AddressBookItem $addressBookItem) {
                    return $addressBookItem->getNote() !== null
                        ? sprintf(
                            '%s %s',
                            u($addressBookItem->getNote())->truncate(50, '...'),
                            '<a href="#" data-bs-toggle="modal" data-bs-target="#note" title="' . $addressBookItem->getNote() . '">' . $this->translator->trans('more') . '</a>'
                        ) . $this->getNotePopUp($addressBookItem->getNote()) : '-';
                },
                templateParameters: ['escape' => false]
            )
            ->addColumn(
                $this->translator->trans('Created at'),
                'createdAt',
                Template::DATETIME,
                templateParameters: ['format' => 'd.m.Y H:i:s'],
                sortable: 'abi.createdAt'
            )
            ->addColumn(
                $this->translator->trans('Updated at'),
                'updatedAt',
                Template::DATETIME,
                templateParameters: ['format' => 'd.m.Y H:i:s'],
                sortable: 'abi.updatedAt'
            )
            ->addColumn(
                $this->translator->trans('Actions'),
                fn(AddressBookItem $addressBookItem) => [
                    [
                        'name' => $this->translator->trans('Edit'),
                        'url' => $this->router->generate('app_address_book_item_edit', ['slug' => $addressBookItem->getSlug()]),
                        'icon_class' => 'fa fa-pencil',
                    ],
                    [
                        'url' => $this->router->generate('app_address_book_item_delete', ['slug' => $addressBookItem->getSlug()]),
                        'name' => $this->translator->trans('Delete'),
                        'btn_type' => 'danger',
                        'icon_class' => 'fa fa-trash',
                    ],
                ],
                Template::ACTIONS,
            )
            ->getGrid();
    }

    private function getNotePopUp(string $note): string
    {
        $closeBtn = $this->translator->trans('Close');
        return
<<<HTML
<div class="modal fade" id="note" tabindex="-1" aria-labelledby="noteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
        $note
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">$closeBtn</button>
      </div>
    </div>
  </div>
</div>
HTML;
    }
}
