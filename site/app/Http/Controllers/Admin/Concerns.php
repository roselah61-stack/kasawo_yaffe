<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

trait Concerns
{
    /** Store an uploaded image (if any) and remove the old upload it replaces. */
    protected function storeImage(Request $request, string $field, string $dir, ?string $old = null): ?string
    {
        if (! $request->hasFile($field)) {
            return $old;
        }
        if ($old && ! str_starts_with($old, 'images/')) {
            Storage::disk('public')->delete($old);
        }

        return $request->file($field)->store($dir, 'public');
    }

    protected function csv(string $filename, array $header, iterable $rows): StreamedResponse
    {
        return response()->streamDownload(function () use ($header, $rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, $header);
            foreach ($rows as $row) {
                fputcsv($out, $row);
            }
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}
