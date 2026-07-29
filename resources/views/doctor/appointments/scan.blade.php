<x-app-layout>
<div style="padding:24px; max-width:600px; margin:0 auto;">
    <h1 style="font-size:20px; font-weight:700; margin-bottom:4px;">Scan Patient QR Code</h1>
    <p style="color:#6b7280; font-size:14px; margin-bottom:20px;">Point the camera at the patient's QR code to mark their checkup as completed.</p>

    <div style="background:#fff; border-radius:10px; padding:20px; box-shadow:0 1px 3px rgba(0,0,0,0.06);">
        <div id="reader" style="width:100%;"></div>
        <div id="result-box" style="display:none; margin-top:16px; padding:14px; border-radius:8px;"></div>

        <div style="margin-top:16px; border-top:1px solid #e5e7eb; padding-top:16px;">
            <p style="font-size:13px; color:#6b7280; margin-bottom:8px;">Camera not working? Enter the code manually:</p>
            <div style="display:flex; gap:8px;">
                <input type="text" id="manual-token" placeholder="Paste QR token here" style="flex:1; border:1px solid #d1d5db; border-radius:6px; padding:8px; font-size:13px;">
                <button onclick="submitToken(document.getElementById('manual-token').value)" style="background:#4f46e5; color:#fff; padding:8px 16px; border-radius:6px; border:none; cursor:pointer; font-size:13px;">Submit</button>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
    let scanning = true;

    function onScanSuccess(decodedText) {
        if (!scanning) return;
        scanning = false;
        html5QrCode.stop().catch(() => {});
        submitToken(decodedText);
    }

    const html5QrCode = new Html5Qrcode("reader");
    html5QrCode.start(
        { facingMode: "environment" },
        { fps: 10, qrbox: 220 },
        onScanSuccess
    ).catch(function (err) {
        document.getElementById('reader').innerHTML = '<p style="color:#ef4444; font-size:13px;">Camera access failed. Please use manual entry below, or check camera permissions.</p>';
    });

    function submitToken(token) {
        if (!token) return;

        fetch("{{ route('doctor.appointments.complete-scan') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ token: token.trim() })
        })
        .then(res => res.json())
        .then(data => {
            const box = document.getElementById('result-box');
            box.style.display = 'block';
            if (data.success) {
                box.style.background = '#dcfce7';
                box.style.color = '#166534';
                box.innerHTML = '✓ ' + data.message;
            } else {
                box.style.background = '#fee2e2';
                box.style.color = '#b91c1c';
                box.innerHTML = '✗ ' + data.message;
                scanning = true;
                html5QrCode.start({ facingMode: "environment" }, { fps: 10, qrbox: 220 }, onScanSuccess).catch(() => {});
            }
        });
    }
</script>
</x-app-layout>
