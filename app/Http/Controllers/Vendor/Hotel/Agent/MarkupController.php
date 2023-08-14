<?php

namespace App\Http\Controllers\Vendor\Hotel\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AgentMarkupSetup;
use App\Models\AgentMarkupDetail;
use App\Models\Vendor;
use Illuminate\Support\Facades\Validator;

class MarkupController extends Controller
{
    public function store(Request $request){
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'markup' => 'required'
        ]);

        $id = auth()->user()->id;
        $vendor = Vendor::where('user_id',$id)->get();

        // dd($request->hasFile('image'));
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator->errors())
                ->withInput($request->all());
        } else {
                $data = new AgentMarkupSetup;
                $data->tax = $request->tax;
                $data->service = $request->service;
                $data->markup_price = $request->markup;
                $data->user_id = $id;
                $data->vendor_id = $vendor[0]->id;
                $data->save();

                foreach($request->blockout as $item){
                    $black = new AgentMarkupDetail();
                    $black->start_date = $item['start'];
                    $black->end_date = $item['end'];
                    $black->surcharge_block_price = 0;
                    $black->markup_cat_id = 1;
                    $black->user_id = $id;
                    $black->vendor_id = $vendor[0]->id;
                    $black->save();
                }

                foreach($request->surcharge as $item){
                    $surcharge = new AgentMarkupDetail();
                    $surcharge->start_date = $item['start'];
                    $surcharge->end_date = $item['end'];
                    $surcharge->markup_cat_id = 2;
                    $surcharge->surcharge_block_price = $item['price'];
                    $surcharge->user_id = $id;
                    $surcharge->vendor_id = $vendor[0]->id;
                    $surcharge->save();
                }

            return redirect()
                ->route('roomagent.price')
                ->with('seccess', 'Data berhasil disimpan.');
        }
    }

    public function update(Request $request,$price){

            $id = auth()->user()->id;
            $vendorid = Vendor::where('user_id',$id)->first();
            $markup = AgentMarkupSetup::where('user_id',$id)->get();

            if($markup->isEmpty()){

                $newmarkup = new AgentMarkupSetup();
                $newmarkup->user_id = $id;
                $newmarkup->vendor_id = $vendorid->id;
                $newmarkup->markup_price = $price;
                $newmarkup->tax = 0;
                $newmarkup->service = 0;
                $newmarkup->save();

            }else{

                $markupupdate = AgentMarkupSetup::find($markup[0]->id);
                $markupupdate->markup_price = $price;
                $markupupdate->save();

            }

            return redirect()->back()->with('message', 'Markup update!');
    }


    public function index(Request $request){
        $url = $request->url();
        $id = auth()->user()->id;
        $data = AgentMarkupSetup::where('user_id',$id)->get();
        $black = AgentMarkupDetail::where('user_id',$id)->where('markup_cat_id','blackout')->get();
        $surc = AgentMarkupDetail::where('user_id',$id)->where('markup_cat_id','surcharges')->get();
        return inertia('Vendor/MenageRoom/Markup/Index',[
            'data' => $data,
            'black' => $black,
            'surcharge' =>$surc,
            'url' => $url
        ]);
    }

    public function create(){
        return inertia('Vendor/MenageRoom/Markup/Create');
    }

    public function edit($id){
        $data = AgentMarkupSetup::find($id);
        return inertia('Vendor/MenageRoom/Markup/Edit',[
            'data' => $data
        ]);
    }

    public function addblack(){
        return inertia('Vendor/MenageRoom/Markup/AddBlackout');
    }

    public function editblack($id){
        $black =  AgentMarkupDetail::find($id);
        return inertia('Vendor/MenageRoom/Markup/EditBlackout',[
            'data'=>$black
        ]);
    }

    public function storeblack(Request $request){
         // dd($request->all());
        $validator = Validator::make($request->all(), [
            'start' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator->errors())
                ->withInput($request->all());
        } else {
            $id = auth()->user()->id;
            $vendor = Vendor::where('user_id',$id)->get();
            $black =  new AgentMarkupDetail();
            $black->start_date = $request->start;
            $black->end_date = $request->end;
            $black->surcharge_block_price = 0;
            $black->user_id = $id;
            $black->vendor_id = $vendor[0]->id;
            $black->markup_cat_id = 'blackout';
            $black->save();

            return redirect()
            ->route('contract.index')
            ->with('success', 'Data saved!');
        }
    }

    public function updateblack(Request $request,$id){
         // dd($request->all());
         $validator = Validator::make($request->all(), [
            'start' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator->errors())
                ->withInput($request->all());
        } else {
            $black = AgentMarkupDetail::find($id);
            $black->start_date = $request->start;
            $black->end_date = $request->end;
            $black->save();

            return redirect()
            ->route('contract.index')
            ->with('success', 'Data saved!');
        }
    }

    public function destroyblack(string $id)
    {
        $data = AgentMarkupDetail::find($id);
        $data->delete();
        return redirect()->back()->with('message', 'Data deleted!');
    }

    public function addsurchage(){
        return inertia('Vendor/MenageRoom/Markup/AddSurchage');
    }

    public function editsurchage($id){
        $surcharge = AgentMarkupDetail::find($id);
        return inertia('Vendor/MenageRoom/Markup/EditSurcharge',[
            'data'=>$surcharge
        ]);
    }

    public function storesurchage(Request $request){
        $validator = Validator::make($request->all(), [
            'start' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator->errors())
                ->withInput($request->all());
        } else {
            $id = auth()->user()->id;
            $vendor = Vendor::where('user_id',$id)->get();
            $surcharge = new AgentMarkupDetail();
            $surcharge->start_date = $request->start;
            $surcharge->end_date = $request->end;
            $surcharge->surcharge_block_price = $request->price;
            $surcharge->user_id = $id;
            $surcharge->vendor_id = $vendor[0]->id;
            $surcharge->markup_cat_id = 'surcharges';
            $surcharge->save();

            return redirect()
            ->route('contract.index')
            ->with('success', 'Data saved!');
        }
    }

    public function updatesurchage(Request $request,$id){
        $validator = Validator::make($request->all(), [
            'start' => 'required',
        ]);
        
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator->errors())
                ->withInput($request->all());
        } else {
            $surcharge = AgentMarkupDetail::find($id);
            $surcharge->start_date = $request->start;
            $surcharge->end_date = $request->end;
            $surcharge->surcharge_block_price = $request->price;
            $surcharge->save();

            return redirect()
            ->route('contract.index')
            ->with('success', 'Data saved!');
        }
    }

    public function destroysurchage(string $id)
    {
        $data = AgentMarkupDetail::find($id);
        $data->delete();
        return redirect()->back()->with('message', 'Data deleted!');
    }
}
