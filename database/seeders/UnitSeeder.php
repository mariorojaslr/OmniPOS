<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Unit;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = [
            ['name' => 'Unidades', 'short_name' => 'U', 'empresa_id' => null, 'is_global' => true],
            ['name' => 'Kilogramos', 'short_name' => 'Kg', 'empresa_id' => null, 'is_global' => true],
            ['name' => 'Gramos', 'short_name' => 'Gr', 'empresa_id' => null, 'is_global' => true],
            ['name' => 'Litros', 'short_name' => 'Lts', 'empresa_id' => null, 'is_global' => true],
            ['name' => 'Mililitros', 'short_name' => 'Ml', 'empresa_id' => null, 'is_global' => true],
            ['name' => 'Metros', 'short_name' => 'Mts', 'empresa_id' => null, 'is_global' => true],
        ];

        foreach ($units as $u) {
            Unit::updateOrCreate(['short_name' => $u['short_name']], $u);
        }
    }
}
