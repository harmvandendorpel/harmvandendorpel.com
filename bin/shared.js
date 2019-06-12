const FILENAME = './_/work.json'

const fs = require('fs')

function readItems() {
  return JSON.parse(fs.readFileSync(FILENAME, 'utf8'))
}

function saveItems(result) {
  const JSONString = JSON.stringify(result, null, 2)
  fs.writeFileSync(FILENAME, JSONString)
}

function makePermalink(str) {
  var re = /[^a-z0-9]+/gi; // global and case insensitive matching of non-char/non-numeric
  var re2 = /^-*|-*$/g;     // get rid of any leading/trailing dashes
  str = str.replace(re, '-');  // perform the 1st regexp
  return str.replace(re2, '').toLowerCase(); // ..aaand the second + return lowercased result
}

function exists(key, array) {
  return key in array && array[key] !== null
}

function missing(key, array) { return !exists(key, array) }

function now() {
  return new Date().toJSON()
}

module.exports = {
  readItems,
  saveItems,
  makePermalink,
  missing,
  exists,
  now
}
