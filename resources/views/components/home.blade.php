@foreach ($banners as $banner)
    <x-frontend::link
        link="{{ $banner['link'] }}"
        target="{{ $banner['target'] }}"
        aria-label="{{ $banner['name'] }}"
        title="{{ $banner['name'] }}"
        class="swiper-slide">
        <div>
            <div>
                <picture>
                    @foreach($banner['images'] as $mediaQuery => $responsiveImages)
                        <source media="{{ $mediaQuery }}"
                                srcset="{{ $responsiveImages }}">
                    @endforeach
                    <img src="data:image/png;base64, R0lGODlhAQABAIAAAMLCwgAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw=="
                         loading="{{ $loop->first ? 'eager' : 'lazy' }}"
                         alt="{{ $banner['name'] }}"
                         title="{{ $banner['name'] }}"
                         decoding="{{ $loop->first ? 'auto' : 'async' }}"
                         class="img-cover">
                </picture>
            </div>
        </div>
    </x-frontend::link>
@endforeach
