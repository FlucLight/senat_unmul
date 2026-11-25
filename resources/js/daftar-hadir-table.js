function getPesertaTable(root = document) {
  return root.querySelector('table[data-table="peserta"]');
}

function detectVariant(table) {
  const headerCells = table?.querySelectorAll('thead th') || [];
  if (headerCells.length === 5 && (headerCells[4]?.textContent || '').toLowerCase().includes('paraf')) return 'satu_kolom';
  if (headerCells.length === 4) return 'blank';
  return 'zigzag';
}

function renumber(table) {
  if (!table) return;
  const variant = detectVariant(table);
  const rows = Array.from(table.querySelectorAll('tbody tr'));
  rows.forEach((row, idx) => {
    const n = idx + 1;
    const cells = row.querySelectorAll('td');
    if (cells[0]) cells[0].textContent = String(n);
    if (variant === 'zigzag') {
      if (cells[3]) cells[3].textContent = n % 2 === 1 ? `${n}.` : '';
      if (cells[4]) cells[4].textContent = n % 2 === 0 ? `${n}.` : '';
    }
  });
}

function makeCell(text = '', style = '') {
  const td = document.createElement('td');
  if (style) td.setAttribute('style', style);
  td.textContent = text;
  return td;
}

function createRow(table, data = {}) {
  const variant = detectVariant(table);
  const tr = document.createElement('tr');
  if (variant === 'zigzag') {
    tr.appendChild(makeCell('', 'text-align:center;'));
    tr.appendChild(makeCell(data.nama || ''));
    tr.appendChild(makeCell(data.jabatan_instansi || ''));
    tr.appendChild(makeCell('', 'width:90px;'));
    tr.appendChild(makeCell('', 'width:90px;'));
  } else if (variant === 'satu_kolom') {
    tr.appendChild(makeCell('', 'text-align:center;'));
    tr.appendChild(makeCell(data.nama || ''));
    tr.appendChild(makeCell(data.identitas || ''));
    tr.appendChild(makeCell(data.jabatan_instansi || ''));
    tr.appendChild(makeCell(''));
  } else {
    tr.appendChild(makeCell('', 'text-align:center;'));
    tr.appendChild(makeCell(data.nama || ''));
    tr.appendChild(makeCell(data.jabatan_instansi || ''));
    tr.appendChild(makeCell(''));
  }
  return tr;
}

function addRows(table, count = 1, dataList = []) {
  if (!table) return 0;
  const tbody = table.querySelector('tbody');
  if (!tbody) return 0;
  let added = 0;
  for (let i = 0; i < count; i++) {
    const data = dataList[i] || {};
    tbody.appendChild(createRow(table, data));
    added++;
  }
  renumber(table);
  return added;
}

function parseNameLines(text) {
  return String(text || '').split(/\r?\n/).map((l) => l.trim()).filter(Boolean).map((line) => {
    let cleaned = line.replace(/^\s*(?:\d+[\.\)\:\-\]]+|[\-\*•])\s*/, '').trim();
    let nama = cleaned; let jabatan_instansi = '';
    if (cleaned.includes('\t')) { const p = cleaned.split('\t'); nama = p[0].trim(); jabatan_instansi = p.slice(1).join(' ').trim(); }
    else if (cleaned.includes('|')) { const p = cleaned.split('|'); nama = p[0].trim(); jabatan_instansi = p.slice(1).join(' ').trim(); }
    else if (cleaned.includes(';')) { const p = cleaned.split(';'); nama = p[0].trim(); jabatan_instansi = p.slice(1).join(' ').trim(); }
    else if (/\s+-\s+/.test(cleaned)) { const p = cleaned.split(/\s+-\s+/); nama = p[0].trim(); jabatan_instansi = p.slice(1).join(' ').trim(); }
    return { nama, jabatan_instansi };
  }).filter((p) => p.nama);
}

function isRowEmpty(row) {
  const cells = Array.from(row.querySelectorAll('td'));
  return cells.slice(1).every((c) => (c.textContent || '').trim() === '');
}

function pasteNames(table, text) {
  if (!table) return { filled: 0, added: 0 };
  const items = parseNameLines(text);
  if (!items.length) return { filled: 0, added: 0 };
  const tbody = table.querySelector('tbody');
  const rows = Array.from(tbody.querySelectorAll('tr'));
  const variant = detectVariant(table);
  let filled = 0; let added = 0;
  for (const item of items) {
    let target = rows.find((r) => isRowEmpty(r));
    if (!target) { target = createRow(table, {}); tbody.appendChild(target); rows.push(target); added++; }
    const cells = target.querySelectorAll('td');
    if (cells[1]) cells[1].textContent = item.nama;
    if (variant === 'satu_kolom') { if (cells[3]) cells[3].textContent = item.jabatan_instansi || ''; }
    else if (variant === 'blank') { if (cells[2]) cells[2].textContent = item.jabatan_instansi || ''; }
    else { if (cells[2]) cells[2].textContent = item.jabatan_instansi || ''; }
    filled++;
  }
  renumber(table);
  return { filled, added };
}

function removeEmptyTrailing(table) {
  if (!table) return 0;
  const tbody = table.querySelector('tbody');
  const rows = Array.from(tbody.querySelectorAll('tr'));
  let removed = 0;
  for (let i = rows.length - 1; i >= 0; i--) {
    if (isRowEmpty(rows[i])) { rows[i].remove(); removed++; } else { break; }
  }
  renumber(table);
  return removed;
}

const api = { getPesertaTable, detectVariant, renumber, createRow, addRows, parseNameLines, pasteNames, removeEmptyTrailing };
if (typeof window !== 'undefined') window.DaftarHadirTable = api;
export default api;
export { getPesertaTable, detectVariant, renumber, createRow, addRows, parseNameLines, pasteNames, removeEmptyTrailing };
