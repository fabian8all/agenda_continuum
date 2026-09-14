<div class="max-w-xl mx-auto p-6">
    <h1 class="text-xl font-semibold mb-4">Solicitud de Uso de Escenario</h1>

    @if ($successMessage)
        <div class="mb-4 rounded-md bg-green-50 text-green-800 px-4 py-3">
            {{ $successMessage }}
        </div>
    @endif

    <form wire:submit="submit" class="space-y-4">
        <div>
            <label for="scenario_id" class="block text-sm font-medium text-gray-700">Escenario</label>
            <select wire:model="scenario_id" id="scenario_id" class="mt-1 block w-full rounded-md border-gray-300">
                <option value="">Selecciona un escenario</option>
                @foreach ($scenarios as $scenario)
                    <option value="{{ $scenario->id }}">{{ $scenario->name }} (capacidad: {{ $scenario->capacity }})</option>
                @endforeach
            </select>
            @error('scenario_id') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="start_time" class="block text-sm font-medium text-gray-700">Fecha y hora de inicio</label>
            <input type="datetime-local" wire:model="start_time" id="start_time" class="mt-1 block w-full rounded-md border-gray-300">
            @error('start_time') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="end_time" class="block text-sm font-medium text-gray-700">Fecha y hora de fin</label>
            <input type="datetime-local" wire:model="end_time" id="end_time" class="mt-1 block w-full rounded-md border-gray-300">
            @error('end_time') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="description" class="block text-sm font-medium text-gray-700">Descripción</label>
            <textarea wire:model="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300"></textarea>
            @error('description') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-white">
            Enviar solicitud
        </button>
    </form>
</div>
