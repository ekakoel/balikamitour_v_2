<div class="alert alert-{{ $type }}">
    <ul>
        @foreach ($messages as $message)
            <li>{!! $message !!}</li>
        @endforeach
    </ul>
</div>