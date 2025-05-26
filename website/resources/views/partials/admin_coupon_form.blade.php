@csrf
<div class="row">
    @if(!isset($coupon))
    <div class="col-md-6 mb-3">
        <label for="code_type" class="form-label">Typ generowania kodu <span class="text-danger">*</span></label>
        <select class="form-select @error('code_type') is-invalid @enderror" id="code_type" name="code_type">
            <option value="auto" {{ old('code_type') == 'auto' ? 'selected' : '' }}>Automatycznie (8 znaków)</option>
            <option value="manual" {{ old('code_type') == 'manual' ? 'selected' : '' }}>Ręcznie</option>
        </select>
        @error('code_type')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    @endif

    <div class="col-md-6 mb-3" id="manual_code_field" style="{{ old('code_type', isset($coupon) ? 'manual' : 'auto') === 'manual' ? '' : 'display:none;' }}">
        <label for="code" class="form-label">Kod rabatowy <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code', $coupon->code ?? '') }}" {{ isset($coupon) || old('code_type', 'auto') === 'manual' ? 'required' : '' }} maxlength="50" style="text-transform:uppercase">
        @error('code')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    @if(isset($coupon))
    <div class="col-md-6 mb-3" id="display_code_field">
        <label class="form-label">Kod rabatowy</label>
        <input type="text" class="form-control" value="{{ $coupon->code }}" readonly>
    </div>
    @endif
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="discount_value_percentage" class="form-label">Rabat (%) <span class="text-danger">*</span></label>
        <div class="input-group">
            <input type="number" class="form-control @error('discount_value_percentage') is-invalid @enderror" id="discount_value_percentage" name="discount_value_percentage" value="{{ old('discount_value_percentage', isset($coupon) ? $coupon->discount_value * 100 : '') }}" min="1" max="100" step="0.01" required>
            <span class="input-group-text">%</span>
        </div>
        @error('discount_value_percentage')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="mt-4">
    <button type="submit" class="btn btn-primary">{{ isset($coupon) ? 'Zaktualizuj kod' : 'Wygeneruj kod' }}</button>
    <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">Anuluj</a>
</div>

@if(!isset($coupon))
@push('scripts')
<script src="{{ asset('js/coupon-edit.js') }}" defer></script>
@endpush
@endif
