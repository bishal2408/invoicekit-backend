<?php

namespace App\Http\Controllers\ApiKey;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApiKey\CreateRequest;
use App\Services\ApiKey\ApiKeyService;
use App\Traits\Response;

class ApiKeyController extends Controller
{
    use Response;

    /**
     * apiKeyService
     *
     * @var ApiKeyService
     */
    private $apiKeyService;

    /**
     * __construct
     */
    public function __construct(ApiKeyService $apiKeyService)
    {
        $this->apiKeyService = $apiKeyService;
    }

    /**
     * store
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateRequest $request)
    {
        $data = $request->validated();

        // generate api key
        $data = $this->apiKeyService->generate($data);

        // if success
        if ($data) {
            return $this->sendResponseWithData($data, 'API_KEY_CREATED');
        }

        // if error
        return $this->errorResponse('API_KEY_CREATE_FAILED');
    }
}
