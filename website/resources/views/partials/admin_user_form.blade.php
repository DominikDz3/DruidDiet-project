@csrf
<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="name" class="form-label">Imię <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name ?? '') }}" required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="surname" class="form-label">Nazwisko <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('surname') is-invalid @enderror" id="surname" name="surname" value="{{ old('surname', $user->surname ?? '') }}" required>
            @error('surname')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="mb-3">
    <label for="email" class="form-label">Adres Email <span class="text-danger">*</span></label>
    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email ?? '') }}" required>
    @error('email')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="password" class="form-label">Hasło @if(!isset($user))<span class="text-danger">*</span>@else (pozostaw puste, aby nie zmieniać) @endif</label>
            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" @if(!isset($user)) required @endif>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Potwierdź Hasło @if(!isset($user))<span class="text-danger">*</span>@endif</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" @if(!isset($user)) required @endif>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="role" class="form-label">Rola <span class="text-danger">*</span></label>
            <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                @foreach($roles as $roleValue)
                    <option value="{{ $roleValue }}" {{ old('role', $user->role ?? '') == $roleValue ? 'selected' : '' }}>
                        {{ ucfirst($roleValue) }}
                    </option>
                @endforeach
            </select>
            @error('role')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="loyalty_points" class="form-label">Punkty lojalnościowe</label>
            <input type="number" class="form-control @error('loyalty_points') is-invalid @enderror" id="loyalty_points" name="loyalty_points" value="{{ old('loyalty_points', $user->loyalty_points ?? 0) }}" min="0">
            @error('loyalty_points')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>


<div class="mb-3">
    <label for="allergens" class="form-label">Alergeny (oddzielone przecinkami)</label>
    <textarea class="form-control @error('allergens') is-invalid @enderror" id="allergens" name="allergens" rows="3">{{ old('allergens', $user->allergens ?? '') }}</textarea>
    @error('allergens')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mt-4">
    <button type="submit" class="btn btn-primary">{{ isset($user) ? 'Zaktualizuj użytkownika' : 'Utwórz użytkownika' }}</button>
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Anuluj</a>
</div>
