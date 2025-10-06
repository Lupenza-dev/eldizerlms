<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <title>Fomu ya Maombi ya Mkopo</title>
    <style>
        @page {
            margin: 40px 30px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            line-height: 1.6;
        }

        .header {
            background-color: #0b1e33;
            color: white;
            padding: 15px;
            text-align: center;
            border-bottom: 3px solid #e74c3c;
        }

        .header img {
            height: 60px;
            float: left;
        }

        .header h1 {
            font-size: 18px;
            margin: 0;
        }

        .header p {
            margin: 0;
            font-size: 11px;
            color: #f5b041;
        }

        h2 {
            font-size: 14px;
            background-color: #f2f2f2;
            padding: 5px;
            border-left: 5px solid #0b1e33;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        td, th {
            padding: 6px;
            border: 1px solid #ccc;
            vertical-align: top;
        }

        .note {
            color: #c0392b;
            font-size: 12px;
            margin-top: 20px;
        }

        .signature {
            margin-top: 50px;
        }

        .footer {
            position: fixed;
            bottom: 10px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <!-- HEADER -->
    <div class="header">
        {{-- <img src="{{ public_path('assets/images/eldizerlogo.jpeg') }}" alt="Logo"> --}}
        <h1>EL-DIZER FINANCIAL SERVICES</h1>
        <p>Tanzania's Most Trusted Lend</p>
    </div>

    <br><br>

    <h2>FOMU YA MAOMBI YA MKOPO</h2>

    <h3>1. TAARIFA ZA MWOMBAJI</h3>
    <table>
        <tr>
            <td>Jina la kwanza:</td>
            <td>{{ $loan->customer->first_name ?? '________________' }}</td>
            <td>Jina la Kati:</td>
            <td>{{ $loan->customer->middle_name ?? '________________' }}</td>
            <td>Jina Mwisho:</td>
            <td>{{ $loan->customer->last_name ?? '________________' }}</td>
        </tr>
        <tr>
            <td>Namba ya simu:</td>
            <td>{{ $loan->customer->phone_number ?? '________________' }}</td>
            <td>Barua pepe:</td>
            <td colspan="3">{{ $loan->customer->email ?? '________________' }}</td>
        </tr>
        <tr>
            <td>Chuo:</td>
            <td>{{ $loan->college?->name ?? '________________' }}</td>
            <td>Kitivo/Idara:</td>
            <td colspan="3">{{ $loan->student?->course ?? '________________' }}</td>
        </tr>
        <tr>
            <td>Namba ya usajili:</td>
            <td>{{ $loan->student?->student_reg_id ?? '________________' }}</td>
            <td>Kozi:</td>
            <td colspan="3">{{ $loan->student?->course ?? '________________' }}</td>
        </tr>
        <tr>
            <td>Mwaka wa masomo:</td>
            <td>{{ $loan->student?->study_year ?? '________________' }}</td>
            <td>Namba ya Mtihani wa kidato cha nne:</td>
            <td colspan="3">{{ $loan->student?->form_four_index_no ?? '________________' }}</td>
        </tr>
        <tr>
            <td>Hali ya HESLB:</td>
            <td>{{ $loan->student?->heslb_status ?? '________________' }}</td>
            <td>Mkoa:</td>
            <td>{{ $loan->customer?->region?->name ?? '________________' }}</td>
            <td>Wilaya:</td>
            <td>{{ $loan->customer?->district?->name ?? '________________' }}</td>
        </tr>
    </table>

    <h3>2. TAARIFA YA MKOPO</h3>
    <table>
        <tr>
            <td>Jumla ya Mkopo:</td>
            <td>{{ number_format( $loan->loan_amount) ?? '________________' }}</td>
            <td>Kiasi kinachoombwa:</td>
            <td>{{ number_format($loan->amount) ?? '________________' }}</td>
        </tr>
        <tr>
            <td>Mpango wa Marejesho:</td>
            <td>{{ $loan->plan ?? '________________' }}</td>
            <td>Tarehe ya Kuanza:</td>
            <td>{{ $loan->start_date ?? '________________' }}</td>
        </tr>
        <tr>
            <td>Tarehe ya Kukamilika:</td>
            <td>{{ $loan->expected_end_date ?? '________________' }}</td>
            <td>Kiwango cha Riba:</td>
            <td>3.5%</td>
        </tr>
        <tr>
            <td>Ada ya usimamizi:</td>
            <td>9.5%</td>
            <td>Adhabu ya Marejesho:</td>
            <td>5%</td>
        </tr>
        <tr>
            <td>Jumla ya miezi:</td>
            <td colspan="3">{{ $loan->plan ?? '________________' }}</td>
        </tr>
    </table>

    <h3>3. WAJIBU WA MDAIWA</h3>
    <p>Kila mteja (mdaiwa) anayekopa kupitia huduma za El-dizer anatakiwa kutimiza majukumu yafuatayo:</p>
    <ol>
        <li>Kutoa taarifa sahihi kuhusu taarifa zote pamoja na hali ya masomo na taarifa za HESLB.</li>
        <li>Kushirikiana kikamilifu na El-dizer katika kipindi chote cha mkataba wa mkopo.</li>
        <li>Kutoa taarifa mara moja baada ya kupokea stahiki zako (Boom Allowance).</li>
        <li>Kutoa taarifa endapo fedha hazitakatwa moja kwa moja kutoka kwenye akaunti yako.</li>
        <li>Kutoa taarifa mapema kuhusu changamoto yoyote inayoweza kuathiri marejesho.</li>
        <li>Kuhakikisha malipo yote yanafanywa kupitia akaunti ya NMB 53010015913 ya El-dizer pekee.</li>
    </ol>

    <h3>C. WAJIBU WA MDHAMINI</h3>
    <table>
        <tr><th>No.</th><th>Jina la Mdhamini</th><th>Uhusiano</th><th>Namba ya Simu</th></tr>
        @foreach ($loan->guarantors as $guarantor)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $guarantor->full_name}}</td>
                <td>{{ $guarantor->relationship}}</td>
                <td>{{ $guarantor->phone_number}}</td>
            </tr>
        @endforeach
    </table>

    <h3>D. WAKALA WA CHUO</h3>
    <table>
        <tr><th>No.</th><th>Jina la Wakala</th><th>Namba ya Simu</th></tr>
        <tr><td>1</td><td>{{ $loan->loan_approval?->agent?->name}}</td><td>{{ $loan->loan_approval?->agent?->phone_number}}</td></tr>
    </table>

    <h3>E. UTHIBITISHO WA MKOPO</h3>
    <p>Mwanafunzi anakubali masharti yote ya mkataba huu pamoja na maelezo yaliyomo kwenye ukurasa wa kwanza (Terms & Conditions na Privacy Policy).</p>

    <div class="signature">
        <p>Tarehe: {{ $loan->start_date }}</p>
    </div>

    <p class="note">
        ⚠️ ANGALIZO: Malipo yote ya mkopo lazima yalipwe kwenye akaunti za El-dizer Financial Services (NMB) 53010015913.<br>
        El-Dizer inaweza kufuatilia mkopo kupitia wadhamini, Dean of Students, CRs, au hatua za kisheria.
    </p>

    <div class="footer">
        © {{ date('Y') }} El-Dizer Financial Services — Tanzania's Most Trusted Lend
    </div>
</body>
</html>
