/**
 * The build is run with laravel-mix, see docs here: https://laravel-mix.com
 */
/* eslint-disable import/no-extraneous-dependencies */
const mix = require("laravel-mix");
const webpack = require("webpack");
const fs = require("fs");
const path = require("path");

// parse environment variables from .env
require("dotenv").config();

// define paths
const srcFolder = `./src/frontend`;
// const distFolder = `./app/dist`;
const publicFolder = `./public/app/dist`;

// compile
mix
  .js(`${srcFolder}/src/app.js`, "/")
  .vue({ version: 3 });

// mix.copyDirectory(`${srcFolder}/images`, `${distFolder}/images`);
mix.setResourceRoot(publicFolder); // Prefixes urls

/**
 * Setup vue correctly
 */
mix.webpackConfig({
  plugins: [
    new webpack.DefinePlugin({
      __VUE_OPTIONS_API__: true,
      __VUE_PROD_DEVTOOLS__: false,
    }),
  ],
});

/**
 * Development specific
 */
if (process.env.NODE_ENV === "development") {
  // Add style lint
  // eslint-disable-next-line global-require
  const StyleLintPlugin = require("stylelint-webpack-plugin");
  mix.webpackConfig({
    plugins: [
      new StyleLintPlugin({
        context: srcFolder,
        files: ["**/*.{scss,vue}"],
      }),
    ],
  });

  // Add eslint
  mix.webpackConfig({
    module: {
      rules: [
        {
          enforce: "pre",
          test: /\.(js|vue)$/,
          exclude: /node_modules/,
          loader: "eslint-loader",
        },
      ],
    }
  });
}
