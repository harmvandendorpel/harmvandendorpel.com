'use strict';

const fs = require('fs')

const rawdata = fs.readFileSync('_/work.json')
const content = JSON.parse(rawdata)

const series = {}

const result = {
  index: content.index,
  content: content.content.map((item) => {
    if (!('meta_image' in item)) {
      // item.meta_image = 'test.jpg'
      if ('parts' in item) {
        if (item.parts.length) {
          if ('content' in item.parts[0]) {
            item.meta_image = item.parts[0].content[0].filename

          }
        }
      }
    }
    return item
  })
}

const stringData = JSON.stringify(result)

fs.writeFileSync('_/work-output.json', stringData)
