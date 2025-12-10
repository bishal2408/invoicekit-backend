<?php

namespace App\Services\ApiKey;

use App\Repositories\ApiKey\ApiKeyRepository;
use App\Repositories\ApiKey\EnvironmentRepository;
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
     * __construct
     */
    public function __construct(
        ApiKeyRepository $apiKeyRepository,
        EnvironmentRepository $environmentRepository,
        GeneratorService $generatorService
    ) {
        $this->apiKeyRepository = $apiKeyRepository;
        $this->environmentRepository = $environmentRepository;
        $this->generatorService = $generatorService;
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
