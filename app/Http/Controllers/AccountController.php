<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Account;

class AccountController extends Controller
{
    public function index()
    {
        $acc = auth()->user()->accounts;
 
        return response()->json([
            'success' => true,
            'data' => $acc
        ]);
    }
 
    public function show($id)
    {
        $acc = auth()->user()->accounts()->find($id);
 
        if (!$acc) {
            return response()->json([
                'success' => false,
                'message' => ' not found '
            ], 400);
        }
 
        return response()->json([
            'success' => true,
            'data' => $acc->toArray()
        ], 400);
    }
 
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'phone' => 'required',
            'city' => 'required',
            'area' => 'required',
            'street' => 'required',
            'building' => 'required',
        ]);
 
        $acc = new Account();
        $acc->name = $request->name;
        $acc->phone = $request->phone;
        $acc->city = $request->city;
        $acc->area = $request->area;
        $acc->street = $request->street;
        $acc->building = $request->building;
 
        if ( auth()->user()->accounts()->save($acc))
            return response()->json([
                'success' => true,
                'data' => $acc->toArray()
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => ' not added'
            ], 500);
    }
 
    public function update(Request $request, $id)
    {
        $acc = auth()->user()->accounts()->find($id);
 
        if (!$acc) {
            return response()->json([
                'success' => false,
                'message' => ' not found'
            ], 400);
        }
 
        $updated = $acc->fill($request->all())->save();
 
        if ($updated)
            return response()->json([
                'success' => true
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => ' can not be updated'
            ], 500);
    }
 
    public function destroy($id)
    {
        $acc =auth()->user()->accounts()->find($id);
 
        if (!$acc) {
            return response()->json([
                'success' => false,
                'message' => ' not found'
            ], 400);
        }
 
        if ($acc->delete()) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => ' can not be deleted'
            ], 500);
        }
    }
}