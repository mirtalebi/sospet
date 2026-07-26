import L from '@neshan-maps-platform/leaflet';
import '@neshan-maps-platform/leaflet/dist/leaflet.css';

const NESHAN_MAP_API_KEY = import.meta.env.VITE_NESHAN_MAP_API_KEY || '';
const NESHAN_SERVICE_API_KEY = import.meta.env.VITE_NESHAN_SERVICE_API_KEY || '';
const NESHAN_DEFAULT_CITY = 'تهران';
const REPORT_AREA_RADIUS_METERS = 350;

const reportMaps = new WeakMap();
const matchMaps = new WeakMap();

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
	if (!NESHAN_SERVICE_API_KEY) {
		return '';
	}

	const response = await fetch(
		`https://api.neshan.org/v5/reverse?lat=${encodeURIComponent(latitude)}&lng=${encodeURIComponent(longitude)}`,
		{
			headers: {
				'Api-Key': NESHAN_SERVICE_API_KEY,
				Accept: 'application/json',
			},
		},
	);

	if (!response.ok) {
		throw new Error('Reverse geocoding failed');
	}

	const data = await response.json();
	const address = data ?? {};

	return (
		address.city ||
		address.state ||
		address.formatted_address ||
		''
	);
};

const syncReportLocation = async (container, latitude, longitude) => {
	const section = getReportSection(container);
	if (!section) {
		return;
	}

	const latitudeField = section.querySelector('[data-report-latitude]');
	const longitudeField = section.querySelector('[data-report-longitude]');
	const cityField = section.querySelector('[data-report-city-input]');
	const cityLabel = section.querySelector('[data-report-city]');
	const coordinatesLabel = section.querySelector('[data-report-coordinates]');
	const statusLabel = section.querySelector('[data-report-status]');

	if (latitudeField) latitudeField.value = latitude;
	if (longitudeField) longitudeField.value = longitude;

	if (statusLabel) {
		statusLabel.textContent = 'در حال تشخیص شهر...';
	}

	let city = '';

	try {
		city = await reverseGeocodeCity(latitude, longitude);
	} catch {
		city = '';
	}

	if (!city) {
		city = NESHAN_DEFAULT_CITY;
	}

	if (cityField) cityField.value = city;
	if (cityLabel) cityLabel.textContent = city;
	if (coordinatesLabel) coordinatesLabel.textContent = `${latitude.toFixed(5)}, ${longitude.toFixed(5)}`;
	if (statusLabel) {
		statusLabel.textContent = 'موقعیت انتخاب شد';
	}

	latitudeField?.dispatchEvent(new Event('input', { bubbles: true }));
	longitudeField?.dispatchEvent(new Event('input', { bubbles: true }));
	cityField?.dispatchEvent(new Event('input', { bubbles: true }));
};

const initializeReportMap = (container) => {
	if (!(container instanceof HTMLElement) || reportMaps.has(container)) {
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
	}).setView([initialLatitude, initialLongitude], 13);

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
	requestAnimationFrame(() => {
		map.invalidateSize();
	});

	const section = getReportSection(container);
	const statusLabel = section?.querySelector('[data-report-status]');

	const updateFromLatLng = async (latlng) => {
		marker.setLatLng(latlng);
		areaCircle.setLatLng(latlng);
		await syncReportLocation(container, latlng.lat, latlng.lng);
	};

	const useCurrentLocation = () => {
		if (!NESHAN_MAP_API_KEY) {
			if (statusLabel) {
				statusLabel.textContent = 'کلید نقشه نشان تنظیم نشده است.';
			}
			return;
		}

		if (!navigator.geolocation) {
			if (statusLabel) {
				statusLabel.textContent = 'مرورگر شما از موقعیت‌یابی پشتیبانی نمی‌کند.';
			}
			return;
		}

		if (statusLabel) {
			statusLabel.textContent = 'در حال درخواست اجازه موقعیت...';
		}

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
						? 'دسترسی به موقعیت رد شد. اگر خواستی دستی روی نقشه انتخاب کن.'
						: 'نتوانستم موقعیت فعلی را دریافت کنم. لطفاً دستی روی نقشه انتخاب کن.';
				}
			},
			{
				enableHighAccuracy: true,
				timeout: 10000,
				maximumAge: 60000,
			},
		);
	};

	map.on('click', (event) => updateFromLatLng(event.latlng));
	marker.on('dragend', async () => {
		const latlng = marker.getLatLng();
		areaCircle.setLatLng(latlng);
		await syncReportLocation(container, latlng.lat, latlng.lng);
	});

	if (!NESHAN_MAP_API_KEY && statusLabel) {
		statusLabel.textContent = 'برای نمایش کامل نقشه، VITE_NESHAN_MAP_API_KEY را تنظیم کنید.';
	}

	reportMaps.set(container, { map, marker, areaCircle, useCurrentLocation });
};

const bootReportMaps = () => {
	document.querySelectorAll('[data-report-map]').forEach((container) => initializeReportMap(container));
};

const getMatchMapState = (section) => {
	const mapContainer = section?.querySelector('[data-match-map]');
	if (!(mapContainer instanceof HTMLElement)) {
		return null;
	}

	return matchMaps.get(mapContainer) ?? null;
};

const updateMatchMapLocation = (section, latitude, longitude) => {
	const state = getMatchMapState(section);
	if (!state) {
		return;
	}

	const latlng = L.latLng(latitude, longitude);
	state.marker.setLatLng(latlng);
	state.map.setView(latlng, state.map.getZoom());
};

