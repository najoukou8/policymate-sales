<?php

namespace App\Command;

use App\Entity\ImportError;
use App\Entity\Sale;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ImportSalesCommand extends Command
{
    protected static $defaultName = 'app:import-sales';
    protected static $defaultDescription = 'Import sales from a CSV file into the database';
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('file', InputArgument::REQUIRED, 'path to csv file')
        ;
    }

    // You should absolutely use transactions when doing bulk inserts/updates.
    // This will ensure that either all your changes are applied, or none at all in case of error.
    // So you don't get partial updates which are often hard to recover from.

    // As you very well mentioned, the algorithm is not optimal for large files.

    // Try to break down your methods into smaller ones.
    // A good practice is usually < 20 lines per method.
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $file = $input->getArgument('file');

        if (!file_exists($file)) {
            $io->error("file not found");
            return Command::FAILURE;
        }
        $handle=fopen($file, 'r');
        if($handle=== false){
            $io->error("unable to open file");
            return Command::FAILURE;
        }
        fgetcsv($handle);
        $rowNumber=1;
        $imported=0;
        $skipped=0;
        while (($row = fgetcsv($handle)) !== false) {
            $rowNumber++;
            [$orderId, $orderDate, $customerEmail, $productSku, $productName, $unitPrice, $quantity] = $row;
            $reason = null;
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $orderDate)) {
                $reason = "Invalid date format";
            } elseif (empty($customerEmail)) {
                $reason = "Missing customer email";
            } elseif ($unitPrice <= 0) {
                $reason = "Negative or zero unit price";
            } elseif ($quantity <= 0) {
                $reason = "Negative or zero quantity";
            }
            if ($reason) {
                $skipped++;
                $this->logError($rowNumber, $reason, $row);
                continue;
            }

            $existing = $this->em->getRepository(Sale::class)
                ->findOneBy(['orderId' => $orderId]);

            if ($existing) {
                $existing->setUnitPrice($unitPrice);
                $existing->setQuantity($quantity);
                $this->em->persist($existing);
                continue;
            }


            $sale = new Sale();
            $sale->setOrderId($orderId);
            $sale->setOrderDate(new \DateTime($orderDate));
            $sale->setCustomerEmail($customerEmail);
            $sale->setProductSku($productSku);
            $sale->setProductName($productName);
            $sale->setUnitPrice($unitPrice);
            $sale->setQuantity($quantity);

            $this->em->persist($sale);
            $imported++;
        }

        fclose($handle);

        $this->em->flush();

        $io->success("Import completed. Imported: $imported, Skipped: $skipped");

        return Command::SUCCESS;
    }

    private function logError(int $row, string $reason, array $data): void
    {

        $error = new ImportError();
        $error->setRowNumber($row);
        $error->setReason($reason);
        $error->setRowData(json_encode($data));

        $this->em->persist($error);
    }
}
