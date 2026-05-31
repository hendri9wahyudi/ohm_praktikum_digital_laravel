document.addEventListener('DOMContentLoaded', () => {
    const cfg = window.practiceConfig || {};
    const csrf = cfg.csrf;
    const chartEl = document.getElementById('ohmChart');
    const processMsg = document.getElementById('processMsg');
    const analysisText = document.getElementById('analysisText');
    const processBtn = document.getElementById('processBtn');
    const finishBtn = document.getElementById('finishBtn');
    const rows = [...document.querySelectorAll('.question-row')];
    const sensorData = {};
    let chart;

    function ensureChart(points = []) {
        const ctx = chartEl.getContext('2d');
        if (chart) {
            chart.destroy();
        }
        chart = new Chart(ctx, {
            type: 'line',
            data: {
                datasets: [{
                    label: 'Tegangan vs Hambatan',
                    data: points,
                    parsing: false,
                    borderWidth: 2,
                    tension: 0.2,
                    fill: false,
                }],
            },
            options: {
                responsive: true,
                scales: {
                    x: { type: 'linear', title: { display: true, text: 'Tegangan (V)' } },
                    y: { title: { display: true, text: 'Resistor (Ohm)' } },
                },
            },
        });
    }

    ensureChart([]);

    rows.forEach((row) => {
        const qNo = row.dataset.questionNo;
        const verifyBtn = row.querySelector('.btn-verify');
        const startBtn = row.querySelector('.btn-start');
        const answerInput = row.querySelector('.student-answer');
        const verification = row.querySelector('.verification');
        const sensorBox = document.querySelector(`.sensor-box[data-question-no="${qNo}"] .sensor-output`);

        verifyBtn.addEventListener('click', async () => {
            const answer = answerInput.value;
            const res = await fetch(cfg.verifyUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ question_no: qNo, answer }),
            });
            const json = await res.json();
            if (json.ok) {
                verification.innerHTML = `<span class="badge ${json.is_correct ? 'text-bg-success' : 'text-bg-danger'}">${json.is_correct ? 'Benar' : 'Salah'}</span> <span class="ms-2">Jawaban benar: ${json.correct_answer}</span>`;
            } else {
                verification.textContent = 'Gagal verifikasi.';
            }
        });

        startBtn.addEventListener('click', async () => {
            const res = await fetch(cfg.startUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ question_no: qNo }),
            });
            const json = await res.json();
            if (json.ok) {
                sensorBox.textContent = json.text;
                sensorData[qNo] = json.reading;
            } else {
                sensorBox.textContent = 'Gagal membaca sensor.';
            }
        });
    });

    processBtn.addEventListener('click', async () => {
        const res = await fetch(cfg.processUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ analysis_text: analysisText.value }),
        });
        const json = await res.json();
        if (json.ok) {
            processMsg.innerHTML = `<span class="text-success">Grafik dan analisis berhasil diproses.</span>`;
            ensureChart(json.chart || []);
            if (!analysisText.value.trim()) {
                analysisText.value = json.analysis || '';
            }
        } else {
            processMsg.innerHTML = `<span class="text-danger">Proses gagal.</span>`;
        }
    });

    finishBtn.addEventListener('click', async () => {
        const res = await fetch(cfg.finishUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ analysis_text: analysisText.value || 'Analisis hasil praktikum.' }),
        });
        const json = await res.json();
        if (json.ok) {
            processMsg.innerHTML = `<span class="text-success">${json.message} Total nilai: ${json.total_score}</span>`;
        } else {
            processMsg.innerHTML = `<span class="text-danger">Finish gagal.</span>`;
        }
    });
});
