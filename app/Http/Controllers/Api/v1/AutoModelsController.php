<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Requests;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Validators\AutoModelValidator;
use App\Http\Requests\AutoModelCreateRequest;
use App\Http\Requests\AutoModelUpdateRequest;
use App\Http\Resources\AutoTypesResource;
use App\Repositories\Contracts\AutoModelRepository;
use App\Services\Contracts\AutoModelService;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * Class AutoModelsController.
 *
 * @package namespace App\Http\Controllers\Api\v1;
 */
class AutoModelsController extends Controller
{
    /**
     * @var AutoModelRepository
     */
    protected $repository;

    /**
     * @var AutoModelValidator
     */
    protected $validator;

    /**
     * @var AutoModelService
     */
    protected $service;

    /**
     * AutoModelsController constructor.
     *
     * @param AutoModelRepository $repository
     * @param AutoModelValidator $validator
     * @param AutoModelService $service
     */
    public function __construct(AutoModelRepository $repository, AutoModelValidator $validator, AutoModelService $service)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
        $this->service  = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->repository->pushCriteria(app('Prettus\Repository\Criteria\RequestCriteria'));
        $autoModels =  AutoTypesResource::collection($this->repository->all());

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $autoModels,
            ]);
        }

        return view('autoModels.index', compact('autoModels'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  AutoModelCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(AutoModelCreateRequest $request)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $autoModel = $this->service->store($request->all());

            $response = [
                'message' => 'AutoModel created.',
                'data'    => new AutoTypesResource($autoModel),
            ];

            if ($request->wantsJson()) {

                return response()->json($response);
            }

            return redirect()->back()->with('message', $response['message']);
        } catch (ValidatorException $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'error'   => true,
                    'message' => $e->getMessageBag()
                ]);
            }

            return redirect()->back()->withErrors($e->getMessageBag())->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $autoModel =  new AutoTypesResource($this->repository->find($id));

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $autoModel,
            ]);
        }

        return view('autoModels.show', compact('autoModel'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $autoModel = $this->repository->find($id);

        return view('autoModels.edit', compact('autoModel'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  AutoModelUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(AutoModelUpdateRequest $request, $id)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $autoModel = $this->service->update($id, $request->all());

            $response = [
                'message' => 'AutoModel updated.',
                'data'    => new AutoTypesResource($autoModel),
            ];

            if ($request->wantsJson()) {

                return response()->json($response);
            }

            return redirect()->back()->with('message', $response['message']);
        } catch (ValidatorException $e) {

            if ($request->wantsJson()) {

                return response()->json([
                    'error'   => true,
                    'message' => $e->getMessageBag()
                ]);
            }

            return redirect()->back()->withErrors($e->getMessageBag())->withInput();
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deleted = $this->service->delete($id);

        if (request()->wantsJson()) {

            return response()->json([
                'message' => 'AutoModel deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'AutoModel deleted.');
    }
}
