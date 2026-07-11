<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>Pawet — برای هر حیوونی، یه خونه</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@400;500;600;700;800&family=Lalezar&family=Poppins:wght@700;800&display=swap"
        rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        sand: {
                            DEFAULT: '#FBF5EC',
                            100: '#F5E9D6'
                        },
                        ink: {
                            DEFAULT: '#2A1D14',
                            soft: '#7A6A59'
                        },
                        saffron: {
                            50: '#FFF7EC',
                            100: '#FEE9CC',
                            200: '#FBCE94',
                            300: '#F6AD5C',
                            400: '#EE832F',
                            500: '#E2622A',
                            600: '#C24D1C',
                            700: '#993D17'
                        },
                        turmeric: {
                            100: '#FDEFC9',
                            300: '#F8CB6B',
                            500: '#EFA13A'
                        },
                        pistachio: {
                            light: '#E8EFDC',
                            DEFAULT: '#7E9A63',
                            dark: '#5C7448'
                        },
                        coral: {
                            light: '#FBE6E2',
                            DEFAULT: '#D8503F',
                            dark: '#AC3B2D'
                        },
                    },
                    fontFamily: {
                        display: ['Lalezar', 'Vazirmatn', 'sans-serif'],
                        body: ['Vazirmatn', 'sans-serif'],
                        brand: ['Poppins', 'sans-serif'],
                    },
                    boxShadow: {
                        tag: '0 18px 30px -12px rgba(194, 77, 28, 0.35)',
                    }
                }
            }
        }
    </script>

    <style>
        [x-cloak] {
            display: none !important;
        }

        html {
            font-family: 'Vazirmatn', sans-serif;
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        *:focus-visible {
            outline: 2px solid #E2622A;
            outline-offset: 2px;
            border-radius: 4px;
        }

        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.01ms !important;
                transition-duration: 0.01ms !important;
            }
        }

        /* the grommet hole on the signature "pet-tag" search bar */
        .tag-hole {
            position: absolute;
            inset-inline-start: 1.5rem;
            top: -0.55rem;
            width: 1.1rem;
            height: 1.1rem;
            border-radius: 9999px;
            background: #FBF5EC;
            box-shadow: inset 0 0 0 2.5px rgba(194, 77, 28, 0.45);
        }
    </style>
</head>

<body class="bg-zinc-50 min-h-screen" dir="rtl">

    <div class="max-w-md mx-auto">

        <!-- Hero -->
        <section class="px-4 pt-6">

            <div class="rounded-3xl bg-gradient-to-br from-orange-500 to-orange-400 p-6 text-white">

                <h1 class="text-2xl font-black leading-relaxed">
                    پیدا کردن خانه‌ای امن برای دوستان پشمالو 🐾
                </h1>

                <p class="mt-3 text-orange-50">
                    سرپرستی، نگهداری موقت یا پیدا کردن حیوانات گمشده؛
                    همه در یک مکان.
                </p>

                <div class="flex gap-2 mt-5">
                    <button class="bg-white text-orange-600 px-4 py-3 rounded-2xl font-bold flex-1">
                        مشاهده حیوانات
                    </button>

                    <button class="bg-white/20 px-4 py-3 rounded-2xl flex-1">
                        ثبت آگهی
                    </button>
                </div>

            </div>
        </section>

        <!-- Actions -->
        <section class="p-4 space-y-3">

            <div class="bg-white rounded-3xl p-4 shadow-sm flex items-center justify-between">

                <div>
                    <h3 class="font-bold">
                        سرپرستی حیوانات
                    </h3>

                    <p class="text-sm text-gray-500">
                        پیدا کردن حیوان خانگی جدید
                    </p>
                </div>

                <span class="text-4xl">🐶</span>

            </div>

            <div class="bg-white rounded-3xl p-4 shadow-sm flex items-center justify-between">

                <div>
                    <h3 class="font-bold">
                        نگهداری موقت
                    </h3>

                    <p class="text-sm text-gray-500">
                        واگذاری یا پذیرش حیوان
                    </p>
                </div>

                <span class="text-4xl">🏠</span>

            </div>

            <div class="bg-white rounded-3xl p-4 shadow-sm flex items-center justify-between">

                <div>
                    <h3 class="font-bold">
                        گمشده و پیدا شده
                    </h3>

                    <p class="text-sm text-gray-500">
                        ثبت حیوان گمشده
                    </p>
                </div>

                <span class="text-4xl">🚨</span>

            </div>

        </section>

    </div>

</body>
