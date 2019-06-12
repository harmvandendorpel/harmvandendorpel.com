const FILENAME = './_/work.json'

main()

function main() {
  const fs = require('fs')
  const work = JSON.parse(fs.readFileSync(FILENAME, 'utf8'))

  const result = {
    content: work.content.map(processItem)
  }

  console.log(`processed ${result.content.length} items`)
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

function processItem(item) {
  const newItem = {
    ...item,
  }

  if (missing('date',newItem)) {
    newItem.date = new Date().toJSON()
  } else {
    newItem.date = new Date(newItem.date).toJSON()
  }

  if (missing('pubDate', newItem)) {
    newItem.pubDate = new Date().toJSON()
  }

  if (missing('perma',newItem) && exists('title', newItem)) {
    newItem.perma = makePermalink(newItem.title)
    console.log(`- create permalink for ${newItem.title}`)
  }
  
  return newItem
}
