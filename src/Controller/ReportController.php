<?php

namespace App\Controller;

use App\Entity\Sale;
use App\Repository\SaleRepository;
use SebastianBergmann\CodeCoverage\Report\Xml\Report;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use function Deployer\error;

class ReportController extends AbstractController
{
 /**
 * @Route("/api/report/top-products", name="api_top_products", methods={"GET"})
 */
    public function getTopProduct(Request $request, SaleRepository $saleRepository): JsonResponse
    {
        $limit= $request->query->get('limit', 3);
        if( !ctype_digit((string) $limit) || (int) $limit<=0){
            return new JsonResponse([
                'error' => 'limit must be a positive integer',
            ]);
        }
        $results = $saleRepository->findTopProducts($limit);

        return $this->json($results);
    }
    /**
     * Attention of typo in the route name!
     * @Route("/api/report/monthly-revenue", name="api_monthly_revenue", methods={"GET"})
     */
   public function monthlyRevenue(Request $request, SaleRepository $saleRepository): JsonResponse {
        $year= $request->query->get('year', date('Y'));
       if( !ctype_digit((string) $year) || (int) $year<=0){
           return new JsonResponse([
               'error' => 'the year must be a positive integer',
           ]);
       }
        $results = $saleRepository->findMonthlyRevenue($year);
        return $this->json($results);
   }
    /**
     * @Route("/api/report/top-customers", name="api_top_customers", methods={"GET"})
     */
    public function topCustomers(Request $request, SaleRepository $saleRepository): JsonResponse {
        $limit= $request->query->get('limit',3);
        if( !ctype_digit((string) $limit) || (int) $limit<=0){
            return new JsonResponse([
                'error' => 'limit must be a positive integer',
            ]);
        }
        $results = $saleRepository->findTopCustomers($limit);
        return $this->json($results);
    }

}
