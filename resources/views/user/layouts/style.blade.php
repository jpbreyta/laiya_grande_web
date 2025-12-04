    <style>
        :root {
            --font-body: 'Poppins', ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Arial, 'Noto Sans', 'Helvetica Neue', sans-serif;
        }

        :root {
            --font-heading: 'Playfair Display', ui-serif, Georgia, Cambria, "Times New Roman", Times, serif;
        }

        :root {
            --font-ui: 'Roboto', ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Arial, 'Noto Sans', 'Helvetica Neue', sans-serif;
        }

        html,
        body {
            font-family: var(--font-body);
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        .font-heading {
            font-family: var(--font-heading);
        }

        input,
        select,
        textarea,
        button {
            font-family: var(--font-ui);
        }

        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slide-up {
            from {
                opacity: 0;
                transform: translateY(40px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fade-in 0.8s ease-out forwards;
            opacity: 0;
        }

        .animate-slide-up {
            animation: slide-up 1s ease-out forwards;
            opacity: 0;
        }

        html {
            scroll-behavior: smooth;
        }

        .group:hover .group-hover\:translate-x-1 {
            transform: translateX(0.25rem);
        }

        .group:hover .group-hover\:translate-x-1 {
            transform: translateX(0.25rem);
        }
    </style>