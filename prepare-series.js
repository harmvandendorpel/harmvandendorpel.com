'use strict';

const fs = require('fs')

const rawdata = fs.readFileSync('_/work.json')
const content = JSON.parse(rawdata)

const series = {}
content.content.forEach((item) => {
  if (!('parts' in item)) return
  item.parts.forEach(part => {
    if (!('series' in part)) return
    const seriesName = part.series
    if (seriesName in series) {
      series[seriesName].push(part)
    } else {
      series[seriesName] = [part]
    }
  })
})

console.log(series)

content.content = content.content.map((item) => {
  if (!('series' in item)) return item
  
  const parts = item.parts || []

  return {
    ...item,
    parts: [
      ...parts,
      { thumbs: series[item.series] }
    ]
  }
})

const stringData = JSON.stringify(content)
fs.writeFileSync('_/work-output.json', stringData)
