@php
  $pickerId = $pickerId ?? 'institution_picker';
  $label = $label ?? 'Asal Institusi';
  $required = $required ?? false;
  $selectedInstitutionId = $selectedInstitutionId ?? null;
  $selectedInstitutionLabel = $selectedInstitutionLabel ?? null;
  $manualInstitutionName = $manualInstitutionName ?? null;
  $wrapperClass = $wrapperClass ?? 'col-md-6';
  $inputPlaceholder = $inputPlaceholder ?? 'Ketik nama institusi';
  $searchValue = old('institution_search', $selectedInstitutionLabel ?: $manualInstitutionName);
  $optionLabel = $selectedInstitutionLabel ?: $searchValue;
@endphp

@once
  <style>
    .institution-picker {
      position: relative;
    }

    .institution-picker-results {
      position: absolute;
      top: calc(100% + 0.45rem);
      left: 0;
      right: 0;
      z-index: 40;
      padding: 0.4rem;
      border: 1px solid rgba(148, 163, 184, 0.16);
      border-radius: 1rem;
      background: rgba(255, 255, 255, 0.98);
      box-shadow: 0 18px 36px rgba(15, 23, 42, 0.12);
      backdrop-filter: blur(14px);
      -webkit-backdrop-filter: blur(14px);
    }

    .institution-picker-option {
      width: 100%;
      padding: 0.78rem 0.85rem;
      border: 0;
      border-radius: 0.8rem;
      background: transparent;
      color: #334155;
      font-size: 0.92rem;
      font-weight: 600;
      text-align: left;
      transition: background 0.18s ease, color 0.18s ease;
      cursor: pointer;
    }

    .institution-picker-option:hover,
    .institution-picker-option:focus-visible {
      background: rgba(37, 99, 235, 0.08);
      color: #1d4ed8;
      outline: none;
    }

    .institution-picker-empty {
      padding: 0.78rem 0.85rem;
      color: #94a3b8;
      font-size: 0.88rem;
      line-height: 1.5;
    }

    .institution-picker-helper-strong {
      font-weight: 700;
    }
  </style>
@endonce

<div class="{{ $wrapperClass }}">
  <label for="{{ $pickerId }}_search" class="form-label">{{ $label }} @if($required)<span class="text-danger">*</span>@endif</label>
  <div class="institution-picker">
    <input
      id="{{ $pickerId }}_search"
      type="text"
      name="institution_search"
      class="form-control @if($errors->has('institution_id') || $errors->has('institution_manual_name')) is-invalid @endif"
      value="{{ $searchValue }}"
      placeholder="{{ $inputPlaceholder }}"
      autocomplete="off"
      data-institution-picker
      data-search-url="{{ route('institutions.search') }}"
      data-hidden-id="{{ $pickerId }}_id"
      data-hidden-manual="{{ $pickerId }}_manual"
      data-helper-id="{{ $pickerId }}_helper"
      data-results-id="{{ $pickerId }}_results"
      data-selected-label="{{ $selectedInstitutionLabel }}"
      @if($required) required @endif>
    <div id="{{ $pickerId }}_results" class="institution-picker-results d-none" role="listbox" aria-label="{{ $label }}"></div>
  </div>
  <input type="hidden" id="{{ $pickerId }}_id" name="institution_id" value="{{ old('institution_id', $selectedInstitutionId) }}">
  <input type="hidden" id="{{ $pickerId }}_manual" name="institution_manual_name" value="{{ old('institution_manual_name', $manualInstitutionName) }}">
  <div id="{{ $pickerId }}_helper" class="form-text">
    Ketik nama institusi. Jika cocok dengan master, pilih dari daftar. Jika belum ada, sistem akan menambahkannya ke daftar institusi agar bisa dipilih lagi nanti.
  </div>
  @error('institution_id')
    <div class="invalid-feedback d-block">{{ $message }}</div>
  @enderror
  @error('institution_manual_name')
    <div class="invalid-feedback d-block">{{ $message }}</div>
  @enderror
</div>
