const path = require('path')
const fs = require('fs')
const packageJson = fs.readFileSync('./package.json')
const version = JSON.parse(packageJson).version || 0
process.env.VUE_APP_VERSION = version

const ContextReplacementPlugin = require('webpack').ContextReplacementPlugin

module.exports = {
  transpileDependencies: ['vuetify'],
  lintOnSave: true,

  publicPath: '/menumaker',

  configureWebpack: {
    plugins: [new ContextReplacementPlugin(/moment[/\\]locale$/, /ru|uz|en/)],
    devtool: 'source-map'
  },

  chainWebpack: config => {
    config.resolve.alias.set('@', path.resolve(__dirname, 'src'))
    config.resolve.alias.set('@components', path.resolve(__dirname, 'src/components'))
    config.resolve.alias.set('@mixins', path.resolve(__dirname, 'src/mixins'))
    config.resolve.alias.set('@api', path.resolve(__dirname, 'src/api'))
    config.resolve.alias.set('@common', path.resolve(__dirname, 'src/common'))
    config.resolve.alias.set('@public', path.resolve(__dirname, 'public/'))
    config.resolve.alias.set('@views', path.resolve(__dirname, 'src/views/'))

    config.plugins.delete('prefetch')
    config.plugins.delete('preload')
  }
}
