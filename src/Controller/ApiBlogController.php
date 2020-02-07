<?php declare(strict_types=1);

namespace App\Controller;

use App\GraphQL\QueryType;
use Exception;
use GraphQL\GraphQL;
use GraphQL\Type\Schema;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @package App\Controller
 * @Route("/api")
 */
class ApiBlogController extends AbstractController
{
    /**
     * @Route("/", name="api")
     *
     * @param Request $request
     * @param QueryType $queryType
     * @return JsonResponse
     */
    public function index(Request $request, QueryType $queryType): JsonResponse
    {
        try {
            $schema = new Schema(['query' => $queryType]);
            $data = json_decode($request->getContent(), true);

            $result = GraphQL::executeQuery(
                $schema,
                $data['query'],
                null,
                null,
                isset($data['variables']) ? $data['variables'] : null,
                isset($data['operationName']) ? $data['operationName'] : null
            );
        } catch (Exception $exception) {
            return  $this->json([
                'errors' => [
                    ['message' => $exception->getMessage()]
                ]
            ]);
        }

        return $this->json($result);
    }
}