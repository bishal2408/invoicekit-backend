<?php

namespace App\Http\Controllers\ApiKey;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApiKey\CreateRequest;
use App\Http\Requests\ApiKey\ListRequest;
use App\Http\Requests\ApiKey\RegenerateRequest;
use App\Http\Requests\ApiKey\RevokeRequest;
use App\Http\Resources\ApiKey\ListResource;
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
     * index
     *
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function index(ListRequest $request)
    {
        $search = $request->get('search');
        $perPage = $request->validated('per_page');
        $orderBy = $request->validated('order_by');
        $orderByDir = $request->validated('order_by_dir');
        $filters = $request->validated('filters');

        // data
        $data = $this->apiKeyService->list(
            $perPage,
            $orderBy,
            $orderByDir,
            $filters,
            $search
        );

        return ListResource::collection($data);
    }

    /**
     * store
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = true;

        // generate api key
        $data = $this->apiKeyService->generate($data);

        // if success
        if ($data) {
            return $this->sendResponseWithData($data, 'API_KEY_CREATED');
        }

        // if error
        return $this->errorResponse('API_KEY_CREATE_FAILED');
    }

    /**
     * regenerate
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function regenerate(RegenerateRequest $request, int $id)
    {
        // regenerate api key
        $data = $this->apiKeyService->regenerate($id);

        // if success
        if ($data) {
            return $this->sendResponseWithData($data, 'API_KEY_REGENERATED');
        }

        // if error
        return $this->errorResponse('API_KEY_REGENERATE_FAILED');
    }

    /**
     * revoke
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function revoke(RevokeRequest $request, int $id)
    {
        // revoke api key
        $data = $this->apiKeyService->revoke($id);

        // if success
        if ($data) {
            return $this->sendResponse('API_KEY_REVOKED');
        }

        // if error
        return $this->errorResponse('API_KEY_REVOKE_FAILED');
    }
}
