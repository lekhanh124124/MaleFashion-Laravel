<div class="col-lg-8 col-md-6">
    <!-- Phần Coupon: Nếu đã nhập rồi thì ẩn hoặc hiện thông báo -->
    @if(!Session::has('coupon'))
    <h6 class="coupon__code"><span class="icon_tag_alt"></span>
        Have a coupon? <a href="{{ route('shopping.cart') }}">Click here</a> to enter your code at Cart page
    </h6>
    @else
    <h6 class="coupon__code"><span class="icon_check_alt"></span>
        Coupon <strong>{{ Session::get('coupon')['coupon_code'] }}</strong> applied!
    </h6>
    @endif

    <h6 class="checkout__title">Billing Details</h6>
    
    <div class="row">
        <div class="col-lg-6">
            <div class="checkout__input">
                <p>First Name<span>*</span></p>
                <input type="text" name="firstname" value="{{ Auth::user()->FIRST_NAME ?? old('firstname') }}">
                @error('firstname') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="col-lg-6">
            <div class="checkout__input">
                <p>Last Name<span>*</span></p>
                <input type="text" name="lastname" value="{{ Auth::user()->LAST_NAME ?? old('lastname') }}">
                @error('lastname') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>
        </div>
    </div>

    <div class="checkout__input">
        <p>Country<span>*</span></p>
        <input type="text" name="country" value="{{ old('country') }}">
        @error('country') <span class="text-danger small">{{ $message }}</span> @enderror
    </div>

    <div class="checkout__input">
        <p>Address<span>*</span></p>
        <input type="text" name="address" placeholder="Street Address" class="checkout__input__add" value="{{ Auth::user()->ADDRESS ?? old('address') }}">
        @error('address') <span class="text-danger small">{{ $message }}</span> @enderror
        <input type="text" name="apartment" placeholder="Apartment, suite, unit etc (optional)" value="{{ old('apartment') }}">
    </div>

    <div class="checkout__input">
        <p>Town/City<span>*</span></p>
        <input type="text" name="city" value="{{ old('city') }}">
        @error('city') <span class="text-danger small">{{ $message }}</span> @enderror
    </div>

    <div class="checkout__input">
        <p>Postcode / ZIP (Optional)</p>
        <input type="text" name="postcode" value="{{ old('postcode') }}">
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="checkout__input">
                <p>Phone<span>*</span></p>
                <input type="text" name="phone" value="{{ Auth::user()->PHONE_NUMBER ?? old('phone') }}">
                @error('phone') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="col-lg-6">
            <div class="checkout__input">
                <p>Email<span>*</span></p>
                <input type="text" name="email" value="{{ Auth::user()->EMAIL ?? old('email') }}">
                @error('email') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>
        </div>
    </div>

    <!-- Chỉ hiện phần tạo tài khoản nếu chưa đăng nhập -->
    @if(!Auth::check())
    <div class="checkout__input__checkbox">
        <label for="acc">
            Create an account?
            <input type="checkbox" id="acc" name="create_account" value="1">
            <span class="checkmark"></span>
        </label>
        <p>Create an account by entering the information below. If you are a returning customer please login at the top of the page</p>
    </div>
    <div class="checkout__input" id="account-password-field">
        <p>Account Password<span>*</span></p>
        <input type="password" name="account_password">
    </div>
    @endif

    <div class="checkout__input__checkbox">
        <label for="diff-acc">
            Note about your order, e.g, special note for delivery
            <input type="checkbox" id="diff-acc" disabled checked>
            <span class="checkmark"></span>
        </label>
    </div>
    <div class="checkout__input">
        <p>Order notes</p>
        <input type="text" name="order_notes" placeholder="Notes about your order, e.g. special notes for delivery." value="{{ old('order_notes') }}">
    </div>
</div>