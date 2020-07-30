<?php
declare(strict_types = 1);

namespace App\Controller\API\V1;

use App\Converter\MarsClockConverter;
use App\Input\ParamReader;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ConvertControllerV1
{
    /**
     * @Route("/api/v1/convert", methods={"POST"})
     */
    public function convert(Request $request): JsonResponse
    {
        $response = new JsonResponse();
        
        try {
            $reader = new ParamReader($request);
            $converter = new MarsClockConverter($reader->getDateTime());

            $response->setData([
                'status' => 200,
                'message' => 'success',
                'data' => [
                    'withoutCom_latency_mar_sol_date' => $converter->getWithoutComLatencyMarsSolDate(),
                    'withCom_latency_mar_sol_date' => $converter->getWithComLatencyMarsSolDate(),
                    'withoutCom_latency_martian_coordinated_time' => $converter->getWithoutComLatencyMartianCoordinatedTime(),
                    'withCom_latency_martian_coordinated_time' => $converter->getWithComLatencyMartianCoordinatedTime(),
                ],
            ]);
        } catch (\Exception $e) {
            $response->setData([
                'status' => 400,
                'message' => $e->getMessage(),
            ]);
        }
        return $response;
    }
}
