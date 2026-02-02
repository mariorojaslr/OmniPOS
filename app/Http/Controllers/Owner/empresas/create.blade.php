<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Nueva empresa
        </h2>
    </x-slot>

    <div class="py-8 max-w-xl mx-auto sm:px-6 lg:px-8">
        <form method="POST" action="{{ route('owner.empresas.store') }}"
              class="bg-white p-6 rounded-xl shadow space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium">Nombre comercial</label>
                <input name="nombre_comercial" required
                       class="w-full mt-1 rounded-lg border-gray-300" />
            </div>

            <div>
                <label class="block text-sm font-medium">Email</label>
                <input name="email" type="email"
                       class="w-full mt-1 rounded-lg border-gray-300" />
            </div>

            <div>
                <label class="block text-sm font-medium">Teléfono</label>
                <input name="telefono"
                       class="w-full mt-1 rounded-lg border-gray-300" />
            </div>

            <div>
                <label class="block text-sm font-medium">Fecha de vencimiento</label>
                <input name="fecha_vencimiento" type="date"
                       class="w-full mt-1 rounded-lg border-gray-300" />
            </div>

            <div class="flex justify-end gap-2 pt-4">
                <a href="{{ route('owner.empresas.index') }}"
                   class="px-4 py-2 rounded-lg border">
                    Cancelar
                </a>

                <button class="bg-black text-white px-4 py-2 rounded-lg">
                    Crear empresa
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
