const fs = require('node:fs/promises');
const CoffeeScript = require('coffeescript');
const { minify } = require('terser');
const sass = require('sass');

async function build() {
  const files = (await fs.readdir('src', { recursive: true })).filter(file => file.endsWith('.coffee')).sort();
  const source = (await Promise.all(files.map(file => fs.readFile(`src/${file}`, 'utf8')))).join('\n\n');
  const compiled = CoffeeScript.compile(source, { bare: true });
  const wrapped = `(function($) {\n${compiled}\n})(window.jQuery);`;
  const banner = '/*! bootstrap-tags 1.1.5 | Copyright 2013 Max Lahey | MIT */';
  await fs.mkdir('dist/js', { recursive: true });
  await fs.mkdir('dist/css', { recursive: true });
  const readable = await minify(wrapped, { compress: false, mangle: false, format: { beautify: true, preamble: banner } });
  const compressed = await minify(wrapped, { format: { preamble: banner } });
  await fs.writeFile('dist/js/bootstrap-tags.js', readable.code + '\n');
  await fs.writeFile('dist/js/bootstrap-tags.min.js', compressed.code + '\n');
  await fs.writeFile('dist/css/bootstrap-tags.css', sass.compile('sass/bootstrap-tags.scss').css);
}

module.exports = build;
if (require.main === module) build().catch(error => { console.error(error); process.exitCode = 1; });