const initializeMatchMap = (container) => {
	if (!(container instanceof HTMLElement) || matchMaps.has(container)) {
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
	}).setView([startLatitude, startLongitude], 13);

	const marker = L.marker([startLatitude, startLongitude], {
		draggable: true,
		icon: matchMarkerIcon,
	}).addTo(map);

	const updateInputs = (latlng) => {
		const containerRoot = container.closest('[data-match-section]');
		const latitudeField = containerRoot?.querySelector('[data-match-latitude]');
		const longitudeField = containerRoot?.querySelector('[data-match-longitude]');
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
	marker.on('dragend', () => {
		const latlng = marker.getLatLng();
		onPositionChanged(latlng);
	});

	requestAnimationFrame(() => {
		map.invalidateSize();
	});

	matchMaps.set(container, { map, marker });
};

const bootMatchMaps = () => {
	document.querySelectorAll('[data-match-map]').forEach((container) => initializeMatchMap(container));
};

const watchForReportMaps = () => {
	if (window.__reportMapObserver) {
		return;
	}

	window.__reportMapObserver = new MutationObserver(() => {
		document.querySelectorAll('[data-report-map]').forEach((container) => initializeReportMap(container));
		document.querySelectorAll('[data-match-map]').forEach((container) => initializeMatchMap(container));
	});

	window.__reportMapObserver.observe(document.body, {
		childList: true,
		subtree: true,
	});
};

document.addEventListener('DOMContentLoaded', bootReportMaps);
document.addEventListener('livewire:navigated', bootReportMaps);
document.addEventListener('livewire:initialized', bootReportMaps);

document.addEventListener('DOMContentLoaded', bootMatchMaps);

document.addEventListener('livewire:navigated', bootMatchMaps);

document.addEventListener('livewire:initialized', bootMatchMaps);
document.addEventListener('livewire:navigating', () => {
	reportMaps.clear?.();
});
document.addEventListener('livewire:initialized', watchForReportMaps);
document.addEventListener('DOMContentLoaded', watchForReportMaps);

document.addEventListener('click', (event) => {
	const target = event.target;
	if (!(target instanceof Element)) {
		return;
	}

	const button = target.closest('[data-report-current-location]');
	if (!button) {
		return;
	}

	const section = button.closest('[data-report-section]');
	const mapContainer = section?.querySelector('[data-report-map]');
	if (!(mapContainer instanceof HTMLElement)) {
		return;
	}

	const mapState = reportMaps.get(mapContainer);
	mapState?.useCurrentLocation?.();
});

const setMatchStatus = (section, message) => {
	const status = section?.querySelector('[data-match-status]');
	if (status) {
		status.textContent = message;
	}
};

const setMatchLocationLoading = (section, isLoading) => {
	const loading = section?.querySelector('[data-match-location-loading]');
	if (!(loading instanceof HTMLElement)) {
		return;
	}

	loading.classList.toggle('hidden', !isLoading);
	loading.classList.toggle('flex', isLoading);
};

const syncMatchField = (field, value) => {
	if (!field) {
		return;
	}

	field.value = value;
	field.dispatchEvent(new Event('input', { bubbles: true }));
};

const initializeMatchSection = (section) => {
	if (!(section instanceof HTMLElement) || section.dataset.matchReady === '1') {
		return;
	}

	section.dataset.matchReady = '1';
	const requestButton = section.querySelector('[data-match-request-location]');
	if (!(requestButton instanceof HTMLElement)) {
		return;
	}

	const requestCurrentLocation = () => {
		if (!navigator.geolocation) {
			setMatchStatus(section, 'مرورگر شما از موقعیت یابی پشتیبانی نمی کند. لطفا شهر را انتخاب کنید.');
			syncMatchField(section.querySelector('[data-match-permission]'), 'error');
			setMatchLocationLoading(section, false);
			return;
		}

		setMatchStatus(section, 'در حال درخواست دسترسی به موقعیت...');
		setMatchLocationLoading(section, true);

		navigator.geolocation.getCurrentPosition(
			(position) => {
				const { latitude, longitude } = position.coords;
				updateMatchMapLocation(section, latitude, longitude);
				syncMatchField(section.querySelector('[data-match-latitude]'), String(latitude));
				syncMatchField(section.querySelector('[data-match-longitude]'), String(longitude));
				syncMatchField(section.querySelector('[data-match-permission]'), 'granted');
				setMatchStatus(section, 'موقعیت دریافت شد. نزدیک ترین موارد نمایش داده می شوند.');
				setMatchLocationLoading(section, false);
			},
			(error) => {
				const denied = error.code === error.PERMISSION_DENIED;
				syncMatchField(section.querySelector('[data-match-permission]'), denied ? 'denied' : 'error');
				setMatchStatus(
					section,
					denied
						? 'دسترسی به موقعیت رد شد. لطفا شهر را انتخاب کنید.'
						: 'موقعیت دریافت نشد. لطفا شهر را انتخاب کنید.',
				);
				setMatchLocationLoading(section, false);
			},
			{
				enableHighAccuracy: true,
				timeout: 10000,
				maximumAge: 60000,
			},
		);
	};

	requestButton.addEventListener('click', requestCurrentLocation);

	if (section.dataset.autoRequested !== '1') {
		section.dataset.autoRequested = '1';
		requestCurrentLocation();
	}
};

const bootMatchSections = () => {
	document.querySelectorAll('[data-match-section]').forEach((section) => initializeMatchSection(section));
};

document.addEventListener('DOMContentLoaded', bootMatchSections);
document.addEventListener('livewire:navigated', bootMatchSections);
document.addEventListener('livewire:initialized', bootMatchSections);
