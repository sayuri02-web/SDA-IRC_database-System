<div>

    <!-- REGION + PROVINCE -->
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Region</label>

                <select wire:model.live="selectedRegion"
                        name="region"
                        class="form-control" required>

                    <option value="">-- Select Region --</option>

                    @foreach($regions as $region)
                        <option value="{{ $region['region_code'] }}">
                            {{ $region['region_name'] }}
                        </option>
                    @endforeach

                </select>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label>Province</label>

                <select wire:model.live="selectedProvince"
                        name="province"
                        class="form-control" required>

                    <option value="">-- Select Province --</option>

                    @foreach($provinces as $province)
                        <option value="{{ $province['province_code'] }}">
                            {{ $province['province_name'] }}
                        </option>
                    @endforeach

                </select>
            </div>
        </div>

    </div>

    <!-- CITY + BARANGAY -->
    <div class="row">

        <div class="col-md-6">
            <div class="form-group">
                <label>City / Municipality</label>

                <select wire:model.live="selectedCity"
                        name="city"
                        class="form-control" required>

                    <option value="">-- Select City --</option>

                    @foreach($cities as $city)
                        <option value="{{ $city['city_code'] }}">
                            {{ $city['city_name'] }}
                        </option>
                    @endforeach

                </select>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label>Barangay</label>

                <select wire:model.live="selectedBarangay"
                        name="barangay"
                        class="form-control" required>

                    <option value="">-- Select Barangay --</option>

                    @foreach($barangays as $barangay)
                        <option value="{{ $barangay['brgy_code'] }}">
                            {{ $barangay['brgy_name'] }}
                        </option>
                    @endforeach

                </select>
            </div>
        </div>

    </div>

    <!-- STREET -->
    <div class="row">

        <div class="col-md-6">
            <div class="form-group">
                <label>Street / Purok / Sitio</label>

                <input type="text"
                       wire:model.live="street"
                       name="street"
                       class="form-control"
                       placeholder="Enter street / purok / sitio"
                       required>
            </div>
        </div>

    </div>

    <input type="hidden" name="region" value="{{ $regionName }}">
    <input type="hidden" name="province" value="{{ $provinceName }}">
    <input type="hidden" name="city" value="{{ $cityName }}">
    <input type="hidden" name="barangay" value="{{ $barangayName }}">

</div>