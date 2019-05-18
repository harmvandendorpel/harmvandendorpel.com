const fps = 50
let frame = 0
const ticksPerCharacter = 4
const wait = 100

const s =
  window.subtitles.reduce((acc, subtitle) => {
    let frame = acc.length > 0 ? (acc[acc.length - 1].text.length * ticksPerCharacter) << 0: 0

    if (acc.length > 0) {
      const previousText = acc[acc.length - 1].text.trim()
      if (previousText.charAt(previousText.length - 1) === ".") {
        console.log(subtitle.text)
        frame += wait
      }
    }

    acc.push({
      text: subtitle.text,
      frame,
      duration: (subtitle.text.length * ticksPerCharacter) << 0
    })
  return acc
}, [])

const cumulative = s.reduce((acc, subtitle) => {
  const frameOn = acc.length > 0 ? acc[acc.length - 1].frameOn + subtitle.frame : 0
  acc.push({
    text: subtitle.text,
    frameOff: subtitle.duration + frameOn,
    frameOn
  })
  return acc
}, [])

console.log(cumulative)
function heartbeat(animId, frame) {
  const subtitleOn = cumulative.find(item => item.frameOn === frame)
  const subtitleOff = cumulative.find(item => item.frameOff === frame)
  const div = document.getElementById("text")
  if (subtitleOn) {
    div.style.display = "inline"
    div.innerHTML = subtitleOn.text
  } else if (subtitleOff) {
    div.innerHTML = ""
    div.style.display = "none"
  }
}

function pulse() {
  heartbeat("a", frame)
  frame++
}

setInterval(pulse, 1000 / fps)
