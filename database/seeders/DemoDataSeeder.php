<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductCombo;
use App\Models\Recipe;
use App\Models\RecipeItem;
use App\Models\ExpenseCategory;
use App\Models\Client;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;

class DemoDataSeeder extends Seeder
{
    public function run()
    {
        $empresaId = 1; // Empresa de Prueba

        // Buscar unidades (o crearlas si no existen)
        $uKg = \App\Models\Unit::firstOrCreate(['empresa_id' => $empresaId, 'name' => 'kg'], ['nombre' => 'Kilogramo'])->id;
        $uUn = \App\Models\Unit::firstOrCreate(['empresa_id' => $empresaId, 'name' => 'unidad'], ['nombre' => 'Unidad'])->id;

        // 1. Insumos Textiles
        $tela = Product::create([
            'empresa_id' => $empresaId,
            'name' => '[DEMO] Tela Algodon 100%',
            'sku' => 'DEMO-INS-001',
            'price' => 0,
            'cost' => 8500,
            'stock' => 50,
            'usage_type' => 'raw_material',
            'is_sellable' => false,
            'unit_id' => $uKg, 
            'active' => true
        ]);

        $rip = Product::create([
            'empresa_id' => $empresaId,
            'name' => '[DEMO] RIP (Cuellos)',
            'sku' => 'DEMO-INS-002',
            'price' => 0,
            'cost' => 9500,
            'stock' => 10,
            'usage_type' => 'raw_material',
            'is_sellable' => false,
            'unit_id' => $uKg,
            'active' => true
        ]);

        // 2. Producto Final con Variantes (Remera)
        $remeraPadre = Product::create([
            'empresa_id' => $empresaId,
            'name' => '[DEMO] Remera Premium V-Neck',
            'sku' => 'DEMO-REM-001',
            'price' => 18500,
            'cost' => 5200,
            'stock' => 0,
            'has_variants' => true,
            'usage_type' => 'sell',
            'is_sellable' => true,
            'active' => true
        ]);

        $colores = ['Blanco', 'Negro'];
        $talles = ['S', 'M', 'L'];

        foreach ($colores as $color) {
            foreach ($talles as $talle) {
                ProductVariant::create([
                    'product_id' => $remeraPadre->id,
                    'color' => $color,
                    'size' => $talle,
                    'price' => 18500,
                    'stock' => 5, // Iniciamos con algo de stock
                    'barcode' => 'DEMO-' . strtoupper(substr($color, 0, 1)) . '-' . $talle
                ]);
            }
        }

        // 3. Producto con Variantes (Zapatos)
        $zapatoPadre = Product::create([
            'empresa_id' => $empresaId,
            'name' => '[DEMO] Zapato Cuero Urbano',
            'sku' => 'DEMO-ZAP-002',
            'price' => 45000,
            'cost' => 22000,
            'stock' => 0,
            'has_variants' => true,
            'usage_type' => 'sell',
            'is_sellable' => true,
            'active' => true
        ]);

        for ($t = 40; $t <= 42; $t++) {
            ProductVariant::create([
                'product_id' => $zapatoPadre->id,
                'color' => 'Marrón',
                'size' => (string)$t,
                'price' => 45000,
                'stock' => 3,
                'barcode' => 'DEMO-ZAP-' . $t
            ]);
        }

        // 4. Combo (1 Remera + 1 Zapato)
        $combo = Product::create([
            'empresa_id' => $empresaId,
            'name' => '[DEMO] Combo Outffit Profesional',
            'sku' => 'DEMO-COMBO-001',
            'price' => 55000, // Precio promocional
            'is_combo' => true,
            'usage_type' => 'sell',
            'is_sellable' => true,
            'active' => true
        ]);

        ProductCombo::create([
            'parent_product_id' => $combo->id,
            'child_product_id' => $remeraPadre->id,
            'quantity' => 1
        ]);

        ProductCombo::create([
            'parent_product_id' => $combo->id,
            'child_product_id' => $zapatoPadre->id,
            'quantity' => 1
        ]);

        // 5. Receta (Para la remera)
        $recipe = Recipe::create([
            'empresa_id' => $empresaId,
            'product_id' => $remeraPadre->id,
            'nombre' => 'Receta Estándar Remera V-Neck',
            'costo_adicional' => 1200, // Mano de obra (Corte + Confección)
            'is_active' => true
        ]);

        RecipeItem::create([
            'recipe_id' => $recipe->id,
            'component_product_id' => $tela->id,
            'quantity' => 0.5, // 0.5 kg de tela por remera
        ]);

        RecipeItem::create([
            'recipe_id' => $recipe->id,
            'component_product_id' => $rip->id,
            'quantity' => 0.02, // 20g de RIP por remera
        ]);

        // 6. Entidades de prueba
        Client::firstOrCreate([
            'empresa_id' => $empresaId,
            'nombre' => 'Juan Perez (Cliente Demo)',
            'cuit' => '20123456789'
        ]);

        Supplier::firstOrCreate([
            'empresa_id' => $empresaId,
            'nombre' => 'Textil Argentina (Proveedor Demo)',
            'cuit' => '30998877665'
        ]);
    }
}
