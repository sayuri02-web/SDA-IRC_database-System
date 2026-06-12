<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Member;

class Church extends Model
{
    protected $fillable = [
        'church_name',
        'cluster',
        'region',
        'province',
        'city',
        'barangay',
        'street',
    ];

    public function members()
    {
        return $this->hasMany(Member::class);
    }

    // =========================
    // JSON LOADER (SAFE)
    // =========================
    private function loadJson($file)
    {
        $path = storage_path("app/address/$file");

        if (!file_exists($path)) {
            return collect();
        }

        return collect(json_decode(file_get_contents($path), true));
    }

    // =========================
    // REGION NAME
    // =========================
    public function regionName()
    {
        return $this->loadJson('region.json')
            ->firstWhere('region_code', $this->region)['region_name'] ?? '';
    }

    // =========================
    // PROVINCE NAME
    // =========================
    public function provinceName()
    {
        return $this->loadJson('province.json')
            ->firstWhere('province_code', $this->province)['province_name'] ?? '';
    }

    // =========================
    // CITY NAME
    // =========================
    public function cityName()
    {
        return $this->loadJson('city.json')
            ->firstWhere('city_code', $this->city)['city_name'] ?? '';
    }

    // =========================
    // BARANGAY NAME
    // =========================
    public function barangayName()
    {
        return $this->loadJson('barangay.json')
            ->firstWhere('brgy_code', $this->barangay)['brgy_name'] ?? '';
    }

    // =========================
    // FULL ADDRESS
    // =========================
    public function getAddressAttribute()
    {
        return collect([
            $this->street,
            $this->barangay,
            $this->city,
            $this->province,
            $this->region,
        ])->filter()->implode(', ');
    }
}