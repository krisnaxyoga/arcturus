<?php

namespace App\Http\Controllers\Api\Transport;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JWTAuth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\PackageTransport;
use App\Models\TransportPickup;
use App\Http\Resources\PostResource;
use App\Models\TransportDestination;

class PackageController extends Controller
{
    public function index(Request $request)
    {
         //get posts
         $iduser = auth()->guard('agent_transport')->id();
         $package = PackageTransport::where('transport_id',$iduser)->with('transportdestination')->get();

         //return collection of package as a resource
         return new PostResource(true, 'List Data User', $package);
    }

    public function destination(){
        $dastination = TransportDestination::all();
        return new PostResource(true, 'List Data Destination', $dastination);
    }

    public function store(Request $request) {
        // Define validation rules
        $validator = Validator::make($request->all(), [
            'type_car' => 'required',
            'destination' => 'required',

        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $iduser = auth()->guard('agent_transport')->id();

        // Create PackageTransport instance
        $PackageTransport = PackageTransport::create([
            'transport_id' => $iduser,
            'type_car' => $request->type_car,
            'destination' => $request->destination,
            'price' => $request->price,
            // 'number_police' => $request->number_police,
            'change_policy' => $request->change_policy,
            'cancellation_policy' => $request->cancellation_policy,
        ]);

        // Check if creation was successful
        if (!$PackageTransport) {
            return response()->json(['message' => 'Gagal membuat data post'], 500);
        }

        // Return response with success message and data
        return response()->json(['message' => 'Data Post Berhasil Ditambahkan!', 'data' => $PackageTransport], 201);
    }


    public function update(Request $request,$id){
            //define validation rules
            $validator = Validator::make($request->all(), [
                'type_car'     => 'required',
                'destination'   => 'required',
            ]);

            //check if validation fails
            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $PackageTransport = PackageTransport::find($id);
            //create post
            $PackageTransport->update([
                'type_car'     => $request->type_car,
                'destination'     => $request->destination,
                'price'   => $request->price,
                // 'number_police'   => $request->number_police,
                'change_policy' => $request->change_policy,
                'cancellation_policy' => $request->cancellation_policy,
            ]);

            //return response
            return new PostResource(true, 'Data Post Berhasil Diubah!', $PackageTransport);
    }

    public function show($id)
    {

        //find post by ID
        $post = PackageTransport::find($id);


        //return response
        return new PostResource(true, 'Data Package!', $post);
    }

    public function destroy($id)
    {

        //find post by ID
        $post = PackageTransport::find($id);

        //delete post
        $post->delete();

        //return response
        return new PostResource(true, 'Data Post Berhasil Dihapus!', null);
    }

    public function pickup(Request $request)
    {
          //define validation rules
        $validator = Validator::make($request->all(), [
            'pickup'     => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:8048',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //upload image
        $image = $request->file('pickup');
        // $image->storeAs('public/images', $image->hashName());
        if ($image) {
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $filename);

            //create post
            $post = new TransportPickup;
            $post->imgpickup = $filename;
            $post->transport_id = $request->transport_id;
            $post->ordertransport_id = $request->order_id;
            $post->status = 'on the way';
            $post->save();
        }

        //return response
        return new PostResource(true, 'Data Post Berhasil Ditambahkan!', $post);
    }

    public function checkin(Request $request, $id)
    {
        // Validasi
        $validator = Validator::make($request->all(), [
            'checkin' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:8048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Mengambil file gambar dari request
        $image = $request->file('checkin');

        // Memeriksa apakah file berhasil diambil dari request
        if ($image) {
            // Menyimpan file ke dalam folder publik/images dengan nama unik
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $filename);

            // Menyimpan informasi file ke dalam database atau di tempat yang diperlukan
            $post = TransportPickup::where('ordertransport_id', $id)->first();
            $post->imgpcheckin = $filename; // Menyimpan nama file ke dalam database
            $post->status = 'success';
            $post->save();

            // Mengembalikan response
            return new PostResource(true, 'Data Post Berhasil Ditambahkan!', $post);
        } else {
            // Jika file tidak berhasil diambil dari request
            return response()->json(['message' => 'Gagal mengunggah file.'], 500);
        }
    }

    public function transportpickup($id)
    {
        $data = TransportPickup::where('ordertransport_id',$id)->first();
        return new PostResource(true, 'Data pickup!', $data);
    }

}
