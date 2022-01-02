<?php

namespace App\Http\Controllers;

use App\Models\Aggression;
use App\Models\Blacklist;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AggressionController extends Controller
{
    /**
     * Show the map
     *
     * @return \Illuminate\View\View|\Laravel\Lumen\Application
     */
    public function index()
    {
        $today = Carbon::now()->startOfDay();
        $weekago = Carbon::now()->subWeek();
        $monthago = Carbon::now()->subMonth();

        $aggressions = Aggression::where('is_visible', 1)->get();
        foreach ($aggressions as &$aggression) {
            $append_date = Carbon::createFromFormat('Y-m-d H:i:s', $aggression->datetime, 'Europe/Brussels')->startOfDay();
            $tags = '["Tout"]';
            if ($today->equalTo($append_date)) {
                $tags = '["Aujourd\'hui", "7 derniers jours", "30 derniers jours", "Tout"]';
            } else {
                if ($append_date->greaterThanOrEqualTo($weekago)) {
                    $tags = '["7 derniers jours", "30 derniers jours", "Tout"]';
                } else {
                    if ($append_date->greaterThanOrEqualTo($monthago)) {
                        $tags = '["30 derniers jours", "Tout"]';
                    }
                }
            }
            $aggression->coordinates = str_replace('LatLng(', '', $aggression->coordinates);
            $aggression->coordinates = str_replace(')', '', $aggression->coordinates);
            $aggression->lat = trim(explode(',', $aggression->coordinates)[0]);
            $aggression->long = trim(explode(',', $aggression->coordinates)[1]);
            $aggression->tags = $tags;
        }
        return view('index', ['aggressions' => $aggressions]);
    }

    /**
     * Store a new aggression
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Laravel\Lumen\Http\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        if (!$request->ajax()) {
            abort(404);
        }
        $this->validate($request, [
            'date' => ['required', 'date'],
            'description' => ['required', 'max:65000', 'min:4'],
        ]);

        // Check if the IP address is not blacklisted.
        $blacklist = Blacklist::all()->pluck('ip')->toArray();
        if (!in_array($request->ip(), $blacklist)) {
            // take care of all possible newline-encodings in input file.
            $new_line_reg = '/(\r\n)|\r|\n/';
            $description = preg_replace($new_line_reg, '. ', $request->description);
            $aggression = new Aggression();
            $aggression->datetime = $request->date . ' ' . $request->time . ':00';
            $aggression->description = $description;
            $aggression->contact = $request->contact;
            $aggression->coordinates = $request->coordinates;
            $aggression->ip = $request->ip();
            $aggression->is_moderate = 0;
            $aggression->is_visible = 0;
            $result = $aggression->save();

            return response()->json([
                'success' => $result,
                'result' => $request->coordinates,
                'description' => $request->description
            ]);
        } else {
            return redirect(route('index'));
        }
    }

    /**
     * Export all aggressions in a CSV
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function exportCSV()
    {
        $fileName = 'agressions.csv';
        $aggressions = Aggression::where('is_visible', 1)->orderBy('id', 'desc')->get();

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ["Identifiant", "Type", "Description", "Latitude", "Longitude", "Date d'ajout", "Date de l'agression"];

        $callback = function () use ($aggressions, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($aggressions as &$aggression) {
                $aggression->coordinates = str_replace('LatLng(', '', $aggression->coordinates);
                $aggression->coordinates = str_replace(')', '', $aggression->coordinates);
                $aggression->lat = trim(explode(',', $aggression->coordinates)[0]);
                $aggression->long = trim(explode(',', $aggression->coordinates)[1]);

                fputcsv($file, [
                    $aggression->id,
                    $aggression->type,
                    $aggression->description,
                    $aggression->lat,
                    $aggression->long,
                    $aggression->created_at,
                    $aggression->datetime
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

