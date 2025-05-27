@csrf

<div class="mb-3">
    <label for="title" class="form-label">Tytuł cateringu</label>
    <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $catering->title ?? '') }}" required>
    @error('title') <div class="text-danger">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label for="description" class="form-label">Opis</label>
    <textarea class="form-control" id="description" name="description" rows="5" required>{{ old('description', $catering->description ?? '') }}</textarea>
    @error('description') <div class="text-danger">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label for="type" class="form-label">Typ cateringu</label>
    <input type="text" class="form-control" id="type" name="type" value="{{ old('type', $catering->type ?? '') }}" required>
    @error('type') <div class="text-danger">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label for="elements" class="form-label">Skład</label>
    <textarea class="form-control" id="elements" name="elements" rows="3">{{ old('elements', $catering->elements ?? '') }}</textarea>
    @error('elements') <div class="text-danger">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label for="allergens" class="form-label">Alergeny</label>
    <textarea class="form-control" id="allergens" name="allergens" rows="3">{{ old('allergens', $catering->allergens ?? '') }}</textarea>
    @error('allergens') <div class="text-danger">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label for="price" class="form-label">Cena (zł)</label>
    <input type="number" step="0.01" class="form-control" id="price" name="price" value="{{ old('price', $catering->price ?? '') }}" required>
    @error('price') <div class="text-danger">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label for="photo" class="form-label">Zdjęcie cateringu</label>
    @if (!empty($catering->photo))
        <div class="mb-2">
            <p>Aktualne zdjęcie:</p>
            <img src="{{ Storage::disk('public_images')->url($catering->photo) }}" alt="Aktualne zdjęcie" style="max-width: 200px; height: auto; object-fit: cover; border-radius: 4px;">
        </div>
        <div class="form-check mb-2">
            <input class="form-check-input" type="checkbox" name="remove_photo" id="remove_photo" value="1">
            <label class="form-check-label" for="remove_photo">Usuń aktualne zdjęcie</label>
        </div>
    @endif
    <input class="form-control" type="file" id="photo" name="photo" accept="image/*">
    <small class="form-text text-muted">Maksymalny rozmiar: 2MB. Obsługiwane formaty: JPG, PNG, GIF, SVG.</small>
    @error('photo') <div class="text-danger">{{ $message }}</div> @enderror
</div>

<button type="submit" class="btn btn-primary">Zapisz</button>
<a href="{{ route('admin.caterings.index') }}" class="btn btn-secondary">Anuluj</a>