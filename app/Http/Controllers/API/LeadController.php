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
    $data = $request->all();

  
    if ($data['interes'] == 'casa') {
        $data['estado'] = 'prioridad_alta';
    } else {
        $data['estado'] = 'nuevo';
    }

    $lead = Lead::create($data);

    return response()->json($lead, 201);
}

}
