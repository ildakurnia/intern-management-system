<!-- BEGIN: Vendor JS-->

@vite(['resources/assets/vendor/libs/jquery/jquery.js', 'resources/assets/vendor/libs/popper/popper.js', 'resources/assets/vendor/js/bootstrap.js', 'resources/assets/vendor/libs/node-waves/node-waves.js', 'resources/assets/vendor/libs/@algolia/autocomplete-js.js'])

@if ($configData['hasCustomizer'])
  @vite('resources/assets/vendor/libs/pickr/pickr.js')
@endif

@vite(['resources/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js', 'resources/assets/vendor/libs/hammer/hammer.js', 'resources/assets/vendor/js/menu.js'])

@yield('vendor-script')
<!-- END: Page Vendor JS-->

<!-- BEGIN: Theme JS-->
@vite(['resources/assets/js/main.js'])
<!-- END: Theme JS-->

<!-- Pricing Modal JS-->
@stack('pricing-script')
<!-- END: Pricing Modal JS-->

<!-- BEGIN: Page JS-->
@yield('page-script')
<!-- END: Page JS-->

<!-- app JS -->
<!-- END: app JS-->

<!-- Global Flash Messages (Bootstrap Toasts) -->
@if (session()->has('success') || session()->has('status') || session()->has('error') || session()->has('warning') || session()->has('info') || $errors->any())
  <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;">
    {{-- Success Toast --}}
    @if (session()->has('success') || session()->has('status'))
    <div id="successToast" class="toast align-items-center text-white bg-success border-0 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="d-flex">
        <div class="toast-body d-flex align-items-center">
          <i class="ri-checkbox-circle-line me-2 fs-5"></i>
          <span>{!! session('success') ?? session('status') !!}</span>
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
    </div>
    @endif

    {{-- Error Toast --}}
    @if (session()->has('error') || $errors->any())
    <div id="errorToast" class="toast align-items-center text-white bg-danger border-0 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="d-flex">
        <div class="toast-body d-flex align-items-center">
          <i class="ri-error-warning-line me-2 fs-5"></i>
          <span>{!! session('error') ?? 'Terjadi kesalahan. Silakan cek kembali isian Anda.' !!}</span>
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
    </div>
    @endif

    {{-- Info/Warning Toast --}}
    @if (session()->has('warning') || session()->has('info'))
    <div id="infoToast" class="toast align-items-center text-white bg-warning border-0 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="d-flex">
        <div class="toast-body d-flex align-items-center">
          <i class="ri-information-line me-2 fs-5"></i>
          <span>{!! session('warning') ?? session('info') !!}</span>
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
    </div>
    @endif
  </div>

  <script type="module">
    document.addEventListener('DOMContentLoaded', function() {
      const toastElList = [].slice.call(document.querySelectorAll('.toast'));
      const toastList = toastElList.map(function(toastEl) {
        return new bootstrap.Toast(toastEl, { delay: 4000 });
      });
      toastList.forEach(toast => toast.show());
    });
  </script>

  <style>
    .toast {
      min-width: 300px;
      max-width: 400px;
      border-radius: 0.6rem !important;
      border: none !important;
      animation: toastSlideIn 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    }
    .toast-body {
      padding: 0.85rem 1.1rem;
      font-weight: 500;
      font-size: 0.95rem;
    }
    .bg-success { background-color: #28c76f !important; box-shadow: 0 4px 12px rgba(40, 199, 111, 0.35) !important; }
    .bg-danger { background-color: #ea5455 !important; box-shadow: 0 4px 12px rgba(234, 84, 85, 0.35) !important; }
    .bg-warning { background-color: #ff9f43 !important; box-shadow: 0 4px 12px rgba(255, 159, 67, 0.35) !important; }

    @keyframes toastSlideIn {
      from {
        transform: translateX(110%);
        opacity: 0;
      }
      to {
        transform: translateX(0);
        opacity: 1;
      }
    }
  </style>
@endif
