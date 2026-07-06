{{--
    FOMU 01: MAOMBI YA MKOPO — Filled Version (with loan data)
    Route suggestion: Route::get('loans/{loan}/maombi-ya-mkopo', [LoanController::class, 'printMaombiYaMkopo']);
    Controller passes: $loan->load(['client.client', 'client.client.nextOfKin', 'loanCategory', 'collectionOfficer'])
--}}
<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fomu 01 – Maombi ya Mkopo | {{ optional($loan->client)->client->name ?? '' }}</title>
    <style>
        /* ─── Reset & Base ─────────────────────────── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            color: #000;
            background: #fff;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* ─── Page ──────────────────────────────────── */
        .page {
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            padding: 10mm 12mm 12mm;
            position: relative;
        }

        /* ─── Header ────────────────────────────────── */
        .header {
            text-align: center;
            margin-bottom: 4px;
        }
        .header .logo-row {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            margin-bottom: 2px;
        }
        .header .logo-icon {
            width: 36px;
            height: 36px;
        }
        .header .brand-name {
            font-size: 22pt;
            font-weight: 900;
            letter-spacing: 1px;
            color: #000;
            line-height: 1;
        }
        .header .brand-name span { color: #1a5aad; }
        .header .tagline {
            font-size: 7.5pt;
            color: #1a5aad;
            font-style: italic;
            letter-spacing: 0.5px;
        }
        .header .address {
            font-size: 13pt;
            font-weight: bold;
            margin-top: 2px;
        }
        .header-line {
            border: none;
            border-top: 3px double #000;
            margin: 4px 0 5px;
        }

        /* ─── Kituo row ─────────────────────────────── */
        .kituo-row {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            font-size: 10pt;
            font-weight: bold;
            margin-bottom: 5px;
            gap: 6px;
        }
        .kituo-row .kituo-box {
            border: 1.5px solid #000;
            min-width: 120px;
            padding: 1px 6px;
            font-weight: normal;
            min-height: 16px;
        }

        /* ─── Section Headers ───────────────────────── */
        .form-title {
            font-size: 11pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 6px 0 3px;
            border-bottom: 1.5px solid #000;
            padding-bottom: 1px;
        }
        .section-title {
            font-size: 10pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 5px 0 2px;
        }

        /* ─── Table Grid ────────────────────────────── */
        table.form-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 4px;
        }
        table.form-table td {
            border: 1px solid #000;
            padding: 2px 5px;
            font-size: 10pt;
            vertical-align: middle;
            min-height: 16px;
            height: 16px;
        }
        table.form-table td.label {
            font-weight: bold;
            white-space: nowrap;
            background: #f0f0f0;
            width: auto;
        }
        table.form-table td.value {
            min-width: 80px;
        }
        table.form-table td.wide-label {
            font-weight: bold;
            background: #f0f0f0;
        }

        /* inline checkbox style */
        .cb-row { display: inline-flex; align-items: center; gap: 3px; margin-right: 8px; }
        .cb-box {
            display: inline-block;
            width: 11px; height: 11px;
            border: 1.5px solid #000;
            text-align: center;
            line-height: 10px;
            font-size: 9pt;
            font-weight: bold;
        }
        .cb-box.checked::after { content: '✓'; }

        /* ─── Signature / disclaimer ────────────────── */
        .note-box {
            border: 1px solid #000;
            padding: 5px 7px;
            font-size: 9pt;
            margin-top: 6px;
            text-align: justify;
            line-height: 1.45;
        }
        .note-box strong { font-size: 9.5pt; }

        /* ─── Footer ────────────────────────────────── */
        .footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 8px;
            border-top: 2px solid #000;
            padding-top: 4px;
            font-size: 9pt;
        }
        .footer .footer-brand { font-weight: 900; font-size: 11pt; }
        .footer .footer-brand span { color: #1a5aad; }
        .footer .footer-center { text-align: center; font-style: italic; }
        .footer .footer-page { font-size: 9pt; }

        /* ─── Print ─────────────────────────────────── */
        .no-print { display: block; }
        @media print {
            .no-print { display: none !important; }
            .page { margin: 0; padding: 8mm 10mm 10mm; }
            body { background: #fff; }
        }

        /* ─── Print Button ──────────────────────────── */
        .print-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #1a5aad;
            color: #fff;
            border: none;
            padding: 8px 20px;
            font-size: 11pt;
            border-radius: 4px;
            cursor: pointer;
            margin: 12px auto 0;
        }
        .print-bar {
            text-align: center;
            padding: 10px 0;
            background: #f8f8f8;
            border-bottom: 1px solid #ddd;
        }

        /* ─── Dotted signature lines ────────────────── */
        .sig-line {
            display: inline-block;
            border-bottom: 1px dotted #000;
            min-width: 160px;
        }
    </style>
</head>
<body>

{{-- Print Bar --}}
<div class="print-bar no-print">
    <button class="print-btn" onclick="window.print()">
        🖨️ Chapisha Fomu
    </button>
    <a href="javascript:history.back()" style="margin-left:14px;font-size:10pt;color:#555;">← Rudi</a>
</div>

<div class="page">

    {{-- ══ HEADER ══ --}}
    <div class="header">
        <div class="logo-row">
            {{-- ArBif logo text --}}
            <div>
                <div class="brand-name">Ar<span>Bif</span></div>
                <div class="tagline">Let's Grow Together</div>
            </div>
        </div>
        <div class="address">Mkolani Street-Mwanza, Tanzania</div>
    </div>
    <hr class="header-line">

    {{-- Jina la Kituo --}}
    <div class="kituo-row">
        <span>JINA LA KITUO:</span>
        <span class="kituo-box">{{ optional($loan->client)->group?->name ?? '' }}</span>
    </div>

    {{-- ══ FOMU TITLE ══ --}}
    <div class="form-title">Fomu 01: Maombi Ya Mkopo</div>

    {{-- ══════════════════════════════════════════
         01. TAARIFA ZA MWOMBAJI
    ══════════════════════════════════════════ --}}
    <div class="section-title">01. Taarifa Za Mwombaji</div>

    @php
        $client  = optional($loan->client)->client;
        $dob     = $client?->DateOfBirth ? \Carbon\Carbon::parse($client->DateOfBirth)->format('d/m/Y') : '';
        $ndoa    = $client?->MaritalStatus ?? '';
    @endphp

    <table class="form-table">
        <tr>
            <td class="label" style="width:38%">Jina kamili la mwombaji</td>
            <td class="value" colspan="3">{{ $client?->name ?? '' }}</td>
        </tr>
        <tr>
            <td class="label">Tarehe ya Kuzaliwa</td>
            <td class="value">{{ $dob }}</td>
            <td class="label" style="width:12%">Simu</td>
            <td class="value">{{ $client?->PhoneNumber ?? '' }}</td>
        </tr>
        <tr>
            <td class="label">Jinsia</td>
            <td class="value">
                <span class="cb-row">
                    <span class="cb-box {{ $client?->Gender === 'Male' ? 'checked' : '' }}"></span> Mwanamke
                </span>
                <span class="cb-row">
                    <span class="cb-box {{ $client?->Gender === 'Female' ? 'checked' : '' }}"></span> Mwanaume
                </span>
            </td>
            <td class="label">Namba ya NIDA</td>
            <td class="value">{{ $client?->NationalIdNumber ?? '' }}</td>
        </tr>
        <tr>
            <td class="label">Ndoa</td>
            <td class="value" colspan="3">
                <span class="cb-row"><span class="cb-box {{ $ndoa==='Single'?'checked':'' }}"></span> Hajaoa/Olewa</span>
                <span class="cb-row"><span class="cb-box {{ $ndoa==='Married'?'checked':'' }}"></span> Ameoa/Olewa</span>
                <span class="cb-row"><span class="cb-box {{ $ndoa==='Separated'?'checked':'' }}"></span> Ameachika</span>
                <span class="cb-row"><span class="cb-box {{ $ndoa==='Widowed'?'checked':'' }}"></span> Mjane/Mgand</span>
            </td>
        </tr>
        <tr>
            <td class="label">Eneo unaloishi</td>
            <td class="value">{{ $client?->ResidentialAddress ?? '' }}</td>
            <td class="label">Umeishi hapo tangu lini</td>
            <td class="value">{{ $client?->ResidenceSince ?? '' }}</td>
        </tr>
        <tr>
            <td class="label">Umiliki wa makazi</td>
            <td class="value" colspan="3">
                <span class="cb-row"><span class="cb-box {{ ($client?->HouseOwnership==='Own')?'checked':'' }}"></span> kwako</span>
                <span class="cb-row"><span class="cb-box {{ ($client?->HouseOwnership==='Rented')?'checked':'' }}"></span> Umepanga</span>
                <span style="margin-left:8px;font-weight:bold;">Mengine (Eleza):</span> {{ $client?->HouseOwnershipOther ?? '' }}
            </td>
        </tr>
        <tr>
            <td class="label">Jina Kamili la mwenza</td>
            <td class="value">{{ $client?->SpouseName ?? '' }}</td>
            <td class="label">Maarufu Mtaani</td>
            <td class="value">{{ $client?->Alias ?? '' }}</td>
        </tr>
        <tr>
            <td class="label">Simu</td>
            <td class="value">{{ $client?->SpousePhone ?? '' }}</td>
            <td class="label">Sahihi</td>
            <td class="value">&nbsp;</td>
        </tr>
    </table>

    {{-- ══════════════════════════════════════════
         02. TAARIFA ZA BIASHARA
    ══════════════════════════════════════════ --}}
    <div class="section-title">02. Taarifa Za Biashara</div>
    <table class="form-table">
        <tr>
            <td class="label" style="width:38%">Jina la Biashara</td>
            <td class="value">{{ $client?->BusinessName ?? '' }}</td>
        </tr>
        <tr>
            <td class="label">Mahali Biashara Ilipo</td>
            <td class="value">{{ $client?->BusinessLocation ?? '' }}</td>
        </tr>
        <tr>
            <td class="label">Umefanya Biashara hii tangu lini</td>
            <td class="value">{{ $client?->BusinessSince ?? '' }}</td>
        </tr>
        <tr>
            <td class="label">Wastani wa kipato kwa mwezi</td>
            <td class="value">{{ $client?->MonthlyIncome ? 'TZS '.number_format($client->MonthlyIncome,2) : '' }}</td>
        </tr>
    </table>

    {{-- ══════════════════════════════════════════
         03. TAARIFA ZA AJIRA
    ══════════════════════════════════════════ --}}
    <div class="section-title">03. Taarifa Za Ajira</div>
    <table class="form-table">
        <tr>
            <td class="label" style="width:38%">Jina la mwajiri / Kampuni</td>
            <td class="value">{{ $client?->EmployerName ?? '' }}</td>
        </tr>
        <tr>
            <td class="label">Mahali ofisi ilipo</td>
            <td class="value">{{ $client?->EmployerAddress ?? '' }}</td>
        </tr>
        <tr>
            <td class="label">Wadhifa wako</td>
            <td class="value">{{ $client?->JobTitle ?? '' }}</td>
        </tr>
        <tr>
            <td class="label">Umefanyakazi hapo toka lini</td>
            <td class="value">{{ $client?->EmployedSince ?? '' }}</td>
        </tr>
    </table>

    {{-- ══════════════════════════════════════════
         04. KIASI CHA MKOPO KINACHOOMBWA
    ══════════════════════════════════════════ --}}
    <div class="section-title">04. Kiasi Cha Mkopo Kinachoombwa</div>
    <table class="form-table">
        <tr>
            <td class="label" style="width:38%">Kiasi cha mkopo</td>
            <td class="value" style="width:30%">
                {{ $loan->amount_requested ? 'TZS '.number_format($loan->amount_requested,2) : '' }}
            </td>
            <td class="label" style="width:18%">Muda wa kulipa mkopo</td>
            <td class="value">{{ optional($loan->loanCategory)->duration ?? '' }}</td>
        </tr>
        <tr>
            <td class="label" colspan="1">Ni kiasi gani cha rejesho unaweza kulipa bila tatizo</td>
            <td class="value" colspan="3">{{ $loan->client_payable_frequency ? 'TZS '.number_format($loan->client_payable_frequency,2) : '' }}</td>
        </tr>
        <tr>
            <td class="label">Dhumuni la mkopo</td>
            <td class="value" colspan="3">{{ $loan->loan_purpose ?? '' }}</td>
        </tr>
    </table>

    {{-- ══════════════════════════════════════════
         05. TAARIFA ZA MDHAMINI
    ══════════════════════════════════════════ --}}
    <div class="section-title">05. Taarifa Za Mdhamini</div>
    @php $guarantor = $client?->guarantor ?? null; @endphp
    <table class="form-table">
        <tr>
            <td class="label" style="width:38%">Jina la Mdhamini</td>
            <td class="value" style="width:30%">{{ $guarantor?->name ?? '' }}</td>
            <td class="label" style="width:12%">Simu</td>
            <td class="value">{{ $guarantor?->phone ?? '' }}</td>
        </tr>
        <tr>
            <td class="label">Mahali anapoishi</td>
            <td class="value" colspan="3">{{ $guarantor?->address ?? '' }}</td>
        </tr>
        <tr>
            <td class="label">Taarifa za Kazi</td>
            <td class="value" colspan="3">
                <span class="cb-row">
                    <span class="cb-box {{ ($guarantor?->employment_type==='Employed')?'checked':'' }}"></span> Ameajiriwa
                </span>
                <span class="cb-row">
                    <span class="cb-box {{ ($guarantor?->employment_type==='Self')?'checked':'' }}"></span> Amejiajiri
                </span>
            </td>
        </tr>
        <tr>
            <td class="label">Jina la kampuni/biashara</td>
            <td class="value" style="width:30%">{{ $guarantor?->employer ?? '' }}</td>
            <td class="label">Sahihi</td>
            <td class="value">&nbsp;</td>
        </tr>
        <tr>
            <td class="label">Mahali ofisi ilipo</td>
            <td class="value" colspan="3">{{ $guarantor?->office_address ?? '' }}</td>
        </tr>
    </table>

    {{-- ══════════════════════════════════════════
         07. TAARIFA ZA MWENYEKITI WA MTAA
    ══════════════════════════════════════════ --}}
    <div class="section-title">07. Taarifa Za Mwenyekiti Wa Mtaa</div>
    <table class="form-table">
        <tr>
            <td style="padding: 3px 5px; font-size:10pt;">
                Mwenyekiti wa Mtaa/Mtendaji
                <span class="sig-line" style="min-width:200px;">&nbsp;</span>
                &nbsp;&nbsp; Sahihi
                <span class="sig-line" style="min-width:120px;">&nbsp;</span>
            </td>
        </tr>
    </table>

    {{-- ══ ANGALIZO ══ --}}
    <div class="note-box">
        <strong>ANGALIZO:</strong> Marejesho yote ya <strong>ArBif Microfinance Ltd</strong> yatafanyika
        kituoni kupitia wanachama wa kikundi husika waliodhamini ana kwa kusaini makubaliano au mikataba ya
        <strong>ArBif Microfinance Ltd</strong>. Mwanachama au wanachama watakaolipa fedha kwa watu wengine
        nje ya wanakikundi wenzao au iwapo wanakikundi hawatayafikisha watadaiwa tena marejesho yao.
        Hairuhusiwi kumtumia kiongozi marejesho. Dhamana za mteja zitachukuliwa kwa yeye au wanakikundi
        wenzake kushindwa kulipa marejesho ya mkopo/mikopo yao na vitauzwa baada ya siku 7 tangu
        zilipochukuliwa.
    </div>

    {{-- ══ FOOTER ══ --}}
    <div class="footer">
        <div class="footer-brand">Ar<span>Bif</span></div>
        <div class="footer-center">
            Let's Grow Together<br>
            <small>Copyright&copy;2025</small>
        </div>
        <div class="footer-page">Page 1</div>
    </div>

</div>{{-- end .page --}}

</body>
</html>