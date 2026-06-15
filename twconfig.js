tailwind.config = {
    theme: {
        extend: {
            fontFamily: {
                sans: ['Sora', 'sans-serif'], // Padrão para corpo e subtítulos
                serif: ['Lora', 'serif'],           // Para Títulos
                display: ['Sora', 'serif'],       // Para Cabeçalho (Menu)
            },
            colors: {
                brand: {
                    'dark-green': '#1d645b',    /* Verde escuro */
                    // 'light-brown': '#cc7722',   /* Marrom claro */
                    'light-brown': '#2FB17C',   /* Marrom claro */
                    // 'coffee': '#4e3924',        /* Marrom escuro (Café) */
                    'coffee': '#072723',        /* Marrom escuro (Café) */
                    // 'light-pink': '#ede2e4',    /* Rosa Claro */
                    'light-pink': '#E9ECEA',    /* Rosa Claro */
                    'light-green': '#7ca899',   /* Verde claro */
                    // 'moss': '#647256',          /* Verde musgo */
                    'moss': '#2FB17C',          /* Verde musgo */
                    'ice': '#f4f4f4',           /* Branco gelo */
                    'grayish': '#6a7771',       /* Verde acinzentado */
                    'red': '#C0392B',

                    'primary': '#E9ECEA',   // Cor Primária
                    'secondary': '#2FB17C', // Cor Secundária
                    'tertiary': '#072723',  // Cor Terciária
                    'neutral': '#787776',   // Cor Neutra

                    // light-brown -> secondary
                    // moss -> secondary
                    // coffee -> tertiary
                    // light-pink -> primary


                }
            },
            animation: {
                'spin-slow': 'spin 3s linear infinite',
                'fade-in': 'fadeIn 0.5s ease-out forwards',
            },
            keyframes: {
                fadeIn: {
                    '0%': { opacity: '0', transform: 'translateY(10px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                }
            }
        }
    }
}