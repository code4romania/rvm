const colors = require('tailwindcss/colors')

module.exports = {
    content: [
        'app/**/*.php',
        './resources/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],
    darkMode: 'class',
    theme: {
        extend: {
            colors: {
                danger: colors.rose,
                primary: {
                    50: '#EFF2FB',
                    100: '#CED9F3',
                    200: '#9DB3E7',
                    300: '#3B67CE',
                    400: '#264998',
                    500: '#213E83',
                    600: '#182F62',
                    700: '#101F41',
                    800: '#0C1731',
                    900: '#040810',
                },
                success: colors.green,
                warning: colors.amber,
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
    ],
}
