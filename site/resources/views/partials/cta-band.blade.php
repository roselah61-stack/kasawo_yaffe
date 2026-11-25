<section class="band">
    <div class="wrap cta-band" data-reveal>
        <div>
            <h2>{{ $title }}</h2>
            <p class="lead" style="margin:0">{{ $text }}</p>
        </div>
        <div style="display:flex;gap:12px;flex-wrap:wrap">
            <a href="{{ $href }}" class="btn btn-light">{{ $button }}</a>
            @isset($href2)<a href="{{ $href2 }}" class="btn btn-ghost-light">{{ $button2 }}</a>@endisset
        </div>
    </div>
</section>
