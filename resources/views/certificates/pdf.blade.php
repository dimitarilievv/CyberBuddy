<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @page {
            margin: 0;
            size: A4 landscape;
        }

        body {
            margin: 0;
            padding: 0;
        }

        .page {
            width: 100%;
            min-height: 100vh;
            padding: 36px;
        }

        .frame {
            border: 5px solid #bfdbfe;
            border-radius: 16px;
            padding: 40px;
            background: #fff;
        }

        .frame-inner {
            border: 1.5px dashed #dbeafe;
            border-radius: 10px;
            padding: 36px;
            text-align: center;
        }

        .academy {
            font-size: 10px;
            font-weight: bold;
            letter-spacing: 4px;
            color: #2563eb;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .cert-title {
            font-size: 36px;
            font-weight: bold;
            color: #1e293b;
            letter-spacing: 4px;
            text-transform: uppercase;
        }

        .cert-subtitle {
            font-size: 11px;
            letter-spacing: 5px;
            color: #1e293b;
            text-transform: uppercase;
            margin-top: 2px;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .divider {
            height: 1px;
            background: #dbeafe;
            margin: 18px auto;
            width: 60%;
        }

        .certify-text {
            font-size: 13px;
            color: #64748b;
            font-style: italic;
            margin-bottom: 6px;
        }

        .recipient {
            font-size: 30px;
            font-weight: bold;
            color: #1e293b;
            margin: 4px 0 16px;
        }

        .body-text {
            font-size: 12px;
            color: #475569;
            line-height: 1.8;
        }

        .module-title {
            font-size: 15px;
            font-weight: bold;
            color: #2563eb;
            margin: 10px 0 16px;
        }

        .score-text {
            font-size: 12px;
            color: #475569;
            margin-bottom: 4px;
        }

        .score-value {
            font-weight: bold;
            color: #2563eb;
        }

        .meta-row {
            display: table;
            width: 100%;
            margin-top: 22px;
            padding-top: 12px;
            border-top: 1px solid #dbeafe;
        }

        .meta-left {
            display: table-cell;
            text-align: left;
            font-size: 9px;
            color: #94a3b8;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .meta-right {
            display: table-cell;
            text-align: right;
            font-size: 9px;
            color: #94a3b8;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .meta-value {
            display: block;
            color: #1e293b;
            font-size: 10px;
            font-weight: bold;
            margin-top: 3px;
        }

        .footer-note {
            font-size: 9px;
            color: #94a3b8;
            margin-top: 14px;
            line-height: 1.6;
        }
    </style>
</head>
<body>
<div class="page">
    <div class="frame">
        <div class="frame-inner">
            <div class="academy">CyberBuddy Academy</div>
            <div class="cert-title">CERTIFICATE</div>
            <div class="cert-subtitle">OF COMPLETION</div>
            <div class="divider"></div>
            <div class="certify-text">This is to certify that</div>
            <div class="recipient">{{ $certificate->user->name }}</div>
            <div class="body-text">has successfully completed the comprehensive cybersecurity training module:</div>
            <div class="module-title">"{{ $certificate->module->title }}"</div>
            <div class="score-text">with a final score of <span
                    class="score-value">{{ $certificate->final_score }}</span></div>
            <div class="divider"></div>
            <div class="meta-row">
                <div class="meta-left">
                    Issued On
                    <span
                        class="meta-value">{{ \Carbon\Carbon::parse($certificate->issued_at)->format('F d, Y') }}</span>
                </div>
                <div class="meta-right">
                    Certificate ID
                    <span
                        class="meta-value">{{ $certificate->certificate_number ?? ('CBP-' . str_pad($certificate->id, 6, '0', STR_PAD_LEFT)) }}</span>
                </div>
            </div>
            <div class="footer-note">
                This certificate is a permanent record of your digital safety skills.<br>
                You can download it for your portfolio or print it to show your teacher!
            </div>
        </div>
    </div>
</div>
</body>
</html>
