<?php

namespace App\Services\ApiKey;

use App\Repositories\ApiKey\ApiKeyRepository;
use App\Repositories\ApiKey\EnvironmentRepository;
use App\Repositories\ApiKey\ListRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ApiKeyService
{
    /**
     * $apiKeyRepository
     *
     * @var ApiKeyRepository
     */
    private $apiKeyRepository;

    /**
     * $environmentRepository
     *
     * @var EnvironmentRepository
     */
    private $environmentRepository;

    /**
     * $generatorService
     *
     * @var GeneratorService
     */
    private $generatorService;

    /**
     * $listRepository
     *
     * @var ListRepository
     */
    private $listRepository;

    /**
     * __construct
     */
    public function __construct(
        ApiKeyRepository $apiKeyRepository,
        EnvironmentRepository $environmentRepository,
        GeneratorService $generatorService,
        ListRepository $listRepository
    ) {
        $this->apiKeyRepository = $apiKeyRepository;
        $this->environmentRepository = $environmentRepository;
        $this->generatorService = $generatorService;
        $this->listRepository = $listRepository;
    }

    /**
     * list
     *
     * @param  mixed  $perPage
     * @param  mixed  $orderBy
     * @param  mixed  $orderByDir
     * @param  mixed  $filters
     * @param  mixed  $search
     * @return mixed
     */
    public function list($perPage, $orderBy, $orderByDir, $filters, $search)
    {
        return $this->listRepository->list(
            $perPage,
            $orderBy,
            $orderByDir,
            $filters,
            $search
        );
    }

    /**
     * generate
     *
     * @param  mixed  $data
     * @return mixed
     */
    public function generate($data)
    {
        /** @var \App\Models\ApiKey\ApiKeyEnvironment $env */
        $env = $this->environmentRepository->findBy('name', $data['environment']);

        // append environment id to data
        $data['environment_id'] = $env->id;

        try {
            DB::beginTransaction();

            // key data
            $keyData = $this->generatorService->generate($env->name);

            // prepare data for create
            $data = array_merge($data, $keyData);

            // create api key record
            $this->apiKeyRepository->store($data);

            // commit transaction
            DB::commit();

            return [
                'plain_key' => $keyData['plain'],
            ];
        } catch (\Exception $e) {
            Log::info('API Key Generation Error: ', [$e->getMessage()]);
            DB::rollback();
        }

        return false;
    }

    /**
     * renegerate
     *
     * @param  mixed  $id
     * @return mixed
     */
    public function regenerate($id)
    {
        /** @var \App\Models\ApiKey\ApiKey $apiKey */
        $apiKey = $this->apiKeyRepository->find($id);

        try {
            DB::beginTransaction();

            // make current key inactive
            $this->apiKeyRepository->update($id, [
                'is_active' => null,
            ]);

            // generate new key
            $keyData = $this->generatorService->generate($apiKey->environment->name);

            // make new api key record
            $this->apiKeyRepository->store([
                'user_id' => $apiKey->user_id,
                'environment_id' => $apiKey->environment_id,
                'name' => $apiKey->name,
                'key_prefix' => $keyData['key_prefix'],
                'key_hash' => $keyData['key_hash'],
                'is_active' => true,
            ]);

            // commit transaction
            DB::commit();

            return [
                'plain_key' => $keyData['plain'],
            ];
        } catch (\Exception $e) {
            Log::info('API Key Regeneration Error: ', [$e->getMessage()]);
            DB::rollBack();
        }

        return false;
    }

    /**
     * revoke
     *
     * @param  mixed  $id
     * @return mixed
     */
    public function revoke($id)
    {
        return $this->apiKeyRepository->update($id, [
            'is_active' => null,
        ]);
    }
}
