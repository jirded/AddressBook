<?php

namespace App\Test\Controller;

use App\Entity\AddressBookItem;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AddressBookItemControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/address/book/item/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(AddressBookItem::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('AddressBookItem index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'address_book_item[name]' => 'Testing',
            'address_book_item[surname]' => 'Testing',
            'address_book_item[phone]' => 'Testing',
            'address_book_item[email]' => 'Testing',
            'address_book_item[note]' => 'Testing',
            'address_book_item[createdAt]' => 'Testing',
            'address_book_item[updatedAt]' => 'Testing',
            'address_book_item[deletedAt]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new AddressBookItem();
        $fixture->setName('My Title');
        $fixture->setSurname('My Title');
        $fixture->setPhone('My Title');
        $fixture->setEmail('My Title');
        $fixture->setNote('My Title');
        $fixture->setCreatedAt('My Title');
        $fixture->setUpdatedAt('My Title');
        $fixture->setDeletedAt('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('AddressBookItem');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new AddressBookItem();
        $fixture->setName('Value');
        $fixture->setSurname('Value');
        $fixture->setPhone('Value');
        $fixture->setEmail('Value');
        $fixture->setNote('Value');
        $fixture->setCreatedAt('Value');
        $fixture->setUpdatedAt('Value');
        $fixture->setDeletedAt('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'address_book_item[name]' => 'Something New',
            'address_book_item[surname]' => 'Something New',
            'address_book_item[phone]' => 'Something New',
            'address_book_item[email]' => 'Something New',
            'address_book_item[note]' => 'Something New',
            'address_book_item[createdAt]' => 'Something New',
            'address_book_item[updatedAt]' => 'Something New',
            'address_book_item[deletedAt]' => 'Something New',
        ]);

        self::assertResponseRedirects('/address/book/item/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getName());
        self::assertSame('Something New', $fixture[0]->getSurname());
        self::assertSame('Something New', $fixture[0]->getPhone());
        self::assertSame('Something New', $fixture[0]->getEmail());
        self::assertSame('Something New', $fixture[0]->getNote());
        self::assertSame('Something New', $fixture[0]->getCreatedAt());
        self::assertSame('Something New', $fixture[0]->getUpdatedAt());
        self::assertSame('Something New', $fixture[0]->getDeletedAt());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new AddressBookItem();
        $fixture->setName('Value');
        $fixture->setSurname('Value');
        $fixture->setPhone('Value');
        $fixture->setEmail('Value');
        $fixture->setNote('Value');
        $fixture->setCreatedAt('Value');
        $fixture->setUpdatedAt('Value');
        $fixture->setDeletedAt('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/address/book/item/');
        self::assertSame(0, $this->repository->count([]));
    }
}
