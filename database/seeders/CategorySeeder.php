<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $incomes = [
            ['name' => 'Gaji', 'type' => 'income', 'icon' => 'fas fa-wallet'],
            ['name' => 'Bonus', 'type' => 'income', 'icon' => 'fas fa-gift'],
            ['name' => 'Freelance', 'type' => 'income', 'icon' => 'fas fa-laptop-code'],
            ['name' => 'THR', 'type' => 'income', 'icon' => 'fas fa-money-bill-wave'],
            ['name' => 'Hadiah', 'type' => 'income', 'icon' => 'fas fa-hand-holding-heart'],
            ['name' => 'Lainnya', 'type' => 'income', 'icon' => 'fas fa-coins'],
        ];

        $expenses = [
            ['name' => 'Makan', 'type' => 'expense', 'icon' => 'fas fa-utensils'],
            ['name' => 'Transportasi', 'type' => 'expense', 'icon' => 'fas fa-car'],
            ['name' => 'Belanja', 'type' => 'expense', 'icon' => 'fas fa-shopping-cart'],
            ['name' => 'Hiburan', 'type' => 'expense', 'icon' => 'fas fa-film'],
            ['name' => 'Kesehatan', 'type' => 'expense', 'icon' => 'fas fa-heartbeat'],
            ['name' => 'Pendidikan', 'type' => 'expense', 'icon' => 'fas fa-graduation-cap'],
            ['name' => 'Tagihan', 'type' => 'expense', 'icon' => 'fas fa-file-invoice-dollar'],
            ['name' => 'Internet', 'type' => 'expense', 'icon' => 'fas fa-wifi'],
            ['name' => 'Pulsa', 'type' => 'expense', 'icon' => 'fas fa-mobile-alt'],
            ['name' => 'Lainnya', 'type' => 'expense', 'icon' => 'fas fa-box'],
        ];

        foreach ($incomes as $category) {
            Category::create($category);
        }

        foreach ($expenses as $category) {
            Category::create($category);
        }
    }
}
