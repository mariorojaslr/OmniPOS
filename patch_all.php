<?php
// patch_all.php - Parcheador inteligente temporal para entorno de producción
echo "--- INICIANDO PARCHEADO ULTRA-RÁPIDO EN SERVIDOR ---\n";

// 1. Parche de rutas
$webRoutesFile = 'routes/web.php';
if (file_exists($webRoutesFile)) {
    $content = file_get_contents($webRoutesFile);
    $old = "Route::get('/demo-experience', function() {";
    $pos = strpos($content, $old);
    if ($pos === false) {
        $old = 'Route::get("/demo-experience", function() {';
        $pos = strpos($content, $old);
    }
    if ($pos !== false) {
        $endPos = strpos($content, "})->name('demo.mode');", $pos);
        if ($endPos === false) {
            $endPos = strpos($content, '})->name("demo.mode");', $pos);
        }
        if ($endPos !== false) {
            $endPos += strlen("})->name('demo.mode');");
            $newRoute = "Route::get('/demo-experience', function() {
    \$demoEmpresa = \App\Models\Empresa::find(2);
    if (!\$demoEmpresa) {
        \$demoEmpresa = \App\Models\Empresa::updateOrCreate(
            ['id' => 2],
            ['nombre_comercial' => 'Sanatorio del Sol (Demo)', 'cuit' => '30-22222222-9', 'activo' => true, 'slug' => 'clinica-demo']
        );
    }
    \$demoUser = \App\Models\User::where('email', 'demo@poolhub.com')->first();
    if (!\$demoUser) {
        \$demoUser = \App\Models\User::updateOrCreate(
            ['email' => 'demo@poolhub.com'],
            ['name' => 'Dr. Leandro Gómez (Admin Demo)', 'password' => \Illuminate\Support\Facades\Hash::make('admin123'), 'role' => 'empresa', 'empresa_id' => \$demoEmpresa->id, 'activo' => true, 'status' => 'activo']
        );
    }
    \Auth::login(\$demoUser);
    return redirect()->route('empresa.dashboard')->with('success', 'Bienvenido a la Demo de Recepción Médica. Explora todas las funciones.');
})->name('demo.mode');";
            $content = substr_replace($content, $newRoute, $pos, $endPos - $pos);
            file_put_contents($webRoutesFile, $content);
            echo "✅ 1. Ruta /demo-experience parcheada con éxito.\n";
        } else {
            echo "❌ 1. No se encontró el final de la ruta demo.mode.\n";
        }
    } else {
        echo "❌ 1. No se encontró el inicio de la ruta /demo-experience.\n";
    }
} else {
    echo "❌ 1. No se encuentra routes/web.php.\n";
}

// 2. Parche de Listado de Empresas en Vista de Owner
$ownerEmpresasFile = 'resources/views/owner/empresas/index.blade.php';
if (file_exists($ownerEmpresasFile)) {
    $content = file_get_contents($ownerEmpresasFile);
    $oldStrMatch = '<div class="fw-bold text-dark">{{ $empresa->nombre_comercial }}</div>';
    $pos = strpos($content, $oldStrMatch);
    if ($pos === false) {
        $oldStrMatch = '<div class="fw-bold">{{ $empresa->nombre_comercial }}</div>';
        $pos = strpos($content, $oldStrMatch);
    }
    if ($pos !== false) {
        $newStr = '<div class="d-flex align-items-center gap-2">
                                <span class="badge bg-secondary rounded-pill px-2 py-1" style="font-size: 10px; background-color: #64748b !important;">ID: {{ $empresa->id }}</span>
                                <div class="fw-bold text-dark">{{ $empresa->nombre_comercial }}</div>
                            </div>';
        $content = str_replace($oldStrMatch, $newStr, $content);
        file_put_contents($ownerEmpresasFile, $content);
        echo "✅ 2. ID agregada al listado de empresas del Owner con éxito.\n";
    } else {
        echo "❌ 2. No se encontró la etiqueta de nombre comercial en el listado de empresas.\n";
    }
} else {
    echo "❌ 2. No se encuentra resources/views/owner/empresas/index.blade.php.\n";
}

// 3. Parche de Facturación en Vista de Owner
$ownerFacturacionFile = 'resources/views/owner/facturacion/index.blade.php';
if (file_exists($ownerFacturacionFile)) {
    $content = file_get_contents($ownerFacturacionFile);
    $oldStrMatch = '<span class="fw-bold h5 mb-0">{{ $empresa->nombre_comercial }}</span>';
    $pos = strpos($content, $oldStrMatch);
    if ($pos !== false) {
        $newStr = '<div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-secondary rounded-pill px-2 py-1" style="font-size: 10px; background-color: #64748b !important;">ID: {{ $empresa->id }}</span>
                                        <span class="fw-bold h5 mb-0">{{ $empresa->nombre_comercial }}</span>
                                    </div>';
        $content = str_replace($oldStrMatch, $newStr, $content);
        file_put_contents($ownerFacturacionFile, $content);
        echo "✅ 3. ID agregada a la lista de facturación del Owner con éxito.\n";
    } else {
        echo "❌ 3. No se encontró el nombre comercial en el acordeón de facturación.\n";
    }
} else {
    echo "❌ 3. No se encuentra resources/views/owner/facturacion/index.blade.php.\n";
}

echo "--- PROCESO DE PARCHEADO FINALIZADO ---\n";
