@php
    $themeSetting = \App\Models\ColorSetting::current();
    $primaryColor = $themeSetting->primary_color ?? config('theme.primary', '#001C4D');
    $secondaryColor = $themeSetting->secondary_color ?? config('theme.secondary', '#5C5F60');
@endphp
<style>
    :root {
        --primary-color: {{ $primaryColor }};
        --secondary-color: {{ $secondaryColor }};
    }
</style>
