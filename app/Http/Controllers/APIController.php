<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Http\Request;

class APIController extends Controller
{
    public function index(){
        //Ambil semua data produk
        $data = Product::all();

        //Kembalikan response JSON sukses dengan HTTP status 200
        return response()->json([
            'success' => true,
            'message' => 'Daftar produk berhasil diambil.',
            'data' => $data
        ], 200);
    }

    public function store_product(Request $request){
        //Validasi input
        $request->validate([
            'name' => 'required|string',
            'price' => 'required',
            'inventory' => 'required|min:1'
        ]);

        try{
            //Buat data Produk baru
            $data = Product::create([
                'name' => $request->name,
                'price' => $request->price,
                'inventory' => $request->inventory
            ]);

            //Kembalikan response JSON sukses dengan HTTP status 201
            return response()->json([
                'success' => true,
                'message' => 'Data produk berhasil dibuat.',
                'data' => $data
            ], 201);
        }catch(Exception $e){
            //Tangkap error (misal jika stok habis) dan kembalikan JSON dengan HTTP 400
            return response()->json([
                'success' => false,
                'error' => 'Bad Request',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function checkout(Request $request){
        //Validasi Input
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|min:1',
            'customer_name' => 'required|string'
        ]);

        try{
            //Memulai transaksi database untuk menjamin konsistensi data
            $order = DB::transaction(function () use ($request) {
                //Ambil data produk dan kunci baris agar tidak bisa diedit request lain (Race Condition Handle)
                $product = Product::where('id', $request->product_id)->lockForUpdate()->first();

                //Cek stok untuk mencegarh inventory minus
                if($product->inventory < $request->quantity){
                    throw new Exception('Stok produk tidak mencukupi atau sudah habis.');
                }

                //Kurangi stok produk dan simpan
                $product->inventory -= $request->quantity;
                $product->save();

                //Buat order baru
                $newOrder = Order::create([
                    'customer_name' => $request->customer_name,
                    'status' => 'success'
                ]);

                //Buat Order Item (dengan memenuhi syarat minimal 1 Order Item per Order)
                OrderItem::create([
                    'order_id' => $newOrder->id,
                    'product_id' => $product->id,
                    'quantity' => $request->quantity
                ]);

                return $newOrder;
            });

            //Kembalikan response JSON sukses dengan HTTP status 201
            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dibuat.',
                'data' => $order
            ], 201);

        }catch(Exception $e){
            //Tangkap error (misal jika stok habis) dan kembalikan JSON dengan HTTP 400
            return response()->json([
                'success' => false,
                'error' => 'Bad Request',
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
