const fs = require('fs');
const path = require('path');

const renames = {
  'Jack Russel.avif': 'jack-russel.avif',
  'Dog Illustration.avif': 'dog-illustration.avif',
  'Untitled (1).avif': 'animal-1.avif',
  'Untitled (2).avif': 'animal-2.avif',
  'Untitled (3).avif': 'animal-3.avif',
  'Untitled (4).avif': 'animal-4.avif',
  'Untitled (5).avif': 'animal-5.avif',
  'Untitled (6).avif': 'animal-6.avif',
};

Object.entries(renames).forEach(([oldName, newName]) => {
  if (fs.existsSync(oldName)) {
    fs.renameSync(oldName, newName);
    console.log(`✓ ${oldName} → ${newName}`);
  } else {
    console.log(`✗ Not found: ${oldName}`);
  }
});

console.log('\nDone! Now commit and push.');
