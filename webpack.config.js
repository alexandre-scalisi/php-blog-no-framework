const path = require("path");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");

module.exports = {
  mode: "development",
  context: path.resolve(__dirname, "src"),
  entry: ["./js/index.js", "./css/app.scss"],
  output: {
    filename: "bundle.js",
    path: path.resolve(__dirname, "public/assets"),
  },
  plugins: [
    new MiniCssExtractPlugin({
      filename: "./[name].css",
      chunkFilename: "./[id].css",
    }),
  ],
  module: {
    rules: [
      {
        test: /\.s[ac]ss$/i,
        use: [
          { loader: MiniCssExtractPlugin.loader },
          { loader: "css-loader" },
          {
            // Loads a SASS/SCSS file and compiles it to CSS
            loader: "sass-loader",
            options: {
              sassOptions: {
                style: "compressed",
              },
            },
          },
        ],
      },
    ],
  },
};
