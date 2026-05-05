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
    public function update(Request $request, $id)
    {
        $lead = Lead::findOrFail($id);

        $lead->update($request->all());

        return response()->json($lead);
    }

    public function destroy($id)
    {
        $lead = Lead::findOrFail($id);
        $lead->delete();

        return response()->json(['message' => 'Lead eliminado']);
    }

}
