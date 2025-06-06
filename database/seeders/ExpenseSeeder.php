<?php

namespace Database\Seeders;

use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ExpenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // Define expense categories and their typical amounts
        $expenseCategories = [
            'Pembelian Bahan Baku' => [500000, 2000000],
            'Biaya Operasional' => [100000, 500000],
            'Gaji Karyawan' => [1000000, 3000000],
            'Listrik & Air' => [200000, 800000],
            'Internet & Komunikasi' => [150000, 400000],
            'Pemeliharaan Kolam' => [300000, 1200000],
            'Pakan Ikan' => [400000, 1500000],
            'Transportasi' => [100000, 600000],
            'Peralatan & Perlengkapan' => [200000, 1000000],
            'Biaya Pemasaran' => [150000, 800000],
            'Sewa Tempat' => [1500000, 2500000],
            'Asuransi' => [200000, 500000],
        ];

        // Generate expenses for the last 2 years
        $startDate = Carbon::now()->subYears(2)->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        $this->command->info('Generating expense data from ' . $startDate->format('Y-m-d') . ' to ' . $endDate->format('Y-m-d'));

        // Generate monthly expenses
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addMonth()) {
            $this->command->info("Generating expenses for " . $date->format('M Y'));

            foreach ($expenseCategories as $category => $amountRange) {
                // Determine frequency of this expense type
                $frequency = $this->getExpenseFrequency($category);

                for ($i = 0; $i < $frequency; $i++) {
                    // Random date within the month
                    $expenseDate = $date->copy()->addDays($faker->numberBetween(0, $date->daysInMonth - 1))
                        ->setHour($faker->numberBetween(8, 17))
                        ->setMinute($faker->numberBetween(0, 59));

                    // Calculate amount based on category and add some randomness
                    $baseAmount = $faker->numberBetween($amountRange[0], $amountRange[1]);
                    $variationFactor = $faker->randomFloat(2, 0.8, 1.2); // ±20% variation
                    $amount = round($baseAmount * $variationFactor, -3); // Round to nearest thousand

                    // Generate description
                    $description = $this->generateExpenseDescription($category, $faker);

                    Expense::create([
                        'category' => $category,
                        'description' => $description,
                        'amount' => $amount,
                        'expense_date' => $expenseDate,
                        'created_at' => $expenseDate,
                        'updated_at' => $expenseDate,
                    ]);
                }
            }
        }

        $this->command->info('Expense seeder completed successfully!');
        $this->command->info('Total expenses created: ' . Expense::count());
    }

    /**
     * Get expense frequency per month based on category
     */
    private function getExpenseFrequency(string $category): int
    {
        $frequencies = [
            'Pembelian Bahan Baku' => 3,      // 3 times per month
            'Biaya Operasional' => 4,         // 4 times per month
            'Gaji Karyawan' => 1,             // Once per month
            'Listrik & Air' => 1,             // Once per month
            'Internet & Komunikasi' => 1,      // Once per month
            'Pemeliharaan Kolam' => 2,        // 2 times per month
            'Pakan Ikan' => 4,                // 4 times per month
            'Transportasi' => 3,              // 3 times per month
            'Peralatan & Perlengkapan' => 1,  // Once per month
            'Biaya Pemasaran' => 2,           // 2 times per month
            'Sewa Tempat' => 1,               // Once per month
            'Asuransi' => 1,                  // Once per month
        ];

        return $frequencies[$category] ?? 1;
    }

    /**
     * Generate realistic expense description
     */
    private function generateExpenseDescription(string $category, $faker): string
    {
        $descriptions = [
            'Pembelian Bahan Baku' => [
                'Pembelian indukan ikan koi kualitas premium',
                'Pengadaan bibit ikan koki import',
                'Pembelian ikan mas untuk budidaya',
                'Pengadaan indukan ikan nila berkualitas',
            ],
            'Biaya Operasional' => [
                'Biaya administrasi dan perizinan',
                'Biaya kebersihan dan sanitasi',
                'Pembelian bahan kimia kolam',
                'Biaya operasional harian',
            ],
            'Gaji Karyawan' => [
                'Gaji karyawan bulan ini',
                'Upah pekerja harian',
                'Tunjangan karyawan',
                'Bonus kinerja karyawan',
            ],
            'Listrik & Air' => [
                'Tagihan listrik bulan ini',
                'Biaya air PDAM',
                'Biaya pompa air kolam',
                'Tagihan PLN',
            ],
            'Internet & Komunikasi' => [
                'Tagihan internet wifi',
                'Biaya telepon kantor',
                'Paket data mobile',
                'Biaya komunikasi',
            ],
            'Pemeliharaan Kolam' => [
                'Perawatan sistem filter kolam',
                'Perbaikan pompa air',
                'Maintenance aerator',
                'Pembersihan kolam rutin',
            ],
            'Pakan Ikan' => [
                'Pembelian pakan ikan premium',
                'Pakan pellet ikan koi',
                'Pakan alami untuk ikan',
                'Vitamin dan suplemen ikan',
            ],
            'Transportasi' => [
                'Biaya pengiriman ikan',
                'Bensin kendaraan operasional',
                'Biaya ekspedisi',
                'Ongkos kirim customer',
            ],
            'Peralatan & Perlengkapan' => [
                'Pembelian jaring ikan',
                'Peralatan maintenance kolam',
                'Perlengkapan kantor',
                'Alat-alat budidaya',
            ],
            'Biaya Pemasaran' => [
                'Iklan online media sosial',
                'Biaya promosi website',
                'Cetak brosur dan katalog',
                'Biaya marketing digital',
            ],
            'Sewa Tempat' => [
                'Sewa lahan kolam',
                'Sewa gudang penyimpanan',
                'Sewa kantor',
                'Biaya sewa bulanan',
            ],
            'Asuransi' => [
                'Premi asuransi usaha',
                'Asuransi kendaraan',
                'Asuransi peralatan',
                'Biaya asuransi bulanan',
            ],
        ];

        $categoryDescriptions = $descriptions[$category] ?? ['Pengeluaran ' . strtolower($category)];

        return $faker->randomElement($categoryDescriptions);
    }
}
