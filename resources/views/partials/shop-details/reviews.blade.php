<section class="product-reviews spad">
    <div class="container">
        <div class="row d-flex justify-content-center">
            <div class="col-lg-8">

                {{-- 1. FORM GỬI ĐÁNH GIÁ --}}
                <div class="blog__details__comment">
                    <h4>Leave a Review</h4>

                    @if(session('success'))
                    <div class="alert alert-success text-center">{{ session('success') }}</div>
                    @endif

                    @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0 pl-3">
                            @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('review.store') }}" method="post">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->PRODUCT_ID }}">

                        {{-- Chọn Sao (Đã sửa CSS để tương tác mượt mà) --}}
                        <div class="mb-3">
                            <label class="form-label d-block font-weight-bold" style="text-align: right; width: 100%;">Your rating</label>
                            <div class="rating-css">
                                <div class="star-icon">
                                    <div class="rating-css">
                                        <div class="star-icon">
                                            <input type="radio" name="rating" id="rating5" value="5" {{ old('rating') == 5 ? 'checked' : '' }}>
                                            <label for="rating5" class="fa fa-star"></label>

                                            <input type="radio" name="rating" id="rating4" value="4" {{ old('rating') == 4 ? 'checked' : '' }}>
                                            <label for="rating4" class="fa fa-star"></label>

                                            <input type="radio" name="rating" id="rating3" value="3" {{ old('rating') == 3 ? 'checked' : '' }}>
                                            <label for="rating3" class="fa fa-star"></label>

                                            <input type="radio" name="rating" id="rating2" value="2" {{ old('rating') == 2 ? 'checked' : '' }}>
                                            <label for="rating2" class="fa fa-star"></label>

                                            <input type="radio" name="rating" id="rating1" value="1" {{ old('rating') == 1 ? 'checked' : '' }}>
                                            <label for="rating1" class="fa fa-star"></label>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <textarea name="comment" class="form-control" rows="4" placeholder="Your comment..." required>{{ old('comment') }}</textarea>
                        </div>

                        {{-- Luôn hiển thị input Tên/Email, tự động điền nếu đã login --}}
                        <div class="row">
                            <div class="col-lg-6 col-md-6 mb-3">
                                <input type="text" name="review_name" class="form-control" placeholder="Your name" required
                                    value="{{ Illuminate\Support\Facades\Auth::check() ? Illuminate\Support\Facades\Auth::user()->full_name : old('review_name') }}"
                                    {{ Illuminate\Support\Facades\Auth::check() ? 'readonly' : '' }}> {{-- Có thể để readonly nếu muốn --}}
                            </div>
                            <div class="col-lg-6 col-md-6 mb-3">
                                <input type="email" name="review_email" class="form-control" placeholder="Your email" required
                                    value="{{ Illuminate\Support\Facades\Auth::check() ? Illuminate\Support\Facades\Auth::user()->EMAIL : old('review_email') }}"
                                    {{ Illuminate\Support\Facades\Auth::check() ? 'readonly' : '' }}>
                            </div>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="site-btn">Submit Review</button>
                        </div>
                    </form>
                </div>

                <hr class="my-5">

                {{-- 2. DANH SÁCH ĐÁNH GIÁ --}}
                <div class="mb-4">
                    <h3 class="mb-2">Ratings & Reviews</h3>
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center">
                            <span class="me-2 font-weight-bold" style="font-size: 24px; margin-right: 10px;">{{ number_format($product->RATING, 1) }}</span>
                            <span class="text-warning" style="color: #f7941d;">
                                @php $rating = (int) $product->RATING; @endphp
                                @for($i=1; $i<=5; $i++)
                                    <i class="fa {{ $i <= $rating ? 'fa-star' : 'fa-star-o' }}"></i>
                                    @endfor
                            </span>
                        </div>
                        <span class="text-muted ml-3">{{ $reviews->count() }} reviews</span>
                    </div>
                </div>

                <div class="reviews__list mb-5">
                    @if($reviews->count() > 0)
                    @foreach($reviews as $review)
                    <div class="review__item mb-4 pb-4 border-bottom">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h5 class="mb-0 font-weight-bold">{{ $review->REVIEW_NAME ?? 'Guest' }}</h5>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($review->CREATED_AT)->format('d M Y, H:i') }}</small>
                        </div>
                        <div class="mb-2 text-warning" style="color: #f7941d;">
                            @for($i=1; $i<=5; $i++)
                                <i class="fa {{ $i <= $review->RATING ? 'fa-star' : 'fa-star-o' }}"></i>
                                @endfor
                        </div>
                        <p class="mb-0">{{ $review->COMMENT }}</p>
                    </div>
                    @endforeach
                    @else
                    <p class="text-center text-muted">Chưa có đánh giá nào. Hãy là người đầu tiên đánh giá sản phẩm này!</p>
                    @endif
                </div>

            </div>
        </div>
    </div>
</section>

<style>
    /* Container chứa các ngôi sao */
    .rating-css .star-icon {
        display: flex;
        flex-direction: row-reverse;
        /* Quan trọng: đảo hướng hiển thị */
        justify-content: flex-start;
        gap: 4px;
    }

    .rating-css input {
        display: none;
        /* Ẩn radio */
    }

    /* Mặc định xám */
    .rating-css label {
        font-size: 24px;
        color: #ccc;
        cursor: pointer;
        transition: color 0.15s ease;
    }

    /* Hover: tô ngôi sao đang hover và các ngôi sao "phía sau" trong DOM
   (nhưng nhờ row-reverse, chúng nằm bên trái trên giao diện) */
    .rating-css label:hover,
    .rating-css label:hover~label {
        color: #f7941d;
    }

    /* Checked: tô ngôi sao được chọn và tất cả ngôi sao bên trái (nhìn trên UI) */
    .rating-css input:checked~label {
        color: #f7941d;
    }
</style>