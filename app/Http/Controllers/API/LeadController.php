<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\Request;

class LeadController extends Controller
{
        public function index()
{
    return Lead::all();
}
    public function store(Request $request)
    {
        $lead = Lead::create($request->all());
        return response()->json($lead, 201);
    }

}
