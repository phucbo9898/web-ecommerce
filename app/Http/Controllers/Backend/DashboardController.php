<?php

namespace App\Http\Controllers\Backend;

use App\Enums\StatusTransaction;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $chart = chart();

        // Total transactions pending
        $transaction = Transaction::whereStatus(StatusTransaction::PENDING)->count();

        // Total products
        $products = Product::count();

        // Total users with role
        $queryUsers = User::query();
        $users = Auth::user()->isAdmin() ? $queryUsers->count() : $queryUsers->whereRole(UserRole::USER)->count();

        // Total articles
        $articles = Article::count();

        return view('backend.dashboard.index', compact('chart', 'transaction', 'products', 'users', 'articles'));
    }
}
