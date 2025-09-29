const http = require('http');

function postJson(url, body) {
  return new Promise((resolve, reject) => {
    const u = new URL(url);
    const data = JSON.stringify(body);
    const opts = {
      hostname: u.hostname,
      port: u.port,
      path: u.pathname + u.search,
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'Content-Length': Buffer.byteLength(data) },
    };
    const req = http.request(opts, (res) => {
      let raw = '';
      res.on('data', (c) => raw += c);
      res.on('end', () => resolve({ status: res.statusCode, body: raw }));
    });
    req.on('error', reject);
    req.write(data);
    req.end();
  })
}

function get(url, token) {
  return new Promise((resolve, reject) => {
    const u = new URL(url);
    const opts = { hostname: u.hostname, port: u.port, path: u.pathname + u.search, method: 'GET', headers: {} };
    if (token) opts.headers['Authorization'] = `Bearer ${token}`;
    const req = http.request(opts, (res) => {
      let raw = '';
      res.on('data', (c) => raw += c);
      res.on('end', () => resolve({ status: res.statusCode, body: raw }));
    });
    req.on('error', reject);
    req.end();
  })
}

async function run() {
  const login = await postJson('http://127.0.0.1:8001/api/auth/login', { email: 'admin@example.com', password: 'password' });
  const j = JSON.parse(login.body || '{}');
  if (!j.token) { console.error('Login failed', j); process.exit(1); }
  console.log('Login token received')

  const rep = await get('http://127.0.0.1:8001/api/admin/attendance-report?section_id=1&from=2025-01-01&to=2026-01-01', j.token);
  const data = JSON.parse(rep.body || '[]');
  console.log('Report length:', Array.isArray(data) ? data.length : 'not array', '\nSample:', data[0] || null);
}

run().catch(e => { console.error(e); process.exit(1); });
