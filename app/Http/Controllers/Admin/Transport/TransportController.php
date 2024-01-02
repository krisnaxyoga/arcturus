<?php

namespace App\Http\Controllers\Admin\Transport;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AgentTransport;
use App\Models\Setting;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Mail\InviteTransport;
use App\Models\OrderTransport;
use Carbon\Carbon;
use App\Models\TransportPickup;
use App\Models\TransportBankAccount;
use App\Models\WidrawTransport;

class TransportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $settingExists = Setting::exists();

        if ($settingExists) {
            $setting = Setting::first();
        } else {
            $setting = new Setting;
        }
        $data = AgentTransport::orderBy('created_at','desc')->get();
        return view('admin.transport.index',compact('data','setting'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $settingExists = Setting::exists();

        if ($settingExists) {
            $setting = Setting::first();
        } else {
            $setting = new Setting;
        }

        $model = new AgentTransport;
        return view('admin.transport.form',compact('model','setting'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         // dd($request->all());
         $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator->errors())
                ->withInput($request->all());
        } else {

            $password = Str::random(8);

            // add new users
            $data = new AgentTransport();
            $data->company_name = $request->company_name;
            $data->mobile_phone = $request->mobile_phone;
            $data->email = $request->email;
            $data->password = Hash::make($password);
            $data->address = $request->address;
            $data->code = $password;
            $data->save();

            return redirect()
            ->route('dashboard.transport.index')
            ->with('message', 'Data saved!.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function invite($id)
    {
       
        $model = AgentTransport::find($id);
        $data = $model;

        if (env('APP_DEBUG') == 'false') {
        Mail::to($model->email)->send(new InviteTransport($data));
        }
        return redirect()->back()->with('message', 'Agent Transport invite!');
    }

    public function is_active($id,$ac)
    {
       
        $model = AgentTransport::find($id);
        $model->status = $ac;
        $model->save();

        return redirect()->back()->with('message', 'Agent Transport active!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $settingExists = Setting::exists();

        if ($settingExists) {
            $setting = Setting::first();
        } else {
            $setting = new Setting;
        }

        $model = AgentTransport::find($id);

        return view('admin.transport.form',compact('model','setting'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
         // dd($request->all());
         $validator = Validator::make($request->all(), [
            'email' => 'nullable'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator->errors())
                ->withInput($request->all());
        } else {


            // add new users
            $data = AgentTransport::find($id);
            $data->company_name = $request->company_name;
            $data->mobile_phone = $request->mobile_phone;
            $data->markup = $request->markup;
            $data->email = $request->email;
            $data->address = $request->address;
            $data->save();

            return redirect()
            ->route('dashboard.transport.index')
            ->with('message', 'Data saved!.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $model = AgentTransport::find($id);
        $model->delete();

        return redirect()
        ->route('dashboard.transport.index')
        ->with('message', 'Data Transport deleted.');
    }

    public function logintransport($id){
        // Auth::guard('agent_transport')->login($id);
        $agenttransport = AgentTransport::where('id', $id)->first();

        // If auth success
        $token = auth()->guard('agent_transport')->login($agenttransport);

        // Redirect atau respon sesuai kebutuhan setelah login berhasil
       $user = AgentTransport::select('id', 'address', 'code', 'company_name', 'created_at', 'email', 'markup', 'mobile_phone', 'status', 'updated_at')
        ->where('id', $id)
        ->first();

        return inertia('Transport/Profile',[
            'token' => $token,
            'user' => $user,
        ]);
    }

    public function report(Request $request){

        $settingExists = Setting::exists();

        if ($settingExists) {
            $setting = Setting::first();
        } else {
            $setting = new Setting;
        }

        $startDate = $request->startdate;
         $endDate = $request->enddate;
       // Konversi tanggal awal dan akhir ke format Carbon untuk pemrosesan
        $startDateCarbon = Carbon::parse($request->startdate)->startOfDay();
        $endDateCarbon = Carbon::parse($request->enddate)->endOfDay();

        // Query dengan filter gabungan, termasuk penanganan jika tanggal kosong
        $data = OrderTransport::whereHas('booking', function ($query) use ($startDateCarbon, $endDateCarbon, $startDate, $endDate) {
            $query->where(function ($query) use ($startDateCarbon, $endDateCarbon, $startDate, $endDate) {
                if ($startDate && $endDate) {
                    $query->whereBetween('booking_date', [$startDate, $endDate]);
                } elseif ($startDate) {
                    $query->where('booking_date', '>=', $startDate);
                } elseif ($endDate) {
                    $query->where('booking_date', '<=', $endDate);
                } else {
                    // Jika keduanya kosong, tampilkan semua data
                    // Tidak perlu menambahkan kondisi di sini karena tidak ada filter tambahan yang perlu ditambahkan
                }
            });
        })->get();

        $transport = TransportPickup::all();
        $bankAccount = TransportBankAccount::all();
        $widrawa = WidrawTransport::all();

        return view('admin.transport.report',compact('data','setting','transport','bankAccount','widrawa'));
    }

    public function widraw(Request $request)
    {
          //define validation rules
        $validator = Validator::make($request->all(), [
            'widraw' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:8048',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //upload image
        $image = $request->file('widraw');
        // $image->storeAs('public/images', $image->hashName());
        if ($image) {
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $filename);

            //create post
            $post = new WidrawTransport;
            $post->image = $filename;
            $post->transport_id = $request->transport_id;
            $post->ordertransport_id = $request->ordertransport_id;
            $post->total = $request->total;
            $post->save();
        }

        //return response
        return redirect()->back()->with('message', 'widraw success!');
    }
}
