@php
  $items = $items ?? [];
@endphp

@once
  <style>
    .app-breadcrumb {
      display: flex;
      align-items: center;
      flex-wrap: wrap;
      gap: 0.6rem;
      margin: 0 0 1.5rem;
      color: #94a3b8;
      font-size: 0.98rem;
      font-weight: 800;
      letter-spacing: -0.01em;
    }

    .app-breadcrumb a,
    .app-breadcrumb span {
      line-height: 1.2;
    }

    .app-breadcrumb a {
      color: #64748b;
      text-decoration: none;
      transition: color 0.18s ease;
    }

    .app-breadcrumb a:hover {
      color: #2563eb;
    }

    .app-breadcrumb .is-current {
      color: #94a3b8;
    }

    .app-breadcrumb-separator {
      color: #cbd5e1;
      font-size: 1.05rem;
      line-height: 1;
    }
  </style>
@endonce

@if(count($items) > 0)
  <nav class="app-breadcrumb" aria-label="Breadcrumb">
    @foreach($items as $item)
      @if(! $loop->first)
        <i class="ri ri-arrow-right-s-line app-breadcrumb-separator" aria-hidden="true"></i>
      @endif

      @if(!empty($item['url']) && empty($item['current']))
        <a href="{{ $item['url'] }}">{{ $item['label'] }}</a>
      @else
        <span class="{{ !empty($item['current']) ? 'is-current' : '' }}">{{ $item['label'] }}</span>
      @endif
    @endforeach
  </nav>
@endif
