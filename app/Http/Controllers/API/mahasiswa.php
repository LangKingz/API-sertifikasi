<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\mahasiswa as ModelsMahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class mahasiswa extends Controller
{
    public function index()
    {
        $data = ModelsMahasiswa::all();
        return response()->json([
            'status' => 200,
            'message' => 'berhasil',
            'data' => $data
        ]);
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nim' => 'required|max:16|unique:mahasiswas,nim',
                'nama' => 'required',
                'email' => 'required|email|unique:mahasiswas,email',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 400,
                    'message' => $validator->errors()

                ], 400);
            }

            $product = ModelsMahasiswa::create($request->all());
            return response()->json([
                'status' => 200,
                'message' => 'Success membuat data',
                'data' => $product
            ], 201);
        } catch (\Throwable $th) {
            return response()->json($th, 500);
        }
    }

    public function detail($id)
    {
        $product = ModelsMahasiswa::findOrFail($id);
        if (!$product) {
            return response()->json([
                'status' => 400,
                'message' => 'not found data'
            ], 400);
        }

        return response()->json([
            'status' => 200,
            'message' => 'berhasil mendapatkan data',
            'data' => $product
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $data = ModelsMahasiswa::findOrFail($id);
        if (!$data) {
            return response()->json([
                'status' => 400,
                'message' => 'not found data'
            ], 400);
        }
        $data->update($request->all());
        return response()->json($data);
    }

    public function delete($id)
    {
        $product = ModelsMahasiswa::findOrFail($id);
        if (!$product) {
            return response()->json([
                'status' => 400,
                'message' => 'not found data'
            ], 400);
        }

        $product->delete();
        return response()->json([
            'status' => 200,
            'message' => 'berhasil menghapus data'
        ], 200);
    }
}
