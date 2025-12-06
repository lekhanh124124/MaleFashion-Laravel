<div class="search-model">
    <div class="h-100 d-flex align-items-center justify-content-center">
        <div class="search-close-switch">+</div>
        
        {{-- SỬA 1 & 2: Thêm action và method --}}
        <form class="search-model-form" action="{{ route('shop') }}" method="GET">
            
            {{-- SỬA 3: Thêm name="search" và value để giữ lại từ khóa cũ --}}
            <input type="text" 
                   name="search" 
                   id="search-input" 
                   placeholder="Search here....." 
                   value="{{ request('search') }}">
                   
        </form>
    </div>
</div>