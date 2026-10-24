const fs = require('node:fs/promises');
const CoffeeScript = require('coffeescript');
const Jasmine = require('jasmine');
const { JSDOM } = require('jsdom');
const build = require('./build.cjs');

async function test() {
  await build();
  const dom = new JSDOM('<!doctype html><html><body></body></html>', { runScripts: 'outside-only', url: 'http://localhost/' });
  const jquery = require('jquery')(dom.window);
  dom.window.$ = dom.window.jQuery = jquery;
  const jasmine = new Jasmine();
  jasmine.exitOnCompletion = false;
  jasmine.addReporter({ specDone(result) { for (const failure of result.failedExpectations) console.error(result.fullName, failure.message); } });
  for (const name of ['describe', 'it', 'beforeEach', 'afterEach', 'expect', 'spyOn', 'jasmine']) dom.window[name] = global[name];
  dom.window.eval(await fs.readFile('dist/js/bootstrap-tags.js', 'utf8'));
  dom.window.document.dispatchEvent(new dom.window.Event('DOMContentLoaded'));
  await new Promise(resolve => jquery(resolve));
  const specs = await fs.readdir('spec');
  for (const file of specs.filter(file => file.endsWith('.coffee')).sort()) {
    dom.window.eval(CoffeeScript.compile(await fs.readFile(`spec/${file}`, 'utf8'), { bare: true }));
  }
  const result = await jasmine.execute();
  dom.window.close();
  if (result.overallStatus !== 'passed') process.exitCode = 1;
}

test().catch(error => { console.error(error); process.exitCode = 1; });
