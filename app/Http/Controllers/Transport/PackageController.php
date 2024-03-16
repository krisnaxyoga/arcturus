<?php

namespace App\Http\Controllers\Transport;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AgentTransport;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($token,$id)
    {
        $user = AgentTransport::select('id', 'address', 'code', 'company_name', 'created_at', 'email', 'markup', 'mobile_phone', 'status', 'updated_at')
        ->where('id', $id)
        ->first();
        return inertia('Transport/Package/Index',[
            'token' => $token,
            'user' => $user
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($token,$id)
    {
        $user = AgentTransport::select('id', 'address', 'code', 'company_name', 'created_at', 'email', 'markup', 'mobile_phone', 'status', 'updated_at')
        ->where('id', $id)
        ->first();
        return inertia('Transport/Package/Create',[
            'token' => $token,
            'user' => $user
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
    public function edit($token,$iddata,$id)
    {
        $iduser = auth()->guard('agent_transport')->id();

        $user = AgentTransport::select('id', 'address', 'code', 'company_name', 'created_at', 'email', 'markup', 'mobile_phone', 'status', 'updated_at')
        ->where('id', $iduser)
        ->first();
      

        return inertia('Transport/Package/Edit',[
            'token' => $token,
            'user' => $user,
            'iddata' => $iddata,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
