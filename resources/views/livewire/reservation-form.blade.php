<div class="mx-auto" style="max-width: 36rem;">
    <h1 class="h4 fw-semibold mb-4">Solicitud de Uso de Escenario</h1>

    @if ($successMessage)
        <div class="alert alert-success" role="status">{{ $successMessage }}</div>
    @endif

    <form wire:submit="submit">
        <div class="mb-3">
            <label for="scenario_id" class="form-label">Escenario</label>
            <select wire:model="scenario_id" id="scenario_id" class="form-select @error('scenario_id') is-invalid @enderror" @error('scenario_id') aria-describedby="scenario_id-error" @enderror>
                <option value="">Selecciona un escenario</option>
                @foreach ($scenarios as $scenario)
                    <option value="{{ $scenario->id }}">{{ $scenario->name }} (capacidad: {{ $scenario->capacity }})</option>
                @endforeach
            </select>
            @error('scenario_id') <div id="scenario_id-error" class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="start_time" class="form-label">Fecha y hora de inicio</label>
            <input type="datetime-local" wire:model="start_time" id="start_time" class="form-control @error('start_time') is-invalid @enderror" @error('start_time') aria-describedby="start_time-error" @enderror>
            @error('start_time') <div id="start_time-error" class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="end_time" class="form-label">Fecha y hora de fin</label>
            <input type="datetime-local" wire:model="end_time" id="end_time" class="form-control @error('end_time') is-invalid @enderror" @error('end_time') aria-describedby="end_time-error" @enderror>
            @error('end_time') <div id="end_time-error" class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Descripción</label>
            <textarea wire:model="description" id="description" rows="3" class="form-control @error('description') is-invalid @enderror" @error('description') aria-describedby="description-error" @enderror></textarea>
            @error('description') <div id="description-error" class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn btn-primary">Enviar solicitud</button>
    </form>
</div>
