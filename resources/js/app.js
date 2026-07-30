import L from '@neshan-maps-platform/leaflet';
import '@neshan-maps-platform/leaflet/dist/leaflet.css';

const NESHAN_MAP_API_KEY = import.meta.env.VITE_NESHAN_MAP_API_KEY || '';
const NESHAN_SERVICE_API_KEY = import.meta.env.VITE_NESHAN_SERVICE_API_KEY || '';
const NESHAN_DEFAULT_CITY = 'تهران';
const REPORT_AREA_RADIUS_METERS = 350;

let reportMaps = new Map();
let matchMaps = new Map();

const reportMarkerIcon = L.divIcon({
    className: 'report-map-marker',
    html: '<span class="report-map-marker__dot"></span>',
    iconSize: [28, 28],
    iconAnchor: [14, 14],
});

const matchMarkerIcon = L.divIcon({
    className: 'match-map-marker',
    html: '<span class="match-map-marker__dot"></span>',
    iconSize: [40, 40],
    iconAnchor: [20, 20],
});

const getReportSection = (container) => container.closest('[data-report-section]');

const reverseGeocodeCity = async (latitude, longitude) => {
    if (!NESHAN_SERVICE_API_KEY) return '';

    try {
        const response = await fetch(
            `https://api.neshan.org/v5/reverse?lat=${encodeURIComponent(latitude)}&lng=${encodeURIComponent(longitude)}`,
            {
                headers: {
                    'Api-Key': NESHAN_SERVICE_API_KEY,
                    Accept: 'application/json',
                },
            },
        );
        if (!response.ok) return '';
        const data = await response.json();
        return data?.city || data?.state || data?.formatted_address || '';
    } catch {
        return '';
    }
};

const syncReportLocation = async (container, latitude, longitude) => {
    const section = getReportSection(container);
    if (!section) return;

    const latitudeField = section.querySelector('[data-report-latitude]');
    const longitudeField = section.querySelector('[data-report-longitude]');
    const cityField = section.querySelector('[data-report-city-input]');
    const cityLabel = section.querySelector('[data-report-city]');
    const coordinatesLabel = section.querySelector('[data-report-coordinates]');
    const statusLabel = section.querySelector('[data-report-status]');

    if (latitudeField) latitudeField.value = latitude;
    if (longitudeField) longitudeField.value = longitude;
    if (statusLabel) statusLabel.textContent = 'در حال تشخیص شهر...';

    let city = await reverseGeocodeCity(latitude, longitude) || NESHAN_DEFAULT_CITY;

    if (cityField) cityField.value = city;
    if (cityLabel) cityLabel.textContent = city;
    if (coordinatesLabel) coordinatesLabel.textContent = `${latitude.toFixed(5)}, ${longitude.toFixed(5)}`;
    if (statusLabel) statusLabel.textContent = 'موقعیت انتخاب شد';

    latitudeField?.dispatchEvent(new Event('input', { bubbles: true }));
    longitudeField?.dispatchEvent(new Event('input', { bubbles: true }));
    cityField?.dispatchEvent(new Event('input', { bubbles: true }));
};

const initializeReportMap = (container) => {
    if (!(container instanceof HTMLElement) || container._leaflet_id || reportMaps.has(container)) {
        return;
    }

    const initialLatitude = Number(container.dataset.initialLatitude || 35.6892);
    const initialLongitude = Number(container.dataset.initialLongitude || 51.3890);
    
    const map = new L.Map(container, {
        key: NESHAN_MAP_API_KEY,
        maptype: 'dreamy',
        zoomControl: true,
        scrollWheelZoom: false,
        center: [initialLatitude, initialLongitude],
        zoom: 13,
    });

    const marker = L.marker([initialLatitude, initialLongitude], {
        draggable: true,
        icon: reportMarkerIcon,
    }).addTo(map);

    const areaCircle = L.circle([initialLatitude, initialLongitude], {
        radius: REPORT_AREA_RADIUS_METERS,
        color: '#2A9DB0',
        weight: 2,
        opacity: 0.75,
        fillColor: '#2A9DB0',
        fillOpacity: 0.18,
    }).addTo(map);
    
    syncReportLocation(container, initialLatitude, initialLongitude);
    
    const resizeObserver = new ResizeObserver(() => {
        map.invalidateSize();
    });
    resizeObserver.observe(container);

    const section = getReportSection(container);
    const statusLabel = section?.querySelector('[data-report-status]');

    const updateFromLatLng = async (latlng) => {
        marker.setLatLng(latlng);
        areaCircle.setLatLng(latlng);
        await syncReportLocation(container, latlng.lat, latlng.lng);
    };

    const useCurrentLocation = () => {
        if (!NESHAN_MAP_API_KEY || !navigator.geolocation) return;
        if (statusLabel) statusLabel.textContent = 'در حال درخواست اجازه موقعیت...';

        navigator.geolocation.getCurrentPosition(
            async (position) => {
                const { latitude, longitude } = position.coords;
                const latlng = L.latLng(latitude, longitude);
                marker.setLatLng(latlng);
                areaCircle.setLatLng(latlng);
                map.setView(latlng, 15);
                await syncReportLocation(container, latitude, longitude);
            },
            (error) => {
                if (statusLabel) {
                    statusLabel.textContent = error.code === error.PERMISSION_DENIED
                        ? 'دسترسی به موقعیت رد شد.'
                        : 'نتوانستم موقعیت فعلی را دریافت کنم.';
                }
            },
            { enableHighAccuracy: true, timeout: 10000, maximumAge: 60000 }
        );
    };

    map.on('click', (event) => updateFromLatLng(event.latlng));
    marker.on('dragend', async () => {
        const latlng = marker.getLatLng();
        areaCircle.setLatLng(latlng);
        await syncReportLocation(container, latlng.lat, latlng.lng);
    });

    reportMaps.set(container, { map, marker, areaCircle, useCurrentLocation, resizeObserver });
};

