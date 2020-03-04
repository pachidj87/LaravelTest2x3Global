<?php

namespace App\Http\Controllers\API;

use App\Events\PaymentPlaced;
use App\Http\Requests\API\ClientPaymentsAPIRequest;
use App\Http\Requests\API\CreatePaymentsAPIRequest;
use App\Http\Requests\API\UpdatePaymentsAPIRequest;
use App\Http\Resources\PaymentResource;
use App\Http\Resources\PaymentResourceCollection;
use App\Jobs\SetDailyExchange;
use App\Models\Payment;
use App\Repositories\PaymentRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\AppBaseController;

/**
 * Class PaymentsController
 * @package App\Http\Controllers\API
 */

class PaymentsAPIController extends AppBaseController
{
    /** @var  PaymentRepository */
    private $paymentRepository;

    /**
     * PaymentsAPIController constructor.
     *
     * @param PaymentRepository $paymentsRepo
     */
    public function __construct(PaymentRepository $paymentsRepo)
    {
        $this->paymentRepository = $paymentsRepo;
    }

    /**
     * @param ClientPaymentsAPIRequest $request
     * @return JsonResponse
     *
     * @SWG\Get(
     *      path="/payments",
     *      summary="Get a listing of the Payments.",
     *      tags={"Payment"},
     *      description="Get all Payments",
     *      produces={"application/json"},
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  type="array",
     *                  @SWG\Items(ref="#/definitions/Payment")
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function index(ClientPaymentsAPIRequest $request)
    {
        if ($request->has('client')) {
            $params = array_merge([
                'user_id' => (int)$request->get('client')
            ], $request->except(['skip', 'limit', 'client']));
        } else {
            $params = $request->except(['skip', 'limit']);
        }

        $payments = $this->paymentRepository->all(
            $params,
            $request->get('skip'),
            $request->get('limit')
        );

        return JsonResponse::create(new PaymentResourceCollection($payments));
    }

    /**
     * @param CreatePaymentsAPIRequest $request
     * @return JsonResponse
     *
     * @SWG\Post(
     *      path="/payments",
     *      summary="Store a newly created Payments in storage",
     *      tags={"Payment"},
     *      description="Store Payments",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Payments that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Payment")
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/Payment"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreatePaymentsAPIRequest $request)
    {
        $input = $request->all();

        /** @var Payment $payment */
        $payment = $this->paymentRepository->create($input);

        SetDailyExchange::dispatch($payment);

        $payment->refresh();

        event(new PaymentPlaced($payment));

        return JsonResponse::create(new PaymentResource($payment));
    }

    /**
     * @param int $id
     * @return JsonResponse
     *
     * @SWG\Get(
     *      path="/payments/{id}",
     *      summary="Display the specified Payments",
     *      tags={"Payment"},
     *      description="Get Payments",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Payments",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/Payment"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function show($id)
    {
        /** @var Payment $payments */
        $payment = $this->paymentRepository->find($id);

        if (empty($payment)) {
            return $this->sendError('Payments not found');
        }

        return JsonResponse::create(new PaymentResource($payment));
    }

    /**
     * @param int $id
     * @param UpdatePaymentsAPIRequest $request
     * @return JsonResponse
     *
     * @SWG\Put(
     *      path="/payments/{id}",
     *      summary="Update the specified Payments in storage",
     *      tags={"Payment"},
     *      description="Update Payments",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Payments",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Payments that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Payment")
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/Payment"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdatePaymentsAPIRequest $request)
    {
        $input = $request->all();

        /** @var Payment $payments */
        $payments = $this->paymentRepository->find($id);

        if (empty($payments)) {
            return $this->sendError('Payments not found');
        }

        $payment = $this->paymentRepository->update($input, $id);

        return JsonResponse::create(new PaymentResource($payment));
    }

    /**
     * @param int $id
     * @return JsonResponse
     *
     * @throws Exception
     *
     * @SWG\Delete(
     *      path="/payments/{id}",
     *      summary="Remove the specified Payments from storage",
     *      tags={"Payment"},
     *      description="Delete Payments",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Payments",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function destroy($id)
    {
        /** @var Payment $payments */
        $payments = $this->paymentRepository->find($id);

        if (empty($payments)) {
            return $this->sendError('Payment not found');
        }

        $payments->delete();

        return $this->sendSuccess('Payment deleted successfully');
    }
}
