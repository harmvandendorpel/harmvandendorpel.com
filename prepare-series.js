'use strict';

const fs = require('fs')

const rawdata = fs.readFileSync('_/work.json')
const content = JSON.parse(rawdata)

const result = content.content.reduce((acc, item) => {
  if ('tags' in item) {
    const tags = item.tags.split(',')
    tags.forEach(tag => acc.push(tag.trim()))    
  }

  if ('cat' in item) {
    const cats = item.cat.split(',')
    cats.forEach(cat => acc.push(cat.trim()))
  }
  return acc
}, [])

const distinct = result.reduce((acc, current) => {
  if (current === '') return acc
  if (current in acc) {
    acc[current].count++
  } else {
    acc[current] = {
      tag: current,
      count: 1
    }
  }

  return acc
}, {})

const array = Object.keys(distinct).map(tag => distinct[tag]).sort((a, b) => b.count - a.count)

console.log(array)

fs.writeFileSync('_/tags.json', JSON.stringify(array))
