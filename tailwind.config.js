import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";
import typography from "@tailwindcss/typography";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./vendor/laravel/jetstream/**/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.vue",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['"pt-sans-caption-regular"', "sans-serif"],
                comic: ['"Comic Neue"', "sans-serif"],
                fraktur: ['"Moderne Fraktur"', "cursive"],
            },
            typography: (theme) => ({
                DEFAULT: {
                    css: {
                        fontFamily: '"pt-sans-caption-regular", sans-serif',
                        fontSize: "1.25rem",
                        textAlign: "justify",
                        lineHeight: "1.75rem",
                        color: theme("colors.gray.800"),
                        p: {
                            marginTop: "0.5em",
                            marginBottom: "0.5em",
                        },
                        h1: {
                            fontSize: "2rem",
                            fontWeight: "700",
                            marginTop: "1em",
                            marginBottom: "0.5em",
                        },
                        h2: {
                            fontSize: "1.75rem",
                            fontWeight: "700",
                            marginTop: "1em",
                            marginBottom: "0.5em",
                        },
                        h3: {
                            fontSize: "1.5rem",
                            fontWeight: "600",
                            marginTop: "0.75em",
                            marginBottom: "0.5em",
                        },
                        ul: {
                            paddingLeft: "1.5em",
                            listStyleType: "disc",
                        },
                        ol: {
                            paddingLeft: "1.5em",
                            listStyleType: "decimal",
                        },
                        li: {
                            marginTop: "0.25em",
                            marginBottom: "0.25em",
                        },
                    },
                },
            }),
        },
    },

    plugins: [forms, typography],
};
