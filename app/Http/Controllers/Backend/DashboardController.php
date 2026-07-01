<?php

namespace App\Http\Controllers\Backend;

use App\Enums\StatusTransaction;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Product;
use App\Models\Rating;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $chart = chart();
        // dd(
        //     $chart
        // );

        $transaction = Transaction::whereIn('status', [
            StatusTransaction::PENDING,
            StatusTransaction::PROCESSING,
        ])->count();

        // Doanh thu hôm nay (chỉ tính giao dịch đã hoàn tất, status = COMPLETED)
        $todayRevenue = Transaction::whereDate('updated_at', Carbon::today())
            ->whereStatus(StatusTransaction::COMPLETED) // đổi tên trạng thái nếu khác
            ->sum('total_amount');

        // Doanh thu hôm qua, dùng để tính % chênh lệch
        $yesterdayRevenue = Transaction::whereDate('updated_at', Carbon::yesterday())
            ->whereStatus(StatusTransaction::COMPLETED)
            ->sum('total_amount');

        $revenueDeltaPercent = $yesterdayRevenue > 0
            ? round((($todayRevenue - $yesterdayRevenue) / $yesterdayRevenue) * 100, 1)
            : ($todayRevenue > 0 ? 100 : 0);
        // dd($todayRevenue, $yesterdayRevenue, $revenueDeltaPercent < 0);

        // Sản phẩm sắp hết hàng (đổi tên cột 'stock' nếu schema của bạn dùng tên khác, vd: quantity)
        $lowStockThreshold = 10;
        $lowStockCount = Product::where('quantity', '<=', $lowStockThreshold)->count();

        // Đánh giá mới trong 7 ngày — bỏ block này nếu bạn chưa có bảng review
        $newReviews = Rating::where('created_at', '>=', Carbon::today()->subDays(7))->count();

        // ===== Hàng 2: chi tiết trạng thái đơn hàng =====
        $orderStatus = [
            'pending'    => Transaction::whereStatus(StatusTransaction::PENDING)->count(),
            'processing' => Transaction::whereStatus(StatusTransaction::PROCESSING)->count(),
            'shipping'   => Transaction::whereStatus(StatusTransaction::SHIPPING)->count(),
            'completed'  => Transaction::whereStatus(StatusTransaction::COMPLETED)->count(),
            'cancelled'  => Transaction::whereStatus(StatusTransaction::CANCELED)->count(),
        ];
        // Nếu enum StatusTransaction của bạn đặt tên khác (vd: CONFIRMED, DELIVERING, DONE, CANCEL...)
        // chỉ cần đổi tên hằng số tương ứng ở trên, cấu trúc mảng $orderStatus giữ nguyên.

        // ===== Hàng 4: sản phẩm bán chạy trong tháng =====
        // Giả định Transaction có quan hệ hasMany 'items' (TransactionItem) gồm product_id, quantity, price.
        // Nếu tên quan hệ/model của bạn khác (vd: details, transactionItems...), đổi lại tên quan hệ bên dưới.
        $topSellingProducts = Product::query()
            ->selectRaw('products.*, COALESCE(SUM(transaction_detail.quantity), 0) as sold_quantity')
            ->selectRaw('COALESCE(SUM(transaction_detail.quantity * transaction_detail.price), 0) as revenue')
            ->join('transaction_detail', 'transaction_detail.product_id', '=', 'products.id')
            ->join('transactions', 'transactions.id', '=', 'transaction_detail.transaction_id')
            ->whereMonth('transactions.created_at', Carbon::now()->month)
            ->whereYear('transactions.created_at', Carbon::now()->year)
            ->groupBy('products.id')
            ->orderByDesc('sold_quantity')
            ->take(5)
            ->get();
        // Nếu bạn chưa có bảng transaction_items, tạm thời truyền $topSellingProducts = collect()
        // để phần UI hiển thị "Chưa có dữ liệu" thay vì lỗi truy vấn.

        // ===== Hàng 5: đơn hàng gần đây + kho sắp hết =====
        $recentOrders = Transaction::with('user') // đổi tên quan hệ nếu khách hàng không lưu qua quan hệ 'user'
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($order) {
                return (object) [
                    'code'           => $order->code ?? $order->id,
                    'customer_name'  => $order->user->name ?? ($order->customer_name ?? 'Khách lẻ'),
                    'total'          => $order->total_amount,
                    'status_label'   => $this->statusLabel($order->status),
                    'status_color'   => $this->statusColor($order->status),
                ];
            });

        $lowStockProducts = Product::where('quantity', '<=', $lowStockThreshold)
            ->orderBy('quantity')
            ->take(5)
            ->get();

        // ===== Hàng 6: tổng quan hệ thống =====
        $queryUsers = User::query();
        $users = Auth::user()->isAdmin() ? $queryUsers->count() : $queryUsers->whereRole(UserRole::USER)->count();

        $products = Product::count();
        $articles = Article::count();

        // Phiếu giảm giá đang chạy — bỏ block này nếu bạn chưa có bảng coupon
        $activeCoupons = Voucher::where('expire_date', '>=', Carbon::now())
            ->count();

        // Phiếu giảm giá sắp hết hạn trong 3 ngày tới — dùng cho dòng phụ của info-box
        $expiringCoupons = Voucher::whereBetween('expire_date', [Carbon::now(), Carbon::now()->addDays(3)])
            ->count();

        // ===== Hàng 7: bài viết / đánh giá / coupon chi tiết =====
        $recentPosts = Article::latest()->take(5)->get();

        $recentReviews = Rating::with('product')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($review) {
                return (object) [
                    'product_name' => $review->product->name ?? '',
                    'rating'       => $review->rating,
                ];
            });

        $activeCouponList = Voucher::where('expire_date', '>=', Carbon::now())
            ->take(5)
            ->get();

        return view('backend.dashboard.index', compact(
            'chart',
            'transaction',
            'products',
            'users',
            'articles',
            'todayRevenue',
            'revenueDeltaPercent',
            'lowStockCount',
            'newReviews',
            'orderStatus',
            'topSellingProducts',
            'recentOrders',
            'lowStockProducts',
            'activeCoupons',
            'expiringCoupons',
            'recentPosts',
            'recentReviews',
            'activeCouponList'
        ));
    }

    private function statusLabel($status): string
    {
        return match ($status) {
            StatusTransaction::PENDING    => 'Chờ xác nhận',
            StatusTransaction::PROCESSING => 'Đang xử lí',
            StatusTransaction::SHIPPING   => 'Đang giao',
            StatusTransaction::COMPLETED  => 'Hoàn tất',
            StatusTransaction::CANCELED  => 'Đã huỷ',
            default                       => 'Không xác định',
        };
    }

    private function statusColor($status): string
    {
        return match ($status) {
            StatusTransaction::PENDING    => 'warning',
            StatusTransaction::PROCESSING => 'info',
            StatusTransaction::SHIPPING   => 'primary',
            StatusTransaction::COMPLETED  => 'success',
            StatusTransaction::CANCELED  => 'danger',
            default                       => 'secondary',
        };
    }
}
