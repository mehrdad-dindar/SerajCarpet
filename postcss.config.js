export default {
    plugins: {
        'tailwindcss/nesting': 'postcss-nesting',
        tailwindcss: {
            config: './tailwind.client.config.js',
        },
        autoprefixer: {},
    },
};
