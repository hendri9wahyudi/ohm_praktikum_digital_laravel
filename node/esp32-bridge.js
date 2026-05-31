/**
 * Node.js bridge untuk menerima data ESP32 lalu meneruskannya ke Laravel.
 * Jalankan:
 *   npm init -y
 *   npm install express axios cors dotenv
 *   node esp32-bridge.js
 */

require('dotenv').config();
const express = require('express');
const cors = require('cors');
const axios = require('axios');

const app = express();
app.use(cors());
app.use(express.json());

const LARAVEL_API = process.env.LARAVEL_API || 'http://127.0.0.1:8000/api/sensors/ingest';

app.get('/health', (_req, res) => {
  res.json({ ok: true, service: 'esp32-bridge' });
});

app.post('/esp32', async (req, res) => {
  try {
    const payload = req.body;
    const result = await axios.post(LARAVEL_API, payload, {
      headers: { 'Content-Type': 'application/json' },
    });
    res.json({ ok: true, forwarded: true, data: result.data });
  } catch (error) {
    res.status(500).json({ ok: false, message: error.message });
  }
});

app.listen(process.env.PORT || 3001, () => {
  console.log('ESP32 bridge running on port ' + (process.env.PORT || 3001));
});
