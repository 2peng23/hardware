<!-- Greeting -->
@if (!empty($greeting))
    <h1>{{ $greeting }}</h1>
@else
    @if ($level === 'error')
        <h1>@lang('Whoops!')</h1>
    @else
        <h1>@lang('Hello!')</h1>
    @endif
@endif

<!-- Intro Lines -->
@foreach ($introLines as $line)
    <p>{{ $line }}</p>
@endforeach

<!-- Action Button -->
@isset($actionText)
    <?php
    $color = match ($level) {
        'success', 'error' => $level,
        default => 'primary',
    };
    ?>
    <x-mail::button :url="$actionUrl" :color="$color">
        {{ $actionText }}
    </x-mail::button>
@endisset

<!-- Outro Lines -->
@foreach ($outroLines as $line)
    <p>{{ $line }}</p>
@endforeach

<!-- Salutation -->
@if (!empty($salutation))
    <p>{{ $salutation }}</p>
@else
    <p>@lang('Regards, Bansud Hardware.')</p>
@endif

<!-- Subcopy -->
@isset($actionText)
    <p>@lang("If you're having trouble clicking the \":actionText\" button, copy and paste the URL below\n" . 'into your web browser:', [
        'actionText' => $actionText,
    ]) <span class="break-all">[{{ $displayableActionUrl }}]({{ $actionUrl }})</span></p>
@endisset
