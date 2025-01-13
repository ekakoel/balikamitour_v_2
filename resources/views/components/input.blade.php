@props(['id', 'name', 'label', 'placeholder', 'value' => '','min','max', 'readonly' => false, 'type' => 'text'])

<div class="form-group">
    <label for="{{ $id }}">{{ __('messages.' . $label) }}</label>
    <input 
        type="{{ $type }}" 
        id="{{ $id }}" 
        name="{{ $name }}"
        min="{{ $min }}"
        max="{{ $max }}"
        class="form-control {{ $readonly ? 'readonly' : '' }} @error($name) is-invalid @enderror" 
        placeholder="{{ __('messages.' . $placeholder) }}" 
        value="{{ $value }}" 
        {{ $readonly ? 'readonly' : '' }}
    >
    @error($name)
        <div class="alert alert-danger">
            {{ $message }}
        </div>
    @enderror
</div>
