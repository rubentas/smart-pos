<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SetBranch
{
  public function handle(Request $request, Closure $next)
  {
    // Jika user punya cabang dan belum pilih cabang di session
    if (auth()->check() && auth()->user()->branch_id && !Session::has('active_branch')) {
      Session::put('active_branch', auth()->user()->branch_id);
    }

    return $next($request);
  }
}