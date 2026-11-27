@props([
    'title',
    'subtitle',
    'icon' => 'fa-house',
])

<header class="header">
    <button class="btn-menu" id="btnMenu" type="button" aria-label="Mở menu">
        <i class="fa-solid fa-bars" aria-hidden="true"></i>
    </button>
    <div class="header-icon"><i class="fa-solid {{ $icon }}" aria-hidden="true"></i></div>
    <div>
        <h1>{{ $title }}</h1>
        <p>{{ $subtitle }}</p>
    </div>
    <div class="ml-auto hidden items-center gap-2 text-sm font-semibold text-muted sm:flex">
        <i class="fa-regular fa-circle-user text-lg text-primary" aria-hidden="true"></i>
        {{ auth()->user()->full_name }}
    </div>
</header>
