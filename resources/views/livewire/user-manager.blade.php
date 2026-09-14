<div>
    <h1 class="h4 fw-semibold mb-4">Administración de Usuarios</h1>

    @if ($successMessage)
        <div class="alert alert-success">{{ $successMessage }}</div>
    @endif
    @error('delete') <div class="alert alert-danger">{{ $message }}</div> @enderror

    <div class="row g-4">
        <div class="col-12 col-lg-7">
            <div class="table-responsive">
                <table class="table table-bordered bg-white align-middle">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Escenarios</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->role }}</td>
                                <td>{{ $user->administeredScenarios->pluck('name')->join(', ') ?: '—' }}</td>
                                <td class="text-end">
                                    <button type="button" wire:click="edit({{ $user->id }})" class="btn btn-sm btn-outline-secondary">Editar</button>
                                    <button type="button" wire:click="delete({{ $user->id }})" wire:confirm="¿Eliminar a {{ $user->name }}?" class="btn btn-sm btn-outline-danger">Eliminar</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-12 col-lg-5">
            <div class="card">
                <div class="card-body">
                    <h2 class="h6 fw-medium mb-3">{{ $editingUserId ? 'Editar usuario' : 'Nuevo usuario' }}</h2>

                    <form wire:submit="save">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre</label>
                            <input type="text" wire:model="name" id="name" class="form-control @error('name') is-invalid @enderror">
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" wire:model="email" id="email" class="form-control @error('email') is-invalid @enderror">
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="role" class="form-label">Rol</label>
                            <select wire:model.live="role" id="role" class="form-select @error('role') is-invalid @enderror">
                                <option value="docente">Docente (solicitante)</option>
                                <option value="admin">Administrador de Escenario</option>
                                <option value="coordinador">Administrador General</option>
                            </select>
                            @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        @if ($role === 'admin')
                            <div class="mb-3">
                                <label class="form-label">Escenarios que administra</label>
                                <select wire:model="scenario_ids" multiple size="5" class="form-select">
                                    @foreach ($scenarios as $scenario)
                                        <option value="{{ $scenario->id }}">{{ $scenario->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">{{ $editingUserId ? 'Guardar cambios' : 'Crear usuario' }}</button>
                            @if ($editingUserId)
                                <button type="button" wire:click="resetForm" class="btn btn-outline-secondary">Cancelar</button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
