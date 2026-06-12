<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Church;
use App\Models\Member;

class AddressPicker extends Component
{
    public $regions = [];
    public $provinces = [];
    public $cities = [];
    public $barangays = [];

    public $selectedRegion = null;
    public $selectedProvince = null;
    public $selectedCity = null;
    public $selectedBarangay = null;

    public $regionName = null;
    public $provinceName = null;
    public $cityName = null;
    public $barangayName = null;

    public $street = null;

    public function mount($churchId = null, $memberId = null)
    {
        // LOAD JSON FILES
        $this->regions = json_decode(
            file_get_contents(storage_path('app/address/region.json')),
            true
        );

        $allProvinces = json_decode(
            file_get_contents(storage_path('app/address/province.json')),
            true
        );

        $allCities = json_decode(
            file_get_contents(storage_path('app/address/city.json')),
            true
        );

        $allBarangays = json_decode(
            file_get_contents(storage_path('app/address/barangay.json')),
            true
        );

        if ($churchId) {

            $church = Church::find($churchId);

            if ($church) {

                // STREET
                $this->street = $church->street;

                // =========================
                // REGION NAME -> CODE
                // =========================
                $region = collect($this->regions)
                    ->firstWhere('region_name', $church->region);

                if ($region) {

                    $this->selectedRegion = $region['region_code'];

                    // LOAD PROVINCES
                    $this->loadProvinces($region['region_code']);
                }

                // =========================
                // PROVINCE NAME -> CODE
                // =========================
                $province = collect($allProvinces)
                    ->firstWhere('province_name', $church->province);

                if ($province) {

                    $this->selectedProvince = $province['province_code'];

                    // LOAD CITIES
                    $this->loadCities($province['province_code']);
                }

                // =========================
                // CITY NAME -> CODE
                // =========================
                $city = collect($allCities)

                    ->where('province_code', $this->selectedProvince)

                    ->firstWhere('city_name', $church->city);

                if ($city) {

                    $this->selectedCity = $city['city_code'];

                    // LOAD BARANGAYS
                    $this->loadBarangays($city['city_code']);
                }

                // =========================
                // BARANGAY NAME -> CODE
                // =========================
                $barangay = collect($allBarangays)

                    ->where('city_code', $this->selectedCity)

                    ->firstWhere('brgy_name', $church->barangay);

                if ($barangay) {

                    $this->selectedBarangay = $barangay['brgy_code'];
                }
            }
        }

        if ($memberId) {

            $member = Member::find($memberId);

            if ($member) {

                $this->street = $member->street;

                // =========================
                // REGION NAME -> CODE
                // =========================
                $region = collect($this->regions)
                    ->firstWhere('region_name', $member->region);

                if ($region) {

                    $this->selectedRegion = $region['region_code'];

                    $this->loadProvinces($region['region_code']);
                }

                // =========================
                // PROVINCE NAME -> CODE
                // =========================
                $province = collect($allProvinces)
                    ->firstWhere('province_name', $member->province);

                if ($province) {

                    $this->selectedProvince = $province['province_code'];

                    $this->loadCities($province['province_code']);
                }

                // =========================
                // CITY NAME -> CODE
                // =========================
                $city = collect($allCities)

                    ->where('province_code', $this->selectedProvince)

                    ->firstWhere('city_name', $member->city);

                if ($city) {

                    $this->selectedCity = $city['city_code'];

                    $this->loadBarangays($city['city_code']);
                }

                // =========================
                // BARANGAY NAME -> CODE
                // =========================
                $barangay = collect($allBarangays)

                    ->where('city_code', $this->selectedCity)

                    ->firstWhere('brgy_name', $member->barangay);

                if ($barangay) {

                    $this->selectedBarangay = $barangay['brgy_code'];
                }
            }
        }   
    }

    public function updatedSelectedRegion($regionCode)
    {
        $region = collect($this->regions)
            ->firstWhere('region_code', $regionCode);

        $this->regionName = $region['region_name'] ?? null;

        // LOAD PROVINCES USING HELPER
        $this->loadProvinces($regionCode);

        // RESET DEPENDENTS
        $this->cities = [];
        $this->barangays = [];

        $this->selectedProvince = null;
        $this->selectedCity = null;
        $this->selectedBarangay = null;
    }

    public function updatedSelectedProvince($provinceCode)
    {
        $province = collect($this->provinces)
            ->firstWhere('province_code', $provinceCode);

        $this->provinceName = $province['province_name'] ?? null;

        // LOAD CITIES USING HELPER
        $this->loadCities($provinceCode);

        // RESET DEPENDENTS
        $this->barangays = [];

        $this->selectedCity = null;
        $this->selectedBarangay = null;
    }

    public function updatedSelectedCity($cityCode)
    {
        $city = collect($this->cities)
            ->firstWhere('city_code', $cityCode);

        $this->cityName = $city['city_name'] ?? null;

        // LOAD BARANGAYS USING HELPER
        $this->loadBarangays($cityCode);

        // RESET
        $this->selectedBarangay = null;
    }

    public function updatedSelectedBarangay($barangayCode)
    {
        $barangay = collect($this->barangays)
            ->firstWhere('brgy_code', $barangayCode);

        $this->barangayName = $barangay['brgy_name'] ?? null;
    }


    public function render()
    {
        return view('livewire.address-picker');
    }

    private function loadProvinces($regionCode)
    {
        $provinces = json_decode(
            file_get_contents(storage_path('app/address/province.json')),
            true
        );

        $this->provinces = collect($provinces)
            ->where('region_code', $regionCode)
            ->values()
            ->toArray();
    }

    private function loadCities($provinceCode)
    {
        $cities = json_decode(
            file_get_contents(storage_path('app/address/city.json')),
            true
        );

        $this->cities = collect($cities)
            ->where('province_code', $provinceCode)
            ->values()
            ->toArray();
    }

    private function loadBarangays($cityCode)
    {
        $barangays = json_decode(
            file_get_contents(storage_path('app/address/barangay.json')),
            true
        );

        $this->barangays = collect($barangays)
            ->where('city_code', $cityCode)
            ->values()
            ->toArray();
    }
}