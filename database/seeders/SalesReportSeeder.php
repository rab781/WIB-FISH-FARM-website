<?php

namespace Database\Seeders;

use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\User;
use App\Models\Produk;
use App\Models\Ongkir;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class SalesReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // Get existing users, products, and shipping options
        $users = User::where('is_admin', false)->get();
        $products = Produk::all();
        $ongkirOptions = Ongkir::all();

        if ($users->isEmpty() || $products->isEmpty() || $ongkirOptions->isEmpty()) {
            $this->command->error('Please run UserSeeder, ProdukSeeder, and OngkirSeeder first!');
            return;
        }

        // Generate sales data for the last 2 years
        $startDate = Carbon::now()->subYears(2);
        $endDate = Carbon::now();

        $this->command->info('Generating sales data from ' . $startDate->format('Y-m-d') . ' to ' . $endDate->format('Y-m-d'));

        // Track order ID to avoid conflicts
        $orderCounter = Pesanan::max('id_pesanan') ?? 0;

        // Generate monthly sales with varying patterns
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addMonth()) {
            // Determine sales volume based on month (simulate seasonal patterns)
            $baseOrdersPerMonth = $this->getMonthlyOrderVolume($date->month);
            $ordersThisMonth = $faker->numberBetween($baseOrdersPerMonth - 10, $baseOrdersPerMonth + 15);

            $this->command->info("Generating {$ordersThisMonth} orders for " . $date->format('M Y'));

            // Generate orders for this month
            for ($i = 0; $i < $ordersThisMonth; $i++) {
                $orderCounter++;

                // Random date within the month
                $orderDate = $date->copy()->addDays($faker->numberBetween(0, $date->daysInMonth - 1))
                    ->setHour($faker->numberBetween(8, 22))
                    ->setMinute($faker->numberBetween(0, 59));

                // Select random user and shipping
                $user = $users->random();
                $ongkir = $ongkirOptions->random();

                // Determine order status (mostly completed for sales report)
                $status = $this->getOrderStatus($orderDate);

                // Calculate order details
                $orderDetails = $this->generateOrderDetails($products, $faker);
                $productTotal = collect($orderDetails)->sum('subtotal');
                $totalHarga = $productTotal + $ongkir->biaya;

                // Create the order
                $pesanan = Pesanan::create([
                    'id_pesanan' => $orderCounter,
                    'user_id' => $user->id,
                    'id_ongkir' => $ongkir->id_ongkir,
                    'total_harga' => $totalHarga,
                    'ongkir_biaya' => $ongkir->biaya,
                    'status_pesanan' => $status,
                    'metode_pembayaran' => $faker->randomElement(['Transfer Bank', 'E-Wallet']),                    'alamat_pengiriman' => $faker->address,
                    'catatan_penerimaan' => $faker->optional(0.3)->sentence(),
                    'created_at' => $orderDate,
                    'updated_at' => $orderDate,
                ]);

                // Add completion dates for completed orders
                if (in_array($status, ['Selesai', 'Dikirim'])) {
                    $completionDate = $orderDate->copy()->addDays($faker->numberBetween(1, 7));
                    $pesanan->update([
                        'tanggal_diterima' => $status === 'Selesai' ? $completionDate : null,
                        'tanggal_pengiriman' => $completionDate->copy()->subDays($faker->numberBetween(1, 3)),
                        'updated_at' => $completionDate,
                    ]);
                }

                // Create order details
                foreach ($orderDetails as $detail) {
                    DetailPesanan::create([
                        'id_pesanan' => $pesanan->id_pesanan,
                        'id_Produk' => $detail['product_id'],
                        'kuantitas' => $detail['quantity'],
                        'harga' => $detail['price'],
                        'subtotal' => $detail['subtotal'],
                        'created_at' => $orderDate,
                        'updated_at' => $orderDate,
                    ]);
                }
            }
        }

        $this->command->info('Sales report seeder completed successfully!');
        $this->command->info('Total orders created: ' . ($orderCounter - (Pesanan::max('id_pesanan') ?? 0) + $ordersThisMonth));
    }

    /**
     * Get order volume based on month (simulate seasonal patterns)
     */
    private function getMonthlyOrderVolume(int $month): int
    {
        // Simulate seasonal patterns
        $baseVolume = [
            1 => 45,  // January - New Year boost
            2 => 35,  // February - Post holiday dip
            3 => 40,  // March - Spring starts
            4 => 50,  // April - Good weather
            5 => 55,  // May - Peak spring
            6 => 60,  // June - Summer starts
            7 => 65,  // July - Peak summer
            8 => 60,  // August - Still summer
            9 => 50,  // September - Back to school
            10 => 45, // October - Autumn
            11 => 55, // November - Pre-holiday shopping
            12 => 70, // December - Holiday season
        ];

        return $baseVolume[$month];
    }

    /**
     * Determine order status based on order date
     */    private function getOrderStatus(Carbon $orderDate): string
    {
        $daysSinceOrder = $orderDate->diffInDays(Carbon::now());

        // Define valid status values
        if ($daysSinceOrder < 3) {
            // Recent orders are usually in early stages
            return collect([
                'Menunggu Pembayaran',
                'Pembayaran Dikonfirmasi',
                'Diproses'
            ])->random();
        }

        if ($daysSinceOrder < 30) {
            // Orders within last month are usually completed or in final stages
            return collect([
                'Diproses',
                'Selesai',
                'Selesai'
            ])->random();
        }

        // Older orders are usually completed, with some cancelled or refunded
        return collect([
            'Selesai',
            'Selesai',
            'Selesai',
            'Selesai',
            'Dibatalkan',
        ])->random();
    }

    /**
     * Generate realistic order details
     */
    private function generateOrderDetails($products, $faker): array
    {        $orderDetails = [];
        // Create weighted random selection manually
        $weights = [1 => 40, 2 => 30, 3 => 20, 4 => 10];
        $total = array_sum($weights);
        $rand = $faker->numberBetween(1, $total);

        $numItems = 1; // Default value
        $cumulative = 0;
        foreach ($weights as $value => $weight) {
            $cumulative += $weight;
            if ($rand <= $cumulative) {
                $numItems = $value;
                break;
            }
        }

        $selectedProducts = $products->random($numItems);

        foreach ($selectedProducts as $product) {            // Higher chance for lower quantities (weighted random selection)
            $qtyWeights = [1 => 50, 2 => 30, 3 => 15, 4 => 4, 5 => 1];
            $totalWeight = array_sum($qtyWeights);
            $rand = $faker->numberBetween(1, $totalWeight);

            $quantity = 1; // Default value
            $cumulative = 0;
            foreach ($qtyWeights as $value => $weight) {
                $cumulative += $weight;
                if ($rand <= $cumulative) {
                    $quantity = $value;
                    break;
                }
            }

            $price = $product->harga;
            $subtotal = $quantity * $price;

            $orderDetails[] = [
                'product_id' => $product->id_Produk,
                'quantity' => $quantity,
                'price' => $price,
                'subtotal' => $subtotal,
            ];
        }

        return $orderDetails;
    }
}