const bootReportMaps = () => {
    document.querySelectorAll('[data-report-map]').forEach(initializeReportMap);
};

const getMatchMapState = (section) => {
    const mapContainer = section?.querySelector('[data-match-map]');
    return mapContainer ? matchMaps.get(mapContainer) || null : null;
};

const updateMatchMapLocation = (section, latitude, longitude) => {
    const state = getMatchMapState(section);
    if (!state) return;

    const latlng = L.latLng(latitude, longitude);
    state.marker.setLatLng(latlng);
    state.map.setView(latlng, state.map.getZoom());
    state.map.invalidateSize();
};

const initializeMatchMap = (container) => {
    if (!(container instanceof HTMLElement) || container._leaflet_id || matchMaps.has(container)) {
        return;
    }

    const initialLatitude = Number(container.dataset.initialLatitude || 35.6892);
    const initialLongitude = Number(container.dataset.initialLongitude || 51.3890);
    const containerRoot = container.closest('[data-match-section]');
    const latitudeField = containerRoot?.querySelector('[data-match-latitude]');
    const longitudeField = containerRoot?.querySelector('[data-match-longitude]');

    const selectedLatitude = latitudeField instanceof HTMLInputElement ? Number(latitudeField.value) : NaN;
    const selectedLongitude = longitudeField instanceof HTMLInputElement ? Number(longitudeField.value) : NaN;
    const hasSelectedLocation = !Number.isNaN(selectedLatitude) && !Number.isNaN(selectedLongitude) && latitudeField?.value !== '' && longitudeField?.value !== '';

    const startLatitude = hasSelectedLocation ? selectedLatitude : initialLatitude;
    const startLongitude = hasSelectedLocation ? selectedLongitude : initialLongitude;
    
    const map = new L.Map(container, {
        key: NESHAN_MAP_API_KEY,
        maptype: 'dreamy',
        zoomControl: true,
        scrollWheelZoom: false,
        center: [startLatitude, startLongitude],
        zoom: 13,
    });

    const marker = L.marker([startLatitude, startLongitude], {
        draggable: true,
        icon: matchMarkerIcon,
    }).addTo(map);

    const updateInputs = (latlng) => {
        if (latitudeField instanceof HTMLInputElement) {
            latitudeField.value = latlng.lat.toString();
            latitudeField.dispatchEvent(new Event('input', { bubbles: true }));
        }
        if (longitudeField instanceof HTMLInputElement) {
            longitudeField.value = latlng.lng.toString();
            longitudeField.dispatchEvent(new Event('input', { bubbles: true }));
        }
    };

    const onPositionChanged = async (latlng) => {
        marker.setLatLng(latlng);
        map.setView(latlng, map.getZoom());
        updateInputs(latlng);
    };

    map.on('click', (event) => onPositionChanged(event.latlng));
    marker.on('dragend', () => onPositionChanged(marker.getLatLng()));

    const resizeObserver = new ResizeObserver(() => {
        map.invalidateSize();
    });
    resizeObserver.observe(container);

    matchMaps.set(container, { map, marker, resizeObserver });
};

