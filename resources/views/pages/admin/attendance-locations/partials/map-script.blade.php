<script>
  document.addEventListener('DOMContentLoaded', function () {
    if (typeof window.leaFlet === 'undefined') {
      return;
    }

    const latitudeInput = document.getElementById('latitude');
    const longitudeInput = document.getElementById('longitude');
    const radiusInput = document.getElementById('radius_meters');
    const radiusBadge = document.getElementById('radiusValueBadge');
    const browserButton = document.getElementById('btnUseBrowserLocation');

    const fallbackLat = parseFloat(latitudeInput.value || '1.1622890');
    const fallbackLng = parseFloat(longitudeInput.value || '104.0049370');
    const fallbackRadius = parseInt(radiusInput.value || '100', 10);

    const map = window.leaFlet.map('attendanceLocationMap').setView([fallbackLat, fallbackLng], 16);

    window.leaFlet.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    const marker = window.leaFlet.marker([fallbackLat, fallbackLng], { draggable: true }).addTo(map);
    let circle = window.leaFlet.circle([fallbackLat, fallbackLng], {
      radius: fallbackRadius,
      color: '#666cff',
      fillColor: '#666cff',
      fillOpacity: 0.15
    }).addTo(map);

    const syncInputsFromMap = latlng => {
      latitudeInput.value = Number(latlng.lat).toFixed(7);
      longitudeInput.value = Number(latlng.lng).toFixed(7);
    };

    const updateRadiusBadge = value => {
      radiusBadge.textContent = `${value} m`;
    };

    const redrawCircle = () => {
      const lat = parseFloat(latitudeInput.value);
      const lng = parseFloat(longitudeInput.value);
      const radius = parseInt(radiusInput.value || '100', 10);

      if (Number.isNaN(lat) || Number.isNaN(lng)) {
        return;
      }

      const latlng = [lat, lng];
      marker.setLatLng(latlng);
      circle.setLatLng(latlng);
      circle.setRadius(radius);
      updateRadiusBadge(radius);
    };

    marker.on('dragend', function (event) {
      const latlng = event.target.getLatLng();
      syncInputsFromMap(latlng);
      redrawCircle();
    });

    map.on('click', function (event) {
      syncInputsFromMap(event.latlng);
      redrawCircle();
    });

    [latitudeInput, longitudeInput].forEach(input => {
      input.addEventListener('change', redrawCircle);
    });

    radiusInput.addEventListener('input', redrawCircle);
    updateRadiusBadge(fallbackRadius);

    browserButton.addEventListener('click', function () {
      if (!navigator.geolocation) {
        window.alert('Browser ini tidak mendukung geolocation.');
        return;
      }

      browserButton.disabled = true;
      browserButton.innerHTML = '<i class="ri ri-loader-4-line me-1"></i> Mengambil lokasi...';

      navigator.geolocation.getCurrentPosition(function (position) {
        const latlng = {
          lat: position.coords.latitude,
          lng: position.coords.longitude
        };

        syncInputsFromMap(latlng);
        redrawCircle();
        map.setView([latlng.lat, latlng.lng], 17);

        browserButton.disabled = false;
        browserButton.innerHTML = '<i class="ri ri-map-pin-user-line me-1"></i> Update Lokasi Saat Ini (Browser)';
      }, function () {
        browserButton.disabled = false;
        browserButton.innerHTML = '<i class="ri ri-map-pin-user-line me-1"></i> Update Lokasi Saat Ini (Browser)';
        window.alert('Lokasi gagal diambil. Pastikan izin lokasi browser sudah aktif.');
      }, {
        enableHighAccuracy: true,
        timeout: 10000
      });
    });
  });
</script>
