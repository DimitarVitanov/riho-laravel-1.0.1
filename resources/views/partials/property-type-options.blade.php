{{-- Property Type Options - Grouped by Category --}}
<option value="">Select property type...</option>
<optgroup label="Residential Apartments">
    <option value="apartment" {{ ($selected ?? '') === 'apartment' ? 'selected' : '' }}>Apartment</option>
    <option value="studio_apartment" {{ ($selected ?? '') === 'studio_apartment' ? 'selected' : '' }}>Studio Apartment</option>
    <option value="duplex_apartment" {{ ($selected ?? '') === 'duplex_apartment' ? 'selected' : '' }}>Duplex Apartment</option>
    <option value="penthouse" {{ ($selected ?? '') === 'penthouse' ? 'selected' : '' }}>Penthouse</option>
    <option value="loft" {{ ($selected ?? '') === 'loft' ? 'selected' : '' }}>Loft</option>
    <option value="serviced_apartment" {{ ($selected ?? '') === 'serviced_apartment' ? 'selected' : '' }}>Serviced Apartment</option>
</optgroup>
<optgroup label="Houses and Villas">
    <option value="house" {{ ($selected ?? '') === 'house' ? 'selected' : '' }}>House</option>
    <option value="detached_house" {{ ($selected ?? '') === 'detached_house' ? 'selected' : '' }}>Detached House</option>
    <option value="semi_detached_house" {{ ($selected ?? '') === 'semi_detached_house' ? 'selected' : '' }}>Semi-Detached House</option>
    <option value="terraced_house" {{ ($selected ?? '') === 'terraced_house' ? 'selected' : '' }}>Terraced House</option>
    <option value="townhouse" {{ ($selected ?? '') === 'townhouse' ? 'selected' : '' }}>Townhouse</option>
    <option value="villa" {{ ($selected ?? '') === 'villa' ? 'selected' : '' }}>Villa</option>
    <option value="luxury_villa" {{ ($selected ?? '') === 'luxury_villa' ? 'selected' : '' }}>Luxury Villa</option>
    <option value="bungalow" {{ ($selected ?? '') === 'bungalow' ? 'selected' : '' }}>Bungalow</option>
    <option value="cottage" {{ ($selected ?? '') === 'cottage' ? 'selected' : '' }}>Cottage</option>
    <option value="farmhouse" {{ ($selected ?? '') === 'farmhouse' ? 'selected' : '' }}>Farmhouse</option>
    <option value="mansion_estate" {{ ($selected ?? '') === 'mansion_estate' ? 'selected' : '' }}>Mansion / Estate</option>
</optgroup>
<optgroup label="Buildings and Projects">
    <option value="residential_building" {{ ($selected ?? '') === 'residential_building' ? 'selected' : '' }}>Residential Building</option>
    <option value="mixed_use_building" {{ ($selected ?? '') === 'mixed_use_building' ? 'selected' : '' }}>Mixed-Use Building</option>
    <option value="new_build_unit" {{ ($selected ?? '') === 'new_build_unit' ? 'selected' : '' }}>New-Build Unit</option>
    <option value="development_project" {{ ($selected ?? '') === 'development_project' ? 'selected' : '' }}>Development Project</option>
</optgroup>
<optgroup label="Land">
    <option value="building_land" {{ ($selected ?? '') === 'building_land' ? 'selected' : '' }}>Building Land / Plot</option>
    <option value="agricultural_land" {{ ($selected ?? '') === 'agricultural_land' ? 'selected' : '' }}>Agricultural Land</option>
    <option value="commercial_land" {{ ($selected ?? '') === 'commercial_land' ? 'selected' : '' }}>Commercial Land</option>
    <option value="development_land" {{ ($selected ?? '') === 'development_land' ? 'selected' : '' }}>Development Land</option>
</optgroup>
<optgroup label="Commercial and Hospitality">
    <option value="commercial_property" {{ ($selected ?? '') === 'commercial_property' ? 'selected' : '' }}>Commercial Property</option>
    <option value="office" {{ ($selected ?? '') === 'office' ? 'selected' : '' }}>Office</option>
    <option value="retail_shop" {{ ($selected ?? '') === 'retail_shop' ? 'selected' : '' }}>Retail / Shop</option>
    <option value="restaurant_cafe" {{ ($selected ?? '') === 'restaurant_cafe' ? 'selected' : '' }}>Restaurant / Café</option>
    <option value="hotel" {{ ($selected ?? '') === 'hotel' ? 'selected' : '' }}>Hotel</option>
    <option value="guesthouse" {{ ($selected ?? '') === 'guesthouse' ? 'selected' : '' }}>Guesthouse / B&B</option>
    <option value="warehouse" {{ ($selected ?? '') === 'warehouse' ? 'selected' : '' }}>Warehouse</option>
    <option value="industrial_property" {{ ($selected ?? '') === 'industrial_property' ? 'selected' : '' }}>Industrial Property</option>
</optgroup>
<optgroup label="Other">
    <option value="garage" {{ ($selected ?? '') === 'garage' ? 'selected' : '' }}>Garage</option>
    <option value="parking_space" {{ ($selected ?? '') === 'parking_space' ? 'selected' : '' }}>Parking Space</option>
    <option value="storage" {{ ($selected ?? '') === 'storage' ? 'selected' : '' }}>Storage</option>
    <option value="other" {{ ($selected ?? '') === 'other' ? 'selected' : '' }}>Other</option>
</optgroup>
