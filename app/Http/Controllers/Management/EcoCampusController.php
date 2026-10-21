<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\Management\EcoWaterCampus;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class EcoCampusController extends Controller
{
    public function index()
    {
        $campuses = EcoWaterCampus::latest()->get();

        return view('managements.campuses.list', compact('campuses'));
    }

    public function sync()
    {
        
        try {
            $token = env('ECO_TOKEN');
            $sender = env('ECO_SENDER');
            $base_url = env('ECO_BASE_URL');
            $response = Http::acceptJson()
                ->withHeaders(['auth-token' => $token, 'sender' => $sender])
                ->connectTimeout(10)
                ->timeout(30)
                ->get($base_url . '/campus_list');
        } catch (ConnectionException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to connect to EcoWater. Please try again.',
            ], 502);
        }

       
        DB::transaction(function () use ($response) {
            foreach ($response['data'] as $campus) {
                $record = EcoWaterCampus::withTrashed()->updateOrCreate(
                    ['campus_code' => $campus['campus_code']],
                    ['campus_name' => $campus['campus_name']]
                );

                if ($record->trashed()) {
                    $record->restore();
                }
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Campuses synced successfully.',
        ]);
    }
}
