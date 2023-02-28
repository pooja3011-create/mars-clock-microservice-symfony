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
    private $response;

    public function __construct()
    {
        $this->response = new JsonResponse();
    }

    /**
     * @Route("/api/v1/convert", methods={"POST"})
     */
    public function convert(Request $request): JsonResponse
    {
        try {
            $reader = new ParamReader($request);
            $converter = new MarsClockConverter($reader->getDateTime());

            $this->setSuccessResponse([
                'withoutCom_latency_mar_sol_date' => $converter->getWithoutComLatencyMarsSolDate(),
                'withCom_latency_mar_sol_date' => $converter->getWithComLatencyMarsSolDate(),
                'withoutCom_latency_martian_coordinated_time' => $converter->getWithoutComLatencyMartianCoordinatedTime(),
                'withCom_latency_martian_coordinated_time' => $converter->getWithComLatencyMartianCoordinatedTime(),
            ]);
        } catch (\Exception $e) {
            $this->setErrorResponse($e->getMessage());
        }
        return $this->response;
    }

    private function setSuccessResponse(array $data): void
    {
        $this->response->setData([
            'status' => 200,
            'message' => 'success',
            'data' => $data,
        ]);
    }

    private function setErrorResponse(string $errorMessage): void
    {
        $this->response->setData([
            'status' => 400,
            'message' => $errorMessage,
        ]);
    }
}
