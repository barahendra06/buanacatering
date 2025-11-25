<div class="modal fade" id="packageModal{{ $packageCategory->first()->catering_product_package_category_id }}" tabindex="-1" aria-labelledby="packageModalLabel{{ $packageCategory->first()->catering_product_package_category_id }}" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content rounded-4 shadow">

      {{-- Header --}}
      <div class="modal-header">
        <h5 class="modal-title text-black" id="packageModalLabel{{ $packageCategory->first()->catering_product_package_category_id }}">{{ $packageCategory->first()->packageCategory->name }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      {{-- Body --}}
      <div class="modal-body">
        <div class="carousel carousel-dark slide" id="carouselMainCategory{{ $packageCategory->first()->catering_product_package_category_id }}" style="margin-top: -15px" data-bs-ride="false">
          <div class="carousel-inner rounded-3">
            @foreach ($packageCategory as $i => $package)
              <div class="carousel-item {{ $i == 0 ? 'active' : '' }}">
                <div class="col-md-12">
                  <div class="row">
                
                    @php
                      $details = $package->items;
                    @endphp
                    <div class="col-md-6">
                      <div class="swiper childSwiper{{-- {{ $package->id }} --}}">
                        <div class="swiper-wrapper">
                          @foreach ($details as $index => $items)
                          <div class="swiper-slide">
                              <img src="{{ asset($items->product->img_path) }}" class="d-block w-100" alt="Image {{ $index + 1 }}" style="aspect-ratio: 1/1; object-fit: cover;">
                          </div>
                          @endforeach
                        </div>

                        {{-- <div class="swiper-button-next"></div> --}}
                        {{-- <div class="swiper-button-prev"></div> --}}
                        <div class="swiper-pagination"></div>
                      </div>
                    </div>

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
              </div>
            @endforeach
          </div>
          <button class="carousel-control-prev" type="button" data-bs-target="#carouselMainCategory{{ $packageCategory->first()->catering_product_package_category_id }}" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#carouselMainCategory{{ $packageCategory->first()->catering_product_package_category_id }}" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
          </button>
        </div>
      </div>

      <div class="modal-footer">
        <a class="btn btn-primary" href="https://wa.me/{{ $contact->phone_number }}" target="_blank"><i class="fa fa-whatsapp"></i>Order Now via Whatsapp</a>
        <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener("shown.bs.modal", function () {

    new Swiper(".childSwiper", {
        loop: true,
        autoplay: {
            delay: 2500,      // 2.5 detik
            disableOnInteraction: false,
        },
        pagination: { 
            el: ".swiper-pagination",
            clickable: true 
        },
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev"
        }
    });

});
</script>
