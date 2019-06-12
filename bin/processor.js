const FILENAME = './_/work.json'
const fs = require('fs')
const work = JSON.parse(fs.readFileSync(FILENAME, 'utf8'))

const result = {
  content: work.content.map(item => {
    const newItem = {
      ...item,
      date: new Date(item.date)
    }

    if ('pubDate' in newItem) {
      newItem.pubDate = new Date(newItem.pubDate)
    } else {
      newItem.pubDate = newItem.date
    }
    
    return newItem
  })
}

const JSONString = JSON.stringify(result, null, 2)
fs.writeFileSync(FILENAME, JSONString)
