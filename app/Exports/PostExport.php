<?php

namespace App\Exports;

use App\Post;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PostExport implements FromView, ShouldAutoSize
{

    public function view(): View
    {
        $from = Carbon::now()->subDays(10);
        $to = now();
        $data = Post::get()->whereBetween('created_at', array($from, $to));
        return view('layouts.pdf_excel', compact('data', 'from'));
    }
}
