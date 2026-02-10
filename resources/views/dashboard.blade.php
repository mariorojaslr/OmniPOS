<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Panel de Empresa
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-sm rounded-lg p-6">

                <h3 class="text-lg font-bold mb-6">Módulos del Sistema</h3>

                <div style="display:flex; gap:15px; flex-wrap:wrap;">

                    <!-- POS -->
                    <a href="/empresa/pos"
                       style="padding:14px 20px; background:#0d6efd; color:white; text-decoration:none; border-radius:8px; font-weight:bold;">
                        🧾 POS
                    </a>

                    <!-- Productos -->
                    <a href="/empresa/products"
                       style="padding:14px 20px; background:#6f42c1; color:white; text-decoration:none; border-radius:8px; font-weight:bold;">
                        📦 Productos
                    </a>

                    <!-- Ventas -->
                    <a href="/ventas"
                       style="padding:14px 20px; background:#fd7e14; color:white; text-decoration:none; border-radius:8px; font-weight:bold;">
                        💰 Ventas
                    </a>

                    <!-- Usuarios -->
                    <a href="/usuarios"
                       style="padding:14px 20px; background:#20c997; color:white; text-decoration:none; border-radius:8px; font-weight:bold;">
                        👤 Usuarios
                    </a>

                    <!-- REPORTES -->
                    <a href="{{ route('reportes.empresa') }}"
                       style="padding:14px 20px; background:#198754; color:white; text-decoration:none; border-radius:8px; font-weight:bold;">
                        📊 Reportes Empresa
                    </a>

                </div>

            </div>

        </div>
    </div>
</x-app-layout>
