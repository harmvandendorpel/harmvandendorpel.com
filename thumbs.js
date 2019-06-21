const fs = require('fs');
const path = require('path');
const shell = require('shelljs');
const sharp = require('sharp');
const allFiles = []

function traverse(directoryName) {
  const files = fs.readdirSync(directoryName)

  files.forEach(function(file) {
    var fullPath = path.join(directoryName,file);
    const f = fs.statSync(fullPath);
    if (f.isDirectory()) {
      traverse(fullPath);
    } else {
      allFiles.push(fullPath);
    }
  });
};

const THUMBS = './_/thumb/'

traverse('./_/img');


allFiles.forEach((f) => {
  const parts = f.split('/')
  const filename = parts.pop()
  
  let targetFilename = null

  // console.log(parts.length, f)
  if (parts.length == 3) {
    const folder = parts.pop()
    const targetFolder = `${THUMBS}${folder}`
    if (!fs.existsSync(targetFolder)) {
      shell.mkdir('-p', targetFolder);
      console.log('create folder', targetFolder)
    }
    targetFilename = `${targetFolder}/${filename}`;
  } else if (parts.length == 2){
    targetFilename = `${THUMBS}${filename}`;
  } else {
    console.log('Do not know what to do with', f)
    return
  }

  if (!fs.existsSync(targetFilename)) {
    sharp(f)
      .resize(500)
      .toFile(targetFilename, function(err) {
        if(err) {
          console.error('could not create thumbnail for', targetFilename)
        } else {
          console.log('Created thumbnail for', targetFilename)
        }
      });
  }
})
