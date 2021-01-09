<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Requests;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Validators\AutoTypeValidator;
use App\Http\Resources\AutoTypesResource;
use App\Services\Contracts\AutoTypeService;
use App\Http\Requests\AutoTypeCreateRequest;
use App\Http\Requests\AutoTypeUpdateRequest;
use App\Repositories\Contracts\AutoTypeRepository;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * Class AutoTypesController.
 *
 * @package namespace App\Http\Controllers\Api\v1;
 */
class AutoTypesController extends Controller
{
    /**
     * @var AutoTypeRepository
     */
    protected $repository;

    /**
     * @var AutoTypeValidator
     */
    protected $validator;

    /**
     * @var AutoTypeService
     */
    protected $service;

    /**
     * AutoTypesController constructor.
     *
     * @param AutoTypeRepository $repository
     * @param AutoTypeValidator $validator
     * @param AutoTypeService $service
     */
    public function __construct(AutoTypeRepository $repository, AutoTypeValidator $validator, AutoTypeService $service)
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
        $autoTypes = AutoTypesResource::collection($this->repository->all());

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $autoTypes,
            ]);
        }

        return view('autoTypes.index', compact('autoTypes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  AutoTypeCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(AutoTypeCreateRequest $request)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $autoType = $this->service->store($request->all());

            $response = [
                'message' => 'AutoType created.',
                'data'    => new AutoTypesResource($autoType),
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
        $autoType = new AutoTypesResource($this->repository->find($id));

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $autoType,
            ]);
        }

        return view('autoTypes.show', compact('autoType'));
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
        $autoType = $this->repository->find($id);

        return view('autoTypes.edit', compact('autoType'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  AutoTypeUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(AutoTypeUpdateRequest $request, $id)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $autoType = $this->service->update($id, $request->all());

            $response = [
                'message' => 'AutoType updated.',
                'data'    =>  new AutoTypesResource($autoType),
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
                'message' => 'AutoType deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'AutoType deleted.');
    }
}
