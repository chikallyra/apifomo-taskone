<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Pool;
use Tests\TestCase;
use App\Models\Product;
use App\Models\Order;

class RaceConditionTest extends TestCase
{
    // use RefreshDatabase;

    public function test_api_can_handle_race_condition_during_flash_sale()
    {
        // Setup: Buat 1 produk dengan stok terbatas
        $product = Product::create([
            'name' => 'Flash Sale Item',
            'price' => 1000,
            'inventory' => 5
        ]);

        $apiUrl = 'http://127.0.0.1:8000/api/orders';

        // Action: Tembakkan 20 request secara bersamaan (Asynchronous)
        $responses = Http::pool(fn (Pool $pool) => collect(range(1, 20))->map(function ($i) use ($pool, $apiUrl, $product) {
            return $pool->post($apiUrl, [
                'product_id' => $product->id,
                'quantity' => 1,
                'customer_name' => 'Customer ' . $i
            ]);
        }));

        // dd([
        //     'http_status' => $responses[0]->status(),
        //     'response_body' => $responses[0]->json()
        // ]);

        // Assertion: Hitung berapa response yang sukses (HTTP 201) dan gagal (HTTP 400)
        $successCount = 0;
        $failedCount = 0;

        foreach ($responses as $response) {
            if ($response->status() === 201) {
                $successCount++;
            } elseif ($response->status() === 400) {
                $failedCount++;
            }
        }

        // Ambil data produk terbaru dari database
        $product->refresh();

        // Syarat Lulus Test:
        // - Stok tidak boleh negatif (harus 0)
        $this->assertEquals(0, $product->inventory);

        // - Hanya boleh ada 5 order yang berhasil masuk database
        $this->assertEquals(5, Order::count());

        // - API harus merespon 5 sukses dan 15 gagal
        $this->assertEquals(5, $successCount);
        $this->assertEquals(15, $failedCount);
    }
}
