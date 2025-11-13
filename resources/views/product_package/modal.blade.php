<div class="modal fade" id="packageModal{{ $package->id }}" tabindex="-1" aria-labelledby="packageModalLabel{{ $package->id }}" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content rounded-4 shadow">

      {{-- Header --}}
      <div class="modal-header">
        <h5 class="modal-title" id="packageModalLabel{{ $package->id }}">{{ $package->name }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      {{-- Body --}}
      <div class="modal-body">
        <div class="row">
          
          {{-- Carousel / Image Slider --}}
          <div class="col-md-6">
            <div id="carouselPackage{{ $package->id }}" style="margin-top: -15px" class="carousel carousel-dark slide" data-bs-ride="carousel">
              
              <div class="carousel-inner rounded-3">
                @php
                  // Jika kamu punya relasi ke images, contoh: $package->images
                  $details = $package->items;
                @endphp

                {{-- <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                    <img src="{{ asset($package->) }}" class="d-block w-100" alt="Image {{ $index + 1 }}" style="aspect-ratio: 1/1; object-fit: cover;">
                </div> --}}
                @foreach ($details as $index=>$items)
                  <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                    <img src="{{ asset($items->product->img_path) }}" class="d-block w-100" alt="Image {{ $index + 1 }}" style="aspect-ratio: 1/1; object-fit: cover;">
                  </div>
                @endforeach
              </div>

              {{-- Tombol kontrol kiri-kanan --}}
              <button class="carousel-control-prev" type="button" data-bs-target="#carouselPackage{{ $package->id }}" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
              </button>
              <button class="carousel-control-next" type="button" data-bs-target="#carouselPackage{{ $package->id }}" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
              </button>

              {{-- Indikator (titik di bawah gambar) --}}
              <div class="carousel-indicators mt-3">
                @foreach ($details as $index=>$items)
                  <button type="button"
                          data-bs-target="#carouselPackage{{ $package->id }}"
                          data-bs-slide-to="{{ $index }}"
                          class="{{ $index === 0 ? 'active' : '' }}"
                          aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                          aria-label="Slide {{ $index + 1 }}"></button>
                @endforeach
              </div>

            </div>
          </div>

          {{-- Deskripsi --}}
          <div class="col-md-6">
            <h5 class="fw-bold text-black">{{ strToUpper($package->name ?? '') }}</h5>
            <p class="mb-4">{{ $package->description ?? '' }}</p>
            <p class="mb-4"><b>For {{ $package->quantity }} Pax</b></p>
            <ul style="padding-left: 1rem!important;font-size:16px">
            @foreach ($details as $index=>$items)
             <li>{{ $items->product->name }}</li>
            @endforeach
            </ul>
            <h6 class="fw-bold ">Harga:</h6>
            <p class="fs-5 text-success mb-0">Rp {{ number_format($package->price, 0, ',', '.') }}</p>
          </div>
        </div>
      </div>

      {{-- Footer --}}
      <div class="modal-footer">
        <a class="btn btn-primary" href="https://wa.me/{{ $contact->phone_number }}" target="_blank"><i class="fa fa-whatsapp"></i>Order Now via Whatsapp</a>
        <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Tutup</button>
        {{-- <a href="{{ route('package.show', $package->id) }}" class="btn btn-primary rounded-3">Lihat Selengkapnya</a> --}}
      </div>
    </div>
  </div>
</div>
