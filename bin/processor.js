const {
  saveItems,
  readItems,
  missing,
  exists
} = require('./shared')

main()

function main() {
  const items = readItems()

  const result = {
    ...items,
    content: items.content.map(processItem)
  }

  console.log(`processed ${result.content.length} items`)
  saveItems(result)
}

function processItem(item) {
  const result = {
    ...item,
  }

  if (missing('date', result)) {
    result.date = new Date().toJSON()
  } else {
    result.date = new Date(result.date).toJSON()
  }

  if (missing('pubDate', result)) {
    result.pubDate = new Date().toJSON()
  }

  if (missing('perma', result) && exists('title', result)) {
    result.perma = makePermalink(result.title)
    console.log(`- create permalink for ${result.title}`)
  }
  
  return result
}
