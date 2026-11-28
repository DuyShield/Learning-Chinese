@props(['name', 'class' => 'size-5'])
@php
    $paths = [
        'home' => 'M3 11l9-8 9 8M5 10v10h14V10M10 20v-6h4v6',
        'book' => 'M2 4h7a3 3 0 013 3v13a2 2 0 00-2-2H2zM22 4h-7a3 3 0 00-3 3v13a2 2 0 012-2h8z',
        'mic' => 'M12 2a3 3 0 00-3 3v6a3 3 0 006 0V5a3 3 0 00-3-3zM5 11a7 7 0 0014 0M12 18v4',
        'headphones' => 'M3 18v-6a9 9 0 0118 0v6M3 18a2 2 0 002 2h1v-6H5a2 2 0 00-2 2zM21 18a2 2 0 01-2 2h-1v-6h1a2 2 0 012 2z',
        'stack' => 'M4 4h16v6H4zM4 14h16v6H4zM8 7h.01M8 17h.01',
        'cap' => 'M2 9l10-5 10 5-10 5zM6 11v5c3 2 9 2 12 0v-5',
        'brain' => 'M9 4a3 3 0 00-3 3 3 3 0 00-2 5 3 3 0 002 5 3 3 0 006 1V4a3 3 0 00-3 0zM15 4a3 3 0 013 3 3 3 0 012 5 3 3 0 01-2 5 3 3 0 01-6 1',
        'swords' => 'M4 4l9 9M20 4l-9 9M3 21l5-5M21 21l-5-5M10 14l-3 3M14 14l3 3',
        'user' => 'M12 12a4 4 0 100-8 4 4 0 000 8zM4 21a8 8 0 0116 0',
        'logout' => 'M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9',
        'search' => 'M11 19a8 8 0 100-16 8 8 0 000 16zM21 21l-4.3-4.3',
        'bell' => 'M18 8a6 6 0 10-12 0c0 7-3 9-3 9h18s-3-2-3-9M13.7 21a2 2 0 01-3.4 0',
        'clock' => 'M12 22a10 10 0 100-20 10 10 0 000 20zM12 6v6l4 2',
        'volume' => 'M11 5L6 9H2v6h4l5 4zM15.5 8.5a5 5 0 010 7M18.5 5.5a9 9 0 010 13',
        'arrow' => 'M5 12h14M13 6l6 6-6 6',
    ];
@endphp
<svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="{{ $paths[$name] ?? $paths['home'] }}"/></svg>
