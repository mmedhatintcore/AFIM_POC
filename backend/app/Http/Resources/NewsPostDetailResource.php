<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

final class NewsPostDetailResource extends JsonResource
{
    public function __construct($resource, private readonly iterable $related = [])
    {
        parent::__construct($resource);
    }

    public function toArray($request): array
    {
        $locale = app()->getLocale();

        return [
            ...(new NewsPostResource($this->resource))->toArray($request),
            'body' => $this->getTranslation('body', $locale),
            'related' => NewsPostResource::collection($this->related)->toArray($request),
        ];
    }
}
