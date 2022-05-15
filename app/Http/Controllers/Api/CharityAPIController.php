<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\API\CreateCharityAPIRequest;
use App\Http\Requests\API\UpdateCharityAPIRequest;
use App\Models\Charity;
use App\Repositories\CharityRepository;
use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\CharityResource;
use Illuminate\Http\Request;
use Response;

/**
 * Class CharityController
 * @package App\Http\Controllers\API
 */

class CharityAPIController extends ApiController
{
    /**
     * Get the repository class
     *
     * @return string
     */
    public function repository()
    {
        return CharityRepository::class;
    }

    /**
     * Display a listing of the Charity.
     * GET|HEAD /charities
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $models = $this->getRepository()->search($request->all())->isActive();

        return $this->paginate($request, $models, 'Charities retrieved successfully');
    }

    /**
     * Store a newly created Charity in storage.
     * POST /charities
     *
     * @param CreateCharityAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateCharityAPIRequest $request)
    {
        $input = $request->all();

        $model = $this->getRepository()->create($input);

        return $this->printModelSuccess(new CharityResource($model), 'Charity saved successfully');
    }

    /**
     * Display the specified Charity.
     * GET|HEAD /charities/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show(Request $request, $id)
    {
        /** @var Charity $model */
        $model = $this->getRepository()->findOrFail($id);

        return $this->printModelSuccess(new CharityResource($model), 'Charity retrieved successfully');
    }

    /**
     * Update the specified Charity in storage.
     * PUT/PATCH /charities/{id}
     *
     * @param int $id
     * @param UpdateCharityAPIRequest $request
     *
     * @return Response
     */
    public function update(UpdateCharityAPIRequest $request, $id)
    {
        $input = $request->all();

        /** @var Charity $model */
        $this->getRepository()->existsOrFail($id);

        $model = $this->getRepository()->update($input, $id);

        return $this->printModelSuccess(new CharityResource($model), 'Charity updated successfully');
    }

    /**
     * Remove the specified Charity from storage.
     * DELETE /charities/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        /** @var Charity $model */
        $model = $this->getRepository()->findOrFail($id);

        return $this->printModelSuccess($model->delete(), 'Charity deleted successfully');
    }
}