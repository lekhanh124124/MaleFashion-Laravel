
@php
  $team = $team ?? [
    ['img' => 'about/team-1.jpg', 'name' => 'John Smith', 'role' => 'Fashion Design'],
    ['img' => 'about/team-2.jpg', 'name' => 'Christine Wise', 'role' => 'C.E.O'],
    ['img' => 'about/team-3.jpg', 'name' => 'Sean Robbins', 'role' => 'Manager'],
    ['img' => 'about/team-4.jpg', 'name' => 'Lucy Myers', 'role' => 'Delivery'],
  ];
@endphp
<section class="team spad">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <div class="section-title">
          <span>Our Team</span>
          <h2>Meet Our Team</h2>
        </div>
      </div>
    </div>
    <div class="row">
      @foreach ($team as $m)
        <div class="col-lg-3 col-md-6 col-sm-6">
          <div class="team__item">
            <img src="{{ asset('assets/malefashion/img/' . $m['img']) }}" alt="">
            <h4>{{ $m['name'] }}</h4>
            <span>{{ $m['role'] }}</span>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>