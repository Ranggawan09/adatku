/** @type {import('tailwindcss').Config} */
const flowbitePlugin = require("flowbite/plugin");
const formsPlugin = require("@tailwindcss/forms");
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "./node_modules/flowbite/**/*.js",
    ],
    theme: {
        extend: {
            fontFamily: {
                car: ["Figtree"],
            },
            colors: {
                pr: {
                    50: "#fdf8f6",
                    100: "#f2e8e5",
                    200: "#eaddd7",
                    300: "#e0cec7",
                    400: "#c9a899", // Warna utama yang lebih terang
                    500: "#b08471", // Warna utama
                    600: "#a17260", // Warna utama yang lebih gelap
                    700: "#875f51", // Warna hover/aktif
                    800: "#6e4f45",
                    900: "#5a423a",
                    950: "#31211d",
                },
                sec: {
                    100: "#fafafa",
                    200: "#ffd799",
                    300: "#f7f7f8",
                    400: "#f5f5f6",
                    500: "#dddddd",
                    600: "#eeeeee",
                    700: "#c4c4c4",
                    800: "#acacac",
                    900: "#acacac",
                },
                jawa: {
                    50: "#fdf8f6",
                    100: "#f2e8e5",
                    200: "#eaddd7",
                    300: "#e0cec7",
                    400: "#c9a899", // Warna utama yang lebih terang
                    500: "#b08471", // Warna utama
                    600: "#a17260", // Warna utama yang lebih gelap
                    700: "#875f51", // Warna hover/aktif
                    800: "#6e4f45",
                    900: "#5a423a",
                    950: "#31211d",
                },
            },
        },
    },
    plugins: [flowbitePlugin, formsPlugin],
};
