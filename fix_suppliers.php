<?php
\App\Models\Supplier::whereNull('empresa_id')->update(['empresa_id' => 4]);
echo "Proveedores fixed\n";
