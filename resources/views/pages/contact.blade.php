@extends('layouts.malefashion')

@section('title', 'Contacts')

@section('content')
<!-- Map Begin -->
<div class="map">
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.067279078136!2d106.62609067552124!3d10.806159158643554!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752be27d8b4f4d%3A0x92dcba2950430867!2zVHLGsOG7nW5nIMSQ4bqhaSBo4buNYyBDw7RuZyBUaMawxqFuZyBUUC4gSOG7kyBDaMOtIE1pbmggKEhVSVQp!5e0!3m2!1svi!2s!4v1763937627775!5m2!1svi!2s" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
</div>
<!-- Map End -->

<!-- Contact Section Begin -->
<section class="contact spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6">
                <div class="contact__text">
                    <div class="section-title">
                        <span>Information</span>
                        <h2>Contact Us</h2>
                        <p>As you might expect of a company that began as a high-end interiors contractor, we pay
                            strict attention.</p>
                    </div>
                    <ul>
                        <li>
                            <h4>America</h4>
                            <p>195 E Parker Square Dr, Parker, CO 801 <br />+43 982-314-0958</p>
                        </li>
                        <li>
                            <h4>France</h4>
                            <p>109 Avenue Léon, 63 Clermont-Ferrand <br />+12 345-423-9893</p>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="contact__form">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    <form action="{{ route('contact.send') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <input type="text" name="NAME" placeholder="Name"
                                       value="{{ old('NAME', session('customer_user_id') ? \App\Models\User::find(session('customer_user_id'))->FIRST_NAME . ' ' . \App\Models\User::find(session('customer_user_id'))->LAST_NAME : '') }}">
                            </div>
                            <div class="col-lg-6">
                                <input type="text" name="EMAIL" placeholder="Email"
                                       value="{{ old('EMAIL', session('customer_user_id') ? \App\Models\User::find(session('customer_user_id'))->EMAIL : '') }}">
                            </div>
                            <div class="col-lg-12">
                                <textarea name="MESSAGE" placeholder="Message">{{ old('MESSAGE') }}</textarea>
                                <button type="submit" class="site-btn">Send Message</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Contact Section End -->
@endsection

<style>
.contact__form input,
.contact__form textarea {
    color: #222 !important;
}
.contact__form input::placeholder,
.contact__form textarea::placeholder {
    color: #bbb;
    opacity: 1;
}
</style>