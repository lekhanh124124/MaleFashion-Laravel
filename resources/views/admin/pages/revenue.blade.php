@extends('layouts.admin')

@section('content')
<div id="pane-revenue" class="mt-2">
  {{-- Form Lọc Ngày --}}
  <form method="GET" class="sticky-toolbar d-flex align-items-center mb-3">
    <h5 class="mb-0 mr-auto">Doanh thu</h5>
    <div class="d-flex align-items-center">
      <label class="mb-0 mr-2 small text-muted">Từ:</label>
      <input type="date" name="start_date" class="form-control form-control-sm mr-2" value="{{ $startDate->format('Y-m-d') }}">
      <label class="mb-0 mr-2 small text-muted">Đến:</label>
      <input type="date" name="end_date" class="form-control form-control-sm mr-2" value="{{ $endDate->format('Y-m-d') }}">
      <button class="btn btn-dark btn-sm" type="submit">Lọc dữ liệu</button>
    </div>
  </form>

  <div class="legend-box mt-2">
    <strong>Ghi chú:</strong> Số liệu tính trên đơn hàng thực tế (Đã trừ các đơn Hủy - Cancelled).
  </div>

  {{-- Cards KPI --}}
  <div class="row mt-3">
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-success mb-3 h-100">
            <div class="card-body p-3">
                <div class="text-white-50 small">Tổng doanh thu</div>
                <div class="h4 mb-0 font-weight-bold">{{ number_format($totalRevenue) }} đ</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-dark bg-light mb-3 h-100 border">
            <div class="card-body p-3">
                <div class="text-muted small">Tổng số đơn</div>
                <div class="h4 mb-0">{{ number_format($totalOrders) }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-dark bg-light mb-3 h-100 border">
            <div class="card-body p-3">
                <div class="text-muted small">Giá trị TB / đơn (AOV)</div>
                <div class="h4 mb-0">{{ number_format($avgOrderValue) }} đ</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-danger mb-3 h-100">
            <div class="card-body p-3">
                <div class="text-white-50 small">Tỷ lệ hủy</div>
                <div class="h4 mb-0">{{ number_format($cancellationRate, 1) }}%</div>
            </div>
        </div>
    </div>
  </div>

  <div class="row mt-2">
    {{-- Biểu đồ --}}
    <div class="col-lg-7">
      <div class="card shadow-sm mb-3">
        <div class="card-header bg-white">
            <strong>Biểu đồ doanh thu</strong>
            <span class="text-muted small ml-2">({{ $startDate->format('d/m') }} - {{ $endDate->format('d/m') }})</span>
        </div>
        <div class="card-body">
            <canvas id="revenueChart" style="height: 300px; width: 100%;"></canvas>
        </div>
      </div>
    </div>

    {{-- Top Danh mục --}}
    <div class="col-lg-5">
      <div class="card shadow-sm mb-3">
        <div class="card-header bg-white">
            <strong>Top Danh mục doanh thu cao</strong>
        </div>
        <table class="table table-hover mb-0">
          <thead class="thead-light">
            <tr>
                <th>Danh mục</th>
                <th class="text-right">Doanh thu (Gross)</th>
            </tr>
          </thead>
          <tbody>
            @forelse($topCategories as $cat)
            <tr>
                <td>{{ $cat->CATEGORY_NAME }}</td>
                <td class="text-right font-weight-bold">{{ number_format($cat->total_revenue) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="2" class="text-center text-muted">Chưa có dữ liệu.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

{{-- Thêm thư viện Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('revenueChart').getContext('2d');
        
        // Dữ liệu từ Controller
        const labels = @json($chartLabels);
        const data = @json($chartData);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Doanh thu (VND)',
                    data: data,
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                    borderWidth: 2,
                    pointBackgroundColor: '#007bff',
                    fill: true,
                    tension: 0.3 // Đường cong mềm mại
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return new Intl.NumberFormat().format(context.raw) + ' đ';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return new Intl.NumberFormat().format(value); // Format số trục Y
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection