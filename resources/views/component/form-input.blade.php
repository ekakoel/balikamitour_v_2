<div class="form-group">
    <label for="{{ $id }}">@lang($label)</label>
    <input 
        type="{{ $type ?? 'text' }}" 
        id="{{ $id }}" 
        name="{{ $name }}" 
        class="form-control {{ $readonly ? 'readonly' : '' }} @error($name) is-invalid @enderror" 
        placeholder="@lang($placeholder)" 
        value="{{ $value }}" 
        {{ $readonly ? 'readonly' : '' }}
    >
    @error($name)
        <div class="alert alert-danger">
            {{ $message }}
        </div>
    @enderror
</div>
