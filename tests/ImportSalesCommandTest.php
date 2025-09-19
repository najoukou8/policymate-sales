<?php

namespace App\Tests;

use App\Command\ImportSalesCommand;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class ImportSalesCommandTest extends KernelTestCase
{
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
