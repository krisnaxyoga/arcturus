<?php

namespace App\Http\Controllers\Vendor\Hotel\Room;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AttributeRoom;
use Illuminate\Support\Facades\Validator;

class AttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $id = auth()->user()->id;
        $data = AttributeRoom::where('user_id',$id)->get();
        return inertia('Vendor/MenageRoom/Attribute/Index',[
            'data' => $data
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia('Vendor/MenageRoom/Attribute/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator->errors())
                ->withInput($request->all());
        } else {
            $id = auth()->user()->id;
                $data =  new AttributeRoom();
                $data->user_id = $id;
                $data->name = $request->name;
                $data->description = $request->description;
                $data->save();
            
            return redirect()
            ->route('room.attribute.index')
            ->with('success', 'Data saved!');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = AttributeRoom::find($id);
        return inertia('Vendor/MenageRoom/Attribute/Edit',[
            'data'=>$data
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator->errors())
                ->withInput($request->all());
        } else {
                $data = AttributeRoom::find($id);
                $data->name = $request->name;
                $data->description = $request->description;
                $data->update();
            
            return redirect()
            ->route('room.attribute.index')
            ->with('success', 'Data saved!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = AttributeRoom::find($id);
        $data->delete();
        return redirect()->back()->with('message', 'Data deleted!');
    }
}
