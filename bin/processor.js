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
