<!-- Vendor -->
<script src="{{ static_asset('assets/front/vendor/plugins/js/plugins.min.js') }}"></script>

<!-- Theme Base, Components and Settings -->
<script src="{{ static_asset('assets/front/js/theme.js') }}"></script>

<!-- Current Page Vendor and Views -->
<script src="{{ static_asset('assets/front/js/views/view.contact.js') }}"></script>

<!-- Theme Custom -->
<script src="{{ static_asset('assets/front/js/custom.js') }}"></script>

<!-- Theme Initialization Files -->
<script src="{{ static_asset('assets/front/js/theme.init.js') }}"></script>

<script>
    $(document).ready(function() {
        i18next
            .use(i18nextHttpBackend)
            .use(i18nextBrowserLanguageDetector)
            .init({
                debug: true,
                fallbackLng: 'en',
                backend: {
                    loadPath: '/locales/{{lng}}/translation.json'
                }
            }, function(err, t) {
                updateContent();
            });

        function updateContent() {
            $('[data-i18n]').each(function() {
                var key = $(this).attr('data-i18n');
                $(this).html(i18next.t(key));
            });
        }

        $('.change-lang').click(function() {
            var selectedLang = $(this).attr('data-lang');
            i18next.changeLanguage(selectedLang, function() {
                updateContent();
            });
        });
    });
</script>