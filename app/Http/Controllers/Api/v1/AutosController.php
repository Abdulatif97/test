<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Requests;

use Illuminate\Http\Request;
use App\Validators\AutoValidator;
use App\Http\Controllers\Controller;
use App\Http\Resources\AutosResource;
use App\Services\Contracts\AutoService;
use App\Http\Requests\AutoCreateRequest;
use App\Http\Requests\AutoUpdateRequest;
use App\Repositories\Contracts\AutoRepository;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * Class AutosController.
 *
 * @package namespace App\Http\Controllers\Api\v1;
 */
class AutosController extends Controller
{
    /**
     * @var AutoRepository
     */
    protected $repository;

    /**
     * @var AutoValidator
     */
    protected $validator;

    /**
     * @var AutoService
     */
    protected $service;

    /**
     * AutosController constructor.
     *
     * @param AutoRepository $repository
     * @param AutoValidator $validator
     * @param AutoService $service
     */
    public function __construct(AutoRepository $repository, AutoValidator $validator, AutoService $service)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->repository->pushCriteria(app('Prettus\Repository\Criteria\RequestCriteria'));
        $autos = AutosResource::collection($this->repository->all());

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $autos,
            ]);
        }

        return view('autos.index', compact('autos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  AutoCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(AutoCreateRequest $request)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $auto = $this->service->store($request->all());

            $response = [
                'message' => 'Auto created.',
                'data'    => new AutosResource($auto),
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
        $auto =  new AutosResource($this->repository->find($id));

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $auto,
            ]);
        }

        return view('autos.show', compact('auto'));
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
        $auto = $this->repository->find($id);

        return view('autos.edit', compact('auto'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  AutoUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(AutoUpdateRequest $request, $id)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $auto = $this->service->update($id, $request->all());

            $response = [
                'message' => 'Auto updated.',
                'data'    => new AutosResource($auto),
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
                'message' => 'Auto deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'Auto deleted.');
    }
}
