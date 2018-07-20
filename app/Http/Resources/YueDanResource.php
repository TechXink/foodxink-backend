<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class YueDanResource extends JsonResource
{
    protected $week = [
        '星期日',
        '星期一',
        '星期二',
        '星期三',
        '星期四',
        '星期五',
        '星期六'
    ];

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'title' => $this->title,
            'time' => [
                'date' => date('Y/m/d', $this->eat_time),
                'week' => $this->week[date('w', $this->eat_time)],
                'hour' => date('H:i', $this->eat_time)
            ],
            'location' => [
                'address' => $this->address,
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
                'name' => $this->location_name
            ],
            'img' => $this->image[0]
        ];
    }
}
