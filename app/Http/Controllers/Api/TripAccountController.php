<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Trip;
use App\Models\TripAccount;
use App\Models\Driver;
use App\Models\TripAccountTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TripAccountController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | CREATE TRIP ACCOUNT
    |--------------------------------------------------------------------------
    | Admin creates account amount for trip.
    */
    public function store(Request $request)
    {
        $driver = auth('driver')->user();
        $adminId = $driver->admin_id;

        $request->validate([
            'trip_id' => 'required|exists:trips,id',
            'opening_amount' => 'required|numeric|min:0',
        ]);

        try {
            $account = DB::transaction(function () use ($request, $adminId) {
                $trip = Trip::where('id', $request->trip_id)
                    ->where('admin_id', $adminId)
                    ->first();

                if (!$trip) {
                    throw new \Exception('Trip not found or unauthorized.');
                }

                $alreadyExists = TripAccount::where('trip_id', $trip->id)->exists();

                if ($alreadyExists) {
                    throw new \Exception('Trip account already exists for this trip.');
                }

                return TripAccount::create([
                    'admin_id' => $adminId,
                    'trip_id' => $trip->id,
                    'driver_id' => $trip->driver_id,
                    'truck_id' => $trip->truck_id,
                    'opening_amount' => $request->opening_amount,
                    'total_expense' => 0,
                    'remaining_amount' => $request->opening_amount,
                    'status' => 'active',
                ]);
            });

            return response()->json([
                'status' => true,
                'message' => 'Trip account created successfully.',
                'data' => [
                    'account' => $account,
                ],
            ], 201);
        } catch (\Exception $e) {
            return $this->errorResponse($e);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW TRIP ACCOUNT DETAIL
    |--------------------------------------------------------------------------
    */
    public function show($tripId)
    {
        $driver = auth('driver')->user();
        $adminId = $driver->admin_id;

        $account = TripAccount::with([
            'trip',
            'driver',
            'truck',
            'transactions' => function ($query) {
                $query->latest();
            }
        ])
            ->where('trip_id', $tripId)
            ->where('admin_id', $adminId)
            ->first();

        if (!$account) {
            return response()->json([
                'status' => false,
                'message' => 'Trip account not found.',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Trip account fetched successfully.',
            'data' => [
                'account' => $account,
                'summary' => [
                    'opening_amount' => (float) $account->opening_amount,
                    'total_expense' => (float) $account->total_expense,
                    'remaining_amount' => (float) $account->remaining_amount,
                ],
            ],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | LIST ALL TRIP ACCOUNTS
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $driver = auth('driver')->user();
        $adminId = $driver->admin_id;

        $query = TripAccount::with(['trip', 'driver', 'truck'])
            ->where('admin_id', $adminId)
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('driver_id')) {
            $query->where('driver_id', $request->driver_id);
        }

        if ($request->filled('trip_id')) {
            $query->where('trip_id', $request->trip_id);
        }

        $accounts = $query->paginate($request->per_page ?? 10);

        return response()->json([
            'status' => true,
            'message' => 'Trip accounts fetched successfully.',
            'data' => $accounts,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE ACCOUNT AMOUNT
    |--------------------------------------------------------------------------
    | Add more balance to existing trip account.
    */
    public function addBalance(Request $request, $tripId)
    {
        $driver = auth('driver')->user();
        $adminId = $driver->admin_id;

        $request->validate([
            'amount' => 'required|numeric|min:1',
            'description' => 'nullable|string',
        ]);

        try {
            $account = DB::transaction(function () use ($request, $tripId, $adminId) {
                $account = TripAccount::where('trip_id', $tripId)
                    ->where('admin_id', $adminId)
                    ->lockForUpdate()
                    ->first();

                if (!$account) {
                    throw new \Exception('Trip account not found.');
                }

                $balanceBefore = (float) $account->remaining_amount;
                $amount = (float) $request->amount;
                $balanceAfter = $balanceBefore + $amount;

                $account->update([
                    'opening_amount' => $account->opening_amount + $amount,
                    'remaining_amount' => $balanceAfter,
                ]);

                return $account->fresh();
            });

            return response()->json([
                'status' => true,
                'message' => 'Balance added successfully.',
                'data' => [
                    'account' => $account,
                ],
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse($e);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | ADD GENERIC TRIP EXPENSE
    |--------------------------------------------------------------------------
    | Main API for all expense types.
    */
    public function addExpense(Request $request)
    {
        $driver = auth('driver')->user();
        $adminId = $driver->admin_id;
        if (!$driver) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized. user not found.',
            ], 401);
        }

        $request->validate([
            'trip_id' => 'required|exists:trips,id',
            'driver_id' => 'nullable|exists:drivers,id',

            'type' => 'required|string|in:fuel,toll,maintenance,food,advance,sos,fleet,other',
            'amount' => 'required|numeric|min:1',

            'expense_date' => 'nullable|date',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',

            'source_type' => 'nullable|string|max:255',
            'source_name' => 'nullable|string|max:255',
            'source_id' => 'nullable|integer',
        ]);

        try {
            $transaction = DB::transaction(function () use ($request, $adminId) {
                $trip = Trip::where('id', $request->trip_id)
                    ->where('admin_id', $adminId)
                    ->first();

                if (!$trip) {
                    throw new \Exception('Trip not found or unauthorized.');
                }

                return $this->createExpenseTransaction([
                    'admin_id' => $adminId,
                    'trip_id' => $trip->id,
                    'driver_id' => $request->driver_id ?? $trip->driver_id,

                    'type' => $request->type,
                    'amount' => $request->amount,
                    'expense_date' => $request->expense_date ?? now()->toDateString(),

                    'title' => $request->title ?? $this->sourceLabel($request->type),
                    'description' => $request->description,

                    'source_type' => $request->source_type ?? 'manual_expense',
                    'source_name' => $request->source_name ?? $this->sourceLabel($request->type),
                    'source_id' => $request->source_id,
                ]);
            });

            return response()->json([
                'status' => true,
                'message' => 'Trip expense added successfully.',
                'data' => [
                    'transaction' => $transaction,
                ],
            ], 201);
        } catch (\Exception $e) {
            return $this->errorResponse($e);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | LIST TRANSACTIONS
    |--------------------------------------------------------------------------
    */
    public function transactions(Request $request, $tripId)
    {
        $driver = auth('driver')->user();
        $adminId = $driver->admin_id;

        $account = TripAccount::where('trip_id', $tripId)
            ->where('admin_id', $adminId)
            ->first();

        if (!$account) {
            return response()->json([
                'status' => false,
                'message' => 'Trip account not found.',
            ], 404);
        }

        $query = TripAccountTransaction::where('trip_account_id', $account->id)
            ->latest();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('source_type')) {
            $query->where('source_type', $request->source_type);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('expense_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('expense_date', '<=', $request->to_date);
        }

        $transactions = $query->paginate($request->per_page ?? 10);

        return response()->json([
            'status' => true,
            'message' => 'Trip transactions fetched successfully.',
            'data' => $transactions,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE TRANSACTION / REVERSE EXPENSE
    |--------------------------------------------------------------------------
    | Optional: If admin deletes wrong expense, amount goes back to account.
    */
    public function destroyTransaction($transactionId)
    {
        $driver = auth('driver')->user();
        $adminId = $driver->admin_id;

        try {
            DB::transaction(function () use ($transactionId, $adminId) {
                $transaction = TripAccountTransaction::with('account')
                    ->where('id', $transactionId)
                    ->first();

                if (!$transaction) {
                    throw new \Exception('Transaction not found.');
                }

                $account = $transaction->account;

                if (!$account || $account->admin_id != $adminId) {
                    throw new \Exception('Unauthorized transaction.');
                }

                $account->lockForUpdate();

                $account->update([
                    'total_expense' => max(0, $account->total_expense - $transaction->amount),
                    'remaining_amount' => $account->remaining_amount + $transaction->amount,
                ]);

                $transaction->delete();
            });

            return response()->json([
                'status' => true,
                'message' => 'Transaction deleted and balance reversed successfully.',
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse($e);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | CORE GENERIC EXPENSE FUNCTION
    |--------------------------------------------------------------------------
    | This function is reusable for fuel, toll tax, maintenance, food, etc.
    */
    private function createExpenseTransaction(array $data)
    {
        $driver = auth('driver')->user();
        $adminId = $driver->admin_id;

        $account = TripAccount::where('trip_id', $data['trip_id'])
            ->where('admin_id', $data['admin_id'])
            ->lockForUpdate()
            ->first();

        if (!$account) {
            throw new \Exception(json_encode([
                'message' => 'Trip account not found.',
                'trip_id' => $data['trip_id'],
            ]));
        }

        if ($account->status !== 'active') {
            throw new \Exception(json_encode([
                'message' => 'Trip account is not active.',
                'status_value' => $account->status,
            ]));
        }

        $amount = (float) $data['amount'];

        if ($amount <= 0) {
            throw new \Exception(json_encode([
                'message' => 'Expense amount must be greater than zero.',
                'amount' => $amount,
            ]));
        }

        /*
        |--------------------------------------------------------------------------
        | DUPLICATE CHECK
        |--------------------------------------------------------------------------
        | This prevents same fuel_log / toll / maintenance source being deducted twice.
        */
        if (!empty($data['source_type']) && !empty($data['source_id'])) {
            $alreadyExists = TripAccountTransaction::where('trip_account_id', $account->id)
                ->where('source_type', $data['source_type'])
                ->where('source_id', $data['source_id'])
                ->exists();

            if ($alreadyExists) {
                throw new \Exception(json_encode([
                    'message' => 'Transaction already exists for this source.',
                    'source_type' => $data['source_type'],
                    'source_id' => $data['source_id'],
                ]));
            }
        }

        $balanceBefore = (float) $account->remaining_amount;
        $balanceAfter = $balanceBefore - $amount;

        if ($balanceAfter < 0) {
            throw new \Exception(json_encode([
                'message' => 'Insufficient trip account balance.',
                'expense_type' => $data['type'],
                'source_type' => $data['source_type'] ?? null,
                'source_name' => $data['source_name'] ?? null,
                'source_id' => $data['source_id'] ?? null,
                'balance_before' => round($balanceBefore, 2),
                'deduct_amount' => round($amount, 2),
                'balance_after' => round($balanceAfter, 2),
                'short_amount' => round(abs($balanceAfter), 2),
            ]));
        }

        $transaction = TripAccountTransaction::create([
            'trip_account_id' => $account->id,
            'trip_id' => $data['trip_id'],
            'driver_id' => $data['driver_id'],

            'type' => $data['type'],
            'amount' => $amount,
            'expense_date' => $data['expense_date'] ?? now()->toDateString(),

            'title' => $data['title'] ?? $this->sourceLabel($data['type']),
            'description' => $data['description'] ?? null,

            'source_type' => $data['source_type'] ?? $data['type'],
            'source_name' => $data['source_name'] ?? $this->sourceLabel($data['type']),
            'source_id' => $data['source_id'] ?? null,

            'balance_before' => $balanceBefore,
            'balance_after' => $balanceAfter,
        ]);

        $account->update([
            'total_expense' => $account->total_expense + $amount,
            'remaining_amount' => $balanceAfter,
        ]);

        return $transaction;
    }

    /*
    |--------------------------------------------------------------------------
    | SOURCE LABELS
    |--------------------------------------------------------------------------
    */
    private function sourceLabel($type)
    {
        return [
            'fuel' => 'Fuel Expense',
            'toll' => 'Toll Tax',
            'maintenance' => 'Maintenance Expense',
            'food' => 'Food Expense',
            'advance' => 'Driver Advance',
            'sos' => 'SOS Expense',
            'fleet' => 'Fleet Expense',
            'other' => 'Other Expense',
        ][$type] ?? 'Other Expense';
    }

    /*
    |--------------------------------------------------------------------------
    | STANDARD ERROR RESPONSE
    |--------------------------------------------------------------------------
    */
    private function errorResponse(\Exception $e)
    {
        $decoded = json_decode($e->getMessage(), true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return response()->json([
                'status' => false,
                ...$decoded,
            ], 422);
        }

        return response()->json([
            'status' => false,
            'message' => $e->getMessage(),
        ], 422);
    }
}
