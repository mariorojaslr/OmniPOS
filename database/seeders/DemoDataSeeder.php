<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductCombo;
use App\Models\Recipe;
use App\Models\RecipeItem;
use App\Models\Client;
use App\Models\Supplier;
use App\Models\Unit;
use App\Models\Rubro;
use App\Models\ProductImage;
use App\Models\ProductionOrder;
use App\Models\User;

class DemoDataSeeder extends Seeder
{
    public function run()
    {
        $empresaId = 1; // Empresa de Prueba
        $userId = User::where('empresa_id', $empresaId)->first()?->id ?? 1;

        // 1. Unidades (Dinámicas)
        $uKg = Unit::firstOrCreate(['empresa_id' => $empresaId, 'short_name' => 'kg'], ['name' => 'Kilogramos'])->id;
        $uUn = Unit::firstOrCreate(['empresa_id' => $empresaId, 'short_name' => 'unidad'], ['name' => 'Unidades'])->id;

        // 2. Rubros (Dinámicos)
        $rIns = Rubro::firstOrCreate(['empresa_id' => $empresaId, 'nombre' => 'Insumos Textiles'], ['activo' => true])->id;
        $rInd = Rubro::firstOrCreate(['empresa_id' => $empresaId, 'nombre' => 'Indumentaria Premium'], ['activo' => true])->id;

        // 3. Insumos Textiles
        $tela = Product::create([
            'empresa_id' => $empresaId,
            'rubro_id' => $rIns,
            'name' => '[DEMO] Tela Algodon 100%',
            'barcode' => 'DEMO-INS-001',
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
            'rubro_id' => $rIns,
            'name' => '[DEMO] RIP (Cuellos)',
            'barcode' => 'DEMO-INS-002',
            'sku' => 'DEMO-INS-002',
            'price' => 0,
            'cost' => 9500,
            'stock' => 10,
            'usage_type' => 'raw_material',
            'is_sellable' => false,
            'unit_id' => $uKg,
            'active' => true
        ]);

        // 4. Producto Final con Variantes (Remera)
        $remeraPadre = Product::create([
            'empresa_id' => $empresaId,
            'rubro_id' => $rInd,
            'name' => '[DEMO] Remera Premium V-Neck',
            'barcode' => 'DEMO-REM-001',
            'sku' => 'DEMO-REM-001',
            'price' => 18500,
            'cost' => 5200,
            'stock' => 0,
            'has_variants' => true,
            'usage_type' => 'sell',
            'is_sellable' => true,
            'unit_id' => $uUn,
            'active' => true
        ]);

        foreach (['Blanco', 'Negro'] as $color) {
            foreach (['S', 'M', 'L'] as $talle) {
                ProductVariant::create([
                    'product_id' => $remeraPadre->id,
                    'color' => $color,
                    'size' => $talle,
                    'price' => 18500,
                    'stock' => 5,
                    'sku' => 'DEMO-' . strtoupper(substr($color, 0, 1)) . '-' . $talle,
                    'barcode' => 'DEMO-' . strtoupper(substr($color, 0, 1)) . '-' . $talle
                ]);
            }
        }

        // 5. Calzado
        $zapatoPadre = Product::create([
            'empresa_id' => $empresaId,
            'rubro_id' => $rInd,
            'name' => '[DEMO] Zapato Cuero Urbano',
            'barcode' => 'DEMO-ZAP-002',
            'sku' => 'DEMO-ZAP-002',
            'price' => 45000,
            'cost' => 22000,
            'stock' => 0,
            'has_variants' => true,
            'usage_type' => 'sell',
            'is_sellable' => true,
            'unit_id' => $uUn,
            'active' => true
        ]);

        for ($t = 40; $t <= 42; $t++) {
            ProductVariant::create([
                'product_id' => $zapatoPadre->id,
                'color' => 'Marrón',
                'size' => (string)$t,
                'price' => 45000,
                'stock' => 3,
                'sku' => 'DEMO-ZAP-' . $t,
                'barcode' => 'DEMO-ZAP-' . $t
            ]);
        }

        // 6. Combo
        $combo = Product::create([
            'empresa_id' => $empresaId,
            'rubro_id' => $rInd,
            'name' => '[DEMO] Combo Outffit Profesional',
            'barcode' => 'DEMO-COMBO-001',
            'sku' => 'DEMO-COMBO-001',
            'price' => 55000,
            'is_combo' => true,
            'usage_type' => 'sell',
            'is_sellable' => true,
            'unit_id' => $uUn,
            'active' => true
        ]);

        ProductCombo::create(['parent_product_id' => $combo->id, 'child_product_id' => $remeraPadre->id, 'quantity' => 1]);
        ProductCombo::create(['parent_product_id' => $combo->id, 'child_product_id' => $zapatoPadre->id, 'quantity' => 1]);

        // 7. Receta
        $recipe = Recipe::create([
            'empresa_id' => $empresaId,
            'product_id' => $remeraPadre->id,
            'name' => 'Receta Estándar Remera V-Neck',
            'is_active' => true
        ]);

        RecipeItem::create(['recipe_id' => $recipe->id, 'component_product_id' => $tela->id, 'quantity' => 0.5, 'unit_id' => $uKg]);
        RecipeItem::create(['recipe_id' => $recipe->id, 'component_product_id' => $rip->id, 'quantity' => 0.02, 'unit_id' => $uKg]);

        // 8. Entidades
        Client::firstOrCreate(['empresa_id' => $empresaId, 'name' => 'Juan Perez (Cliente Demo)'], ['document' => '20123456789']);
        Supplier::firstOrCreate(['empresa_id' => $empresaId, 'name' => 'Textil Argentina (Proveedor Demo)'], ['document' => '30998877665']);

        // 9. Imágenes (Visual Wow)
        ProductImage::updateOrCreate(['product_id' => $remeraPadre->id], ['path' => 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?auto=format&fit=crop&q=80&w=800', 'is_main' => true]);
        ProductImage::updateOrCreate(['product_id' => $zapatoPadre->id], ['path' => 'https://images.unsplash.com/photo-1533867617858-e7b97e060509?auto=format&fit=crop&q=80&w=800', 'is_main' => true]);
        ProductImage::updateOrCreate(['product_id' => $combo->id], ['path' => 'https://images.unsplash.com/photo-1489987707025-afc232f7ea0f?auto=format&fit=crop&q=80&w=800', 'is_main' => true]);
        ProductImage::updateOrCreate(['product_id' => $tela->id], ['path' => 'https://images.unsplash.com/photo-1528459801416-a9e53bbf4e17?auto=format&fit=crop&q=80&w=800', 'is_main' => true]);

        // 10. Órdenes de Producción
        ProductionOrder::create([
            'empresa_id'   => $empresaId,
            'user_id'      => $userId,
            'recipe_id'    => $recipe->id,
            'quantity'     => 50,
            'status'       => 'completada',
            'completed_at' => now(),
            'notes'        => 'Lote inicial de stock para demo'
        ]);

        ProductionOrder::create([
            'empresa_id'   => $empresaId,
            'user_id'      => $userId,
            'recipe_id'    => $recipe->id,
            'quantity'     => 100,
            'status'       => 'pendiente',
            'notes'        => 'Planificación para próxima semana'
        ]);
    }
}
