<?php

namespace Database\Seeders;

use App\Models\User;
use App\Src\Inventory\Models\Product;
use App\Src\Inventory\Models\ProductCategory;
use App\Src\Inventory\Models\Stock;
use App\Src\Inventory\Models\SubcategoryProduct;
use App\Src\POS\Models\Card;
use App\Src\POS\Models\CardPlan;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Usuario Administrador ──
        User::factory()->create([
            'name' => 'Administrador',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
        ]);

        // ── Categorías ──
        $lubricentro = ProductCategory::create(['category_name' => 'Lubricentro', 'description' => 'Productos de lubricentro', 'status' => 'active']);
        $lavadero = ProductCategory::create(['category_name' => 'Lavadero', 'description' => 'Productos de lavadero', 'status' => 'active']);
        $repuestos = ProductCategory::create(['category_name' => 'Repuestos', 'description' => 'Repuestos generales', 'status' => 'active']);
        $servicios = ProductCategory::create(['category_name' => 'Servicios', 'description' => 'Servicios de mano de obra', 'status' => 'active']);

        // ── Subcategorías ──
        $aceites = SubcategoryProduct::create(['subcategory_name' => 'Aceites', 'description' => 'Aceites de motor y transmisión', 'status' => 'active']);
        $filtros = SubcategoryProduct::create(['subcategory_name' => 'Filtros', 'description' => 'Filtros de aire, aceite y combustible', 'status' => 'active']);
        $liquidos = SubcategoryProduct::create(['subcategory_name' => 'Líquidos', 'description' => 'Refrigerante, lavaparabrisas, etc.', 'status' => 'active']);
        $lavadoSub = SubcategoryProduct::create(['subcategory_name' => 'Lavado', 'description' => 'Shampoo, cera, silicona', 'status' => 'active']);
        $moSub = SubcategoryProduct::create(['subcategory_name' => 'Mano de Obra', 'description' => 'Servicios de instalación y trabajo', 'status' => 'active']);

        // ── Productos + Stock ──
        $products = [
            // Lubricentro - Aceites
            ['category_id' => $lubricentro->id, 'subcategory_id' => $aceites->id, 'product_name' => 'Aceite Shell Helix HX7 10W-40 4L', 'product_code' => 'ACE-001', 'bar_code' => '7791234560001', 'cost_price' => 18000, 'sale_price' => 25000, 'status' => 'active', 'stock' => 20],
            ['category_id' => $lubricentro->id, 'subcategory_id' => $aceites->id, 'product_name' => 'Aceite Mobil Super 2000 5W-30 4L', 'product_code' => 'ACE-002', 'bar_code' => '7791234560002', 'cost_price' => 22000, 'sale_price' => 30000, 'status' => 'active', 'stock' => 15],
            ['category_id' => $lubricentro->id, 'subcategory_id' => $aceites->id, 'product_name' => 'Aceite YPF Elaion F50 e 5W-30 4L', 'product_code' => 'ACE-003', 'bar_code' => '7791234560003', 'cost_price' => 16000, 'sale_price' => 22000, 'status' => 'active', 'stock' => 25],
            ['category_id' => $lubricentro->id, 'subcategory_id' => $aceites->id, 'product_name' => 'Aceite Castrol GTX 20W-50 1L', 'product_code' => 'ACE-004', 'bar_code' => '7791234560004', 'cost_price' => 5000, 'sale_price' => 7500, 'status' => 'active', 'stock' => 30],

            // Lubricentro - Filtros
            ['category_id' => $lubricentro->id, 'subcategory_id' => $filtros->id, 'product_name' => 'Filtro de Aceite XJ-200', 'product_code' => 'FIL-001', 'bar_code' => '7791234560010', 'cost_price' => 3500, 'sale_price' => 5500, 'status' => 'active', 'stock' => 40],
            ['category_id' => $lubricentro->id, 'subcategory_id' => $filtros->id, 'product_name' => 'Filtro de Aire AF-350 Toyota', 'product_code' => 'FIL-002', 'bar_code' => '7791234560011', 'cost_price' => 4000, 'sale_price' => 6500, 'status' => 'active', 'stock' => 25],
            ['category_id' => $lubricentro->id, 'subcategory_id' => $filtros->id, 'product_name' => 'Filtro de Combustible FC-120', 'product_code' => 'FIL-003', 'bar_code' => '7791234560012', 'cost_price' => 2800, 'sale_price' => 4500, 'status' => 'active', 'stock' => 35],
            ['category_id' => $lubricentro->id, 'subcategory_id' => $filtros->id, 'product_name' => 'Filtro de Habitáculo FH-VW', 'product_code' => 'FIL-004', 'bar_code' => '7791234560013', 'cost_price' => 3200, 'sale_price' => 5000, 'status' => 'active', 'stock' => 20],

            // Lubricentro - Líquidos
            ['category_id' => $lubricentro->id, 'subcategory_id' => $liquidos->id, 'product_name' => 'Refrigerante Prestone 50/50 1L', 'product_code' => 'LIQ-001', 'bar_code' => '7791234560020', 'cost_price' => 4500, 'sale_price' => 7000, 'status' => 'active', 'stock' => 18],
            ['category_id' => $lubricentro->id, 'subcategory_id' => $liquidos->id, 'product_name' => 'Líquido de Frenos DOT4 500ml', 'product_code' => 'LIQ-002', 'bar_code' => '7791234560021', 'cost_price' => 3000, 'sale_price' => 4800, 'status' => 'active', 'stock' => 22],
            ['category_id' => $lubricentro->id, 'subcategory_id' => $liquidos->id, 'product_name' => 'Agua Destilada 5L', 'product_code' => 'LIQ-003', 'bar_code' => '7791234560022', 'cost_price' => 1200, 'sale_price' => 2000, 'status' => 'active', 'stock' => 50],

            // Lavadero
            ['category_id' => $lavadero->id, 'subcategory_id' => $lavadoSub->id, 'product_name' => 'Shampoo Automotor Premium 5L', 'product_code' => 'LAV-001', 'bar_code' => '7791234560030', 'cost_price' => 5000, 'sale_price' => 8000, 'status' => 'active', 'stock' => 12],
            ['category_id' => $lavadero->id, 'subcategory_id' => $lavadoSub->id, 'product_name' => 'Cera en Pasta Brillo Max', 'product_code' => 'LAV-002', 'bar_code' => '7791234560031', 'cost_price' => 6000, 'sale_price' => 9500, 'status' => 'active', 'stock' => 10],
            ['category_id' => $lavadero->id, 'subcategory_id' => $lavadoSub->id, 'product_name' => 'Silicona para Tablero 400ml', 'product_code' => 'LAV-003', 'bar_code' => '7791234560032', 'cost_price' => 2500, 'sale_price' => 4000, 'status' => 'active', 'stock' => 30],

            // Repuestos
            ['category_id' => $repuestos->id, 'subcategory_id' => null, 'product_name' => 'Bujía NGK BKR6E (x1)', 'product_code' => 'REP-001', 'bar_code' => '7791234560040', 'cost_price' => 2500, 'sale_price' => 4000, 'status' => 'active', 'stock' => 60],
            ['category_id' => $repuestos->id, 'subcategory_id' => null, 'product_name' => 'Correa de Distribución Gates', 'product_code' => 'REP-002', 'bar_code' => '7791234560041', 'cost_price' => 15000, 'sale_price' => 22000, 'status' => 'active', 'stock' => 8],
            ['category_id' => $repuestos->id, 'subcategory_id' => null, 'product_name' => 'Pastillas de Freno Delanteras', 'product_code' => 'REP-003', 'bar_code' => '7791234560042', 'cost_price' => 12000, 'sale_price' => 18000, 'status' => 'active', 'stock' => 14],

            // Servicios (Mano de Obra)
            ['category_id' => $servicios->id, 'subcategory_id' => $moSub->id, 'product_name' => 'Servicio de Cambio de Aceite', 'product_code' => 'SRV-001', 'bar_code' => '7791234560050', 'cost_price' => 0, 'sale_price' => 5000, 'status' => 'active', 'stock' => 999],
            ['category_id' => $servicios->id, 'subcategory_id' => $moSub->id, 'product_name' => 'Servicio de Lavado Completo', 'product_code' => 'SRV-002', 'bar_code' => '7791234560051', 'cost_price' => 0, 'sale_price' => 8000, 'status' => 'active', 'stock' => 999],
            ['category_id' => $servicios->id, 'subcategory_id' => $moSub->id, 'product_name' => 'Servicio de Alineación y Balanceo', 'product_code' => 'SRV-003', 'bar_code' => '7791234560052', 'cost_price' => 0, 'sale_price' => 12000, 'status' => 'active', 'stock' => 999],
            ['category_id' => $servicios->id, 'subcategory_id' => $moSub->id, 'product_name' => 'Servicio de Scanner OBD', 'product_code' => 'SRV-004', 'bar_code' => '7791234560053', 'cost_price' => 0, 'sale_price' => 6000, 'status' => 'active', 'stock' => 999],
        ];

        foreach ($products as $data) {
            $stockQty = $data['stock'];
            unset($data['stock']);

            $product = Product::create($data);

            Stock::create([
                'product_id' => $product->id,
                'quantity' => $stockQty,
            ]);
        }

        // ── Tarjetas y Planes ──
        $visa = Card::create(['name' => 'Visa', 'type' => 'credit', 'is_active' => true]);
        CardPlan::create(['card_id' => $visa->id, 'name' => '1 Cuota', 'installments' => 1, 'surcharge_percentage' => 0, 'is_active' => true, 'is_promotion' => false]);
        CardPlan::create(['card_id' => $visa->id, 'name' => '3 Cuotas', 'installments' => 3, 'surcharge_percentage' => 8.75, 'is_active' => true, 'is_promotion' => false]);
        CardPlan::create(['card_id' => $visa->id, 'name' => '6 Cuotas', 'installments' => 6, 'surcharge_percentage' => 15.50, 'is_active' => true, 'is_promotion' => false]);
        CardPlan::create(['card_id' => $visa->id, 'name' => '12 Cuotas', 'installments' => 12, 'surcharge_percentage' => 28.00, 'is_active' => true, 'is_promotion' => false]);

        $mastercard = Card::create(['name' => 'Mastercard', 'type' => 'credit', 'is_active' => true]);
        CardPlan::create(['card_id' => $mastercard->id, 'name' => '1 Cuota', 'installments' => 1, 'surcharge_percentage' => 0, 'is_active' => true, 'is_promotion' => false]);
        CardPlan::create(['card_id' => $mastercard->id, 'name' => '3 Cuotas', 'installments' => 3, 'surcharge_percentage' => 9.00, 'is_active' => true, 'is_promotion' => false]);
        CardPlan::create(['card_id' => $mastercard->id, 'name' => '6 Cuotas', 'installments' => 6, 'surcharge_percentage' => 16.00, 'is_active' => true, 'is_promotion' => false]);

        $visaDebit = Card::create(['name' => 'Visa Débito', 'type' => 'debit', 'is_active' => true]);
        CardPlan::create(['card_id' => $visaDebit->id, 'name' => '1 Pago Débito', 'installments' => 1, 'surcharge_percentage' => 0, 'is_active' => true, 'is_promotion' => false]);

        $maestro = Card::create(['name' => 'Maestro', 'type' => 'debit', 'is_active' => true]);
        CardPlan::create(['card_id' => $maestro->id, 'name' => '1 Pago Débito', 'installments' => 1, 'surcharge_percentage' => 0, 'is_active' => true, 'is_promotion' => false]);
    }
}