const bootMatchMaps = () => {
    document.querySelectorAll('[data-match-map]').forEach(initializeMatchMap);
};

// FIX 1: Disconnect and re-observe the NEW document.body on navigation shifts
const watchForMaps = () => {
    if (window.__mapObserver) {
        window.__mapObserver.disconnect();
    }

    window.__mapObserver = new MutationObserver(() => {
        bootReportMaps();
        bootMatchMaps();
    });

    window.__mapObserver.observe(document.body, { childList: true, subtree: true });
};

// FIX 2: Aggressive cleanup of structural elements and properties to prevent morph caching errors
const teardownAllMaps = () => {
    reportMaps.forEach((state, container) => {
        if (state.resizeObserver) state.resizeObserver.disconnect();
        if (state.map) {
            try { state.map.off(); state.map.remove(); } catch (e) {}
        }
        if (container) {
            delete container._leaflet_id;
            container.innerHTML = '';
        }
    });
    reportMaps.clear();

    matchMaps.forEach((state, container) => {
        if (state.resizeObserver) state.resizeObserver.disconnect();
        if (state.map) {
            try { state.map.off(); state.map.remove(); } catch (e) {}
        }
        if (container) {
            delete container._leaflet_id;
            container.innerHTML = '';
        }
    });
    matchMaps.clear();

    document.querySelectorAll('[data-match-section]').forEach(el => {
        delete el.dataset.matchReady;
        delete el.dataset.autoRequested;
    });
};

// Lifecycle Events hook setup
document.addEventListener('livewire:navigating', teardownAllMaps);
document.addEventListener('livewire:navigated', () => {
    watchForMaps(); // Re-bind observer to the modern swapped body element
    bootReportMaps();
    bootMatchMaps();
    bootMatchSections();
});

document.addEventListener('DOMContentLoaded', () => {
    bootReportMaps();
    bootMatchMaps();
    bootMatchSections();
    watchForMaps();
});

// Fixed Click Event Listener
document.addEventListener('click', (event) => {
    const button = event.target.closest('[data-report-current-location]');
    if (!button) return;

    const mapContainer = button.closest('[data-report-section]')?.querySelector('[data-report-map]');
    if (mapContainer) reportMaps.get(mapContainer)?.useCurrentLocation?.();
});

const initializeMatchSection = (section) => {
    if (!(section instanceof HTMLElement) || section.dataset.matchReady === '1') return;
    section.dataset.matchReady = '1';

    const requestButton = section.querySelector('[data-match-request-location]');
    
    const requestCurrentLocation = () => {
        if (!navigator.geolocation) return;

        setMatchStatus(section, 'در حال درخواست دسترسی به موقعیت...');
        setMatchLocationLoading(section, true);

        navigator.geolocation.getCurrentPosition(
            (position) => {
                const { latitude, longitude } = position.coords;
                updateMatchMapLocation(section, latitude, longitude);
                syncMatchField(section.querySelector('[data-match-latitude]'), String(latitude));
                syncMatchField(section.querySelector('[data-match-longitude]'), String(longitude));
                syncMatchField(section.querySelector('[data-match-permission]'), 'granted');
                setMatchStatus(section, 'موقعیت دریافت شد.');
                setMatchLocationLoading(section, false);
            },
            (error) => {
                const denied = error.code === error.PERMISSION_DENIED;
                syncMatchField(section.querySelector('[data-match-permission]'), denied ? 'denied' : 'error');
                setMatchStatus(section, 'موقعیت دریافت نشد.');
                setMatchLocationLoading(section, false);
            },
            { enableHighAccuracy: true, timeout: 10000, maximumAge: 60000 }
        );
    };

    if (requestButton) requestButton.addEventListener('click', requestCurrentLocation);
    if (section.dataset.autoRequested !== '1') {
        section.dataset.autoRequested = '1';
        setTimeout(requestCurrentLocation, 250);
    }
};

const bootMatchSections = () => {
    document.querySelectorAll('[data-match-section]').forEach(initializeMatchSection);
};

const setMatchStatus = (section, message) => {
    const status = section?.querySelector('[data-match-status]');
    if (status) status.textContent = message;
};

const setMatchLocationLoading = (section, isLoading) => {
    const loading = section?.querySelector('[data-match-location-loading]');
    if (loading instanceof HTMLElement) {
        loading.classList.toggle('hidden', !isLoading);
        loading.classList.toggle('flex', isLoading);
    }
};

const syncMatchField = (field, value) => {
    if (field) {
        field.value = value;
        field.dispatchEvent(new Event('input', { bubbles: true }));
    }
};