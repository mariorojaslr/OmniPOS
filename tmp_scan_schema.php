<?php

use Illuminate\Support\Facades\Schema;

$tables = ['products', 'product_variants', 'rubros', 'units', 'clients', 'suppliers', 'recipes', 'recipe_items'];

foreach ($tables as $table) {
    if (Schema::hasTable($table)) {
        echo $table . ": " . implode(', ', Schema::getColumnListing($table)) . "\n";
    } else {
        echo $table . ": NO EXISTE\n";
    }
}
