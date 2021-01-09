<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Requests;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Validators\AutoMotorValidator;
use App\Services\Contracts\AutoMotorService;
use App\Http\Requests\AutoMotorCreateRequest;
use App\Http\Requests\AutoMotorUpdateRequest;
use App\Http\Resources\AutoMotorsResource;
use App\Repositories\Contracts\AutoMotorRepository;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * Class AutoMotorsController.
 *
 * @package namespace App\Http\Controllers\Api\v1;
 */
class AutoMotorsController extends Controller
{
    /**
     * @var AutoMotorRepository
     */
    protected $repository;

    /**
     * @var AutoMotorValidator
     */
    protected $validator;

    /**
     * @var AutoMotorService
     */
    protected $service;

    /**
     * AutoMotorsController constructor.
     *
     * @param AutoMotorRepository $repository
     * @param AutoMotorValidator $validator
     * @param AutoMotorService $service
     */
    public function __construct(AutoMotorRepository $repository, AutoMotorValidator $validator, AutoMotorService $service)
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
        $autoMotors = AutoMotorsResource::collection($this->repository->all());

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $autoMotors,
            ]);
        }

        return view('autoMotors.index', compact('autoMotors'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  AutoMotorCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(AutoMotorCreateRequest $request)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $autoMotor = $this->service->store($request->all());

            $response = [
                'message' => 'AutoMotor created.',
                'data'    => new AutoMotorsResource($autoMotor),
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
        $autoMotor = new AutoMotorsResource($this->repository->find($id));

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $autoMotor,
            ]);
        }

        return view('autoMotors.show', compact('autoMotor'));
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
        $autoMotor = new AutoMotorsResource($this->repository->find($id));

        return view('autoMotors.edit', compact('autoMotor'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  AutoMotorUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(AutoMotorUpdateRequest $request, $id)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $autoMotor = $this->service->update($id, $request->all());

            $response = [
                'message' => 'AutoMotor updated.',
                'data'    => new AutoMotorsResource($autoMotor),
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
                'message' => 'AutoMotor deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'AutoMotor deleted.');
    }
}
