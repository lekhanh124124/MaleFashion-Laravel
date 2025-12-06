
@php
  $clients = $clients ?? [
    'clients/client-1.png',
    'clients/client-2.png',
    'clients/client-3.png',
    'clients/client-4.png',
  ];
@endphp
<section class="clients spad">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <div class="section-title">
          <span>Partner</span>
          <h2>Happy Clients</h2>
        </div>
      </div>
    </div>
    <div class="row">
      @foreach ($clients as $c)
        <div class="col-lg-3 col-md-4 col-sm-4 col-6">
          <a href="#" class="client__item">
            <img src="{{ asset('assets/malefashion/img/' . $c) }}" alt="">
          </a>
        </div>
      @endforeach
    </div>
  </div>
</section>