<?php

namespace App\Tests;

use App\Command\ImportSalesCommand;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class ImportSalesCommandTest extends KernelTestCase
{
    // For your test to pass, you need to refresh the database schema before each test.
    // You can do this by using the SchemaTool from Doctrine or install one of the many libraries that help with this.
    // Otherwise the test will work the first time "Imported: 1, Skipped: 1" but fail the next time (Imported: 0).
    protected function setUp(): void
    {
        self::bootKernel();
        
        $entityManager = self::$container->get('doctrine')->getManager();
        $schemaTool = new \Doctrine\ORM\Tools\SchemaTool($entityManager);
        $schemaTool->dropSchema($entityManager->getMetadataFactory()->getAllMetadata());
        $schemaTool->createSchema($entityManager->getMetadataFactory()->getAllMetadata());
    }

    public function testImportValidCsv(): void
    {
        $kernel = self::bootKernel();
        $container = self::$container;

        $command=$container->get(ImportSalesCommand::class);
        $commandTester= new CommandTester($command);
        $filePath = __DIR__ . '/sample.csv';
        file_put_contents($filePath, <<<CSV
order_id,order_date,customer_email,product_sku,product_name,unit_price,quantity
1,2025-08-01,test@example.com,SKU-1,Widget A,10.00,2
2,2025-08-02,test2@example.com,SKU-2,Widget B,15.00,-3
CSV
        );

        $commandTester->execute([
            'file' => $filePath,
        ]);
        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Imported: 1, Skipped: 1', $output);
        unlink($filePath);

    }
}
