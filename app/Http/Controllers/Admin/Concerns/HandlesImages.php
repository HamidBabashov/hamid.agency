<?php

namespace App\Http\Controllers\Admin\Concerns;

use App\Services\ImageService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

trait HandlesImages
{
    protected function imageRules(): array
    {
        return ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:8192'];
    }

    /** Yeni şəkil yüklənibsə WebP-ə çevirib saxlayır, köhnəsini silir. "remove_{field}" seçilibsə silir. */
    protected function syncImage(Request $request, Model $model, string $field, string $directory, ?int $maxWidth = null): void
    {
        $images = app(ImageService::class);

        if ($request->hasFile($field)) {
            $images->delete($model->getAttribute($field));
            $model->setAttribute($field, $images->store($request->file($field), $directory, $maxWidth));
        } elseif ($request->boolean("remove_{$field}")) {
            $images->delete($model->getAttribute($field));
            $model->setAttribute($field, null);
        }
    }
}
