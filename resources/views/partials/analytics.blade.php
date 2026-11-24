@if (filled($seo->ga4MeasurementId))
    <div id="sospet-consent"
        class="fixed inset-x-3 bottom-24 z-[60] hidden rounded-2xl border border-border-custom bg-bg-main p-4 shadow-xl md:inset-x-auto md:end-6 md:bottom-6 md:max-w-sm">
        <p class="text-xs leading-6 text-text-body">
            برای بهبود ساس‌پت، آمار بازدید را به‌صورت ناشناس اندازه‌گیری می‌کنیم. می‌توانید انتخاب کنید.
        </p>
        <div class="mt-3 flex gap-2">
            <button type="button" data-analytics-consent="granted"
                class="rounded-lg bg-primary px-3 py-2 text-[11px] font-bold text-white transition hover:bg-primary-hover">موافقم</button>
            <button type="button" data-analytics-consent="denied"
                class="rounded-lg border border-border-custom px-3 py-2 text-[11px] font-bold text-text-body transition hover:bg-bg-secondary">نیاز
                نیست</button>
        </div>
    </div>

    <script>
        (function() {
            var measurementId = @json($seo->ga4MeasurementId);
            var storageKey = 'sospet_analytics_consent';

            function loadAnalytics() {
                if (window.sospetAnalyticsLoaded) return;
                window.sospetAnalyticsLoaded = true;

                var script = document.createElement('script');
                script.async = true;
                script.src = 'https://www.googletagmanager.com/gtag/js?id=' + encodeURIComponent(measurementId);
                document.head.appendChild(script);

                window.dataLayer = window.dataLayer || [];
                window.gtag = function() { window.dataLayer.push(arguments); };
                window.gtag('js', new Date());
                window.gtag('config', measurementId, { anonymize_ip: true });
            }

            var stored = localStorage.getItem(storageKey);

            if (stored === 'granted') {
                loadAnalytics();
            } else if (stored === null) {
                var banner = document.getElementById('sospet-consent');
                if (banner) banner.classList.remove('hidden');
            }

            document.addEventListener('click', function(event) {
                var button = event.target.closest('[data-analytics-consent]');
                if (!button) return;

                var value = button.getAttribute('data-analytics-consent');
                localStorage.setItem(storageKey, value);

                var banner = document.getElementById('sospet-consent');
                if (banner) banner.remove();

                if (value === 'granted') loadAnalytics();
            });
        })();
    </script>
@endif
