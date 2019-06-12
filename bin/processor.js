const FILENAME = './_/work.json'
<<<<<<< HEAD

main()

function main() {
  const fs = require('fs')
  const work = JSON.parse(fs.readFileSync(FILENAME, 'utf8'))

  const result = {
    content: work.content.map(processItem)
  }

  console.log(result.content)
  const JSONString = JSON.stringify(result, null, 2)
  fs.writeFileSync(FILENAME, JSONString)
}

function processItem(item) {
  const newItem = {
    ...item,
  }

  if (!('date' in newItem)) {
    newItem.date = new Date().toJSON()
  } else {
    newItem.date = new Date(newItem.date).toJSON()
  }

  if (!('pubDate' in newItem)) {
    newItem.pubDate = new Date().toJSON()
  }
  
  return newItem
}
=======
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
>>>>>>> b18403d14431c02ac6889c6295e8413a3b6b9fce
