@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'text-sm text-danger dark:text-red-400 mt-2']) }}>
        @foreach ((array) $messages as $message)
            <li><i class="fa fa-exclamation-triangle text-danger"></i> {{ $message }}</li>
        @endforeach
    </ul>
@endif
