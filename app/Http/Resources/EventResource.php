<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'title'       => $this->title,
            'description' => $this->description,
            'event_date'  => $this->event_date,
            'user_id'     => $this->user_id,
            'created_at'  => $this->created_at?->toDateTimeString(),
            'updated_at'  => $this->updated_at?->toDateTimeString(),

            // hanya dikirim kalau relasi users di-load
            'participants' => $this->whenLoaded('users', function () {
                return $this->users->map(function ($user) {
                    return [
                        'id'    => $user->id,
                        'name'  => $user->name,
                        'email' => $user->email,
                        'joined_at' => $user->pivot->created_at?->toDateTimeString(),
                    ];
                });
            }),
        ];
    }
}
