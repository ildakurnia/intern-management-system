<script>
  document.addEventListener('DOMContentLoaded', function () {
    const pickers = document.querySelectorAll('[data-institution-picker]');

    pickers.forEach(function (input) {
      const hiddenId = document.getElementById(input.dataset.hiddenId);
      const hiddenManual = document.getElementById(input.dataset.hiddenManual);
      const helper = document.getElementById(input.dataset.helperId);
      const results = document.getElementById(input.dataset.resultsId);
      const searchUrl = input.dataset.searchUrl;
      const selectedLabel = (input.dataset.selectedLabel || '').trim().toLowerCase();
      let institutions = [];
      let activeRequest = 0;

      const renderHelper = function (message, tone) {
        if (!helper) {
          return;
        }

        helper.textContent = message;
        helper.classList.remove('text-success', 'text-warning', 'text-body-secondary');

        if (tone === 'success') {
          helper.classList.add('text-success');
          return;
        }

        if (tone === 'warning') {
          helper.classList.add('text-warning');
          return;
        }

        helper.classList.add('text-body-secondary');
      };

      const hideResults = function () {
        if (!results) {
          return;
        }

        results.classList.add('d-none');
        results.innerHTML = '';
      };

      const triggerSelectionSync = function () {
        hiddenId?.dispatchEvent(new Event('change', { bubbles: true }));
        input.dispatchEvent(new Event('change', { bubbles: true }));
      };

      const setManualSelection = function () {
        hiddenId.value = '';
        hiddenManual.value = input.value.trim();

        if (input.value.trim() === '') {
          renderHelper('Ketik nama institusi. Jika cocok dengan master, pilih dari daftar. Jika belum ada, sistem akan menambahkannya ke daftar institusi.', 'default');
          return;
        }

        renderHelper('Klik institusi dari daftar agar sistem memakai data resmi. Jika belum ada, nama ini akan ditambahkan ke daftar institusi saat disimpan.', 'warning');
      };

      const selectInstitution = function (institution) {
        input.value = institution.name;
        hiddenId.value = institution.id;
        hiddenManual.value = '';
        hideResults();
        renderHelper('Institusi ditemukan di master dan akan dipakai sebagai data resmi.', 'success');
        triggerSelectionSync();
      };

      const renderResults = function (items) {
        if (!results) {
          return;
        }

        if (!items.length) {
          results.innerHTML = '<div class="institution-picker-empty">Tidak ada hasil yang cocok. Anda tetap bisa lanjut, dan nama institusi ini akan otomatis ditambahkan ke daftar agar bisa dipilih lagi nanti.</div>';
          results.classList.remove('d-none');
          return;
        }

        results.innerHTML = '';

        items.forEach(function (institution) {
          const button = document.createElement('button');
          button.type = 'button';
          button.className = 'institution-picker-option';
          button.textContent = institution.name;
          button.dataset.id = institution.id;
          button.addEventListener('mousedown', function (event) {
            event.preventDefault();
            selectInstitution(institution);
          });
          results.appendChild(button);
        });

        results.classList.remove('d-none');
      };

      const fetchOptions = async function () {
        const query = input.value.trim();

        if (query.length < 2) {
          institutions = [];
          hideResults();
          setManualSelection();
          return;
        }

        const requestId = ++activeRequest;

        try {
          const response = await fetch(searchUrl + '?q=' + encodeURIComponent(query), {
            headers: {
              'X-Requested-With': 'XMLHttpRequest',
              'Accept': 'application/json',
            },
          });

          if (!response.ok) {
            throw new Error('Request failed');
          }

          const payload = await response.json();

          if (requestId !== activeRequest) {
            return;
          }

          institutions = Array.isArray(payload.data) ? payload.data : [];
          renderResults(institutions);

          const normalizedQuery = query.toLowerCase();
          const exactMatch = institutions.find(function (institution) {
            return institution.name.trim().toLowerCase() === normalizedQuery;
          });

          if (exactMatch && hiddenId.value && input.value.trim().toLowerCase() === exactMatch.name.trim().toLowerCase()) {
            renderHelper('Institusi ditemukan di master dan akan dipakai sebagai data resmi.', 'success');
            return;
          }

          if (institutions.length > 0) {
            renderHelper('Pilih institusi dari daftar agar data resmi langsung terhubung.', 'default');
            return;
          }

          setManualSelection();
        } catch (error) {
          institutions = [];
          hideResults();
          renderHelper('Gagal mengambil daftar institusi. Anda tetap bisa menyimpan nama ini, dan sistem akan menambahkannya ke daftar saat data berhasil disimpan.', 'warning');
        }
      };

      input.addEventListener('input', function () {
        hiddenId.value = '';
        hiddenManual.value = input.value.trim();
        fetchOptions();
        triggerSelectionSync();
      });

      input.addEventListener('focus', function () {
        if (institutions.length > 0 && input.value.trim().length >= 2 && results?.childElementCount) {
          results.classList.remove('d-none');
        }
      });

      input.addEventListener('blur', function () {
        window.setTimeout(function () {
          hideResults();

          const currentValue = input.value.trim().toLowerCase();
          const selectedOption = institutions.find(function (institution) {
            return institution.name.trim().toLowerCase() === currentValue;
          });

          if (selectedOption && hiddenId.value === String(selectedOption.id)) {
            renderHelper('Institusi ditemukan di master dan akan dipakai sebagai data resmi.', 'success');
            return;
          }

          setManualSelection();
        }, 120);
      });

      document.addEventListener('click', function (event) {
        if (event.target === input || results?.contains(event.target)) {
          return;
        }

        hideResults();
      });

      if (hiddenId.value && input.value.trim() !== '' && input.value.trim().toLowerCase() === selectedLabel) {
        renderHelper('Institusi ditemukan di master dan akan dipakai sebagai data resmi.', 'success');
      } else if (hiddenId.value && input.value.trim() !== '') {
        renderHelper('Institusi ditemukan di master dan akan dipakai sebagai data resmi.', 'success');
      } else {
        setManualSelection();
      }
    });
  });
</script>
