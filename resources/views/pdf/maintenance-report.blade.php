<!DOCTYPE html>
<html>
<head>
    test
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; color: #1a202c; line-height: 1.5; font-size: 13px; }
        .header { margin-bottom: 40px; border-bottom: 3px solid #4f46e5; padding-bottom: 20px; }
        .title { font-size: 24px; font-weight: bold; color: #111827; margin: 0; }
        .subtitle { font-size: 12px; color: #6b7280; text-transform: uppercase; letter-spacing: 1px; margin-top: 5px; }
        
        .info-grid { width: 100%; margin-bottom: 30px; }
        .info-box { background: #f9fafb; padding: 15px; border-radius: 8px; border: 1px solid #f3f4f6; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th { background-color: #4f46e5; color: white; text-align: left; padding: 12px; font-size: 11px; text-transform: uppercase; }
        td { padding: 12px; border-bottom: 1px solid #e5e7eb; }
        
        .label { font-weight: bold; color: #374151; }
        .status { font-weight: bold; text-transform: uppercase; font-size: 10px; padding: 4px 8px; border-radius: 4px; }
        
        /* Couleurs pour DomPDF */
        .urgent { color: #dc2626; background-color: #fee2e2; }
        .a_prevoir { color: #d97706; background-color: #fef3c7; }
        .bon { color: #059669; background-color: #d1fae5; }

        .footer { margin-top: 50px; font-size: 10px; color: #9ca3af; text-align: center; border-top: 1px solid #f3f4f6; padding-top: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Rapport de Contrôle</div>
        <div class="subtitle">Maintenance périodique - {{ $plan->truck->plate_number }}</div>
    </div>

    <table class="info-grid">
        <tr>
            <td class="info-box" style="width: 50%;">
                <div class="label">VÉHICULE</div>
                <div>{{ $plan->truck->brand }} {{ $plan->truck->model }}</div>
                <div style="font-size: 16px; font-weight: bold; color: #4f46e5;">{{ $plan->truck->plate_number }}</div>
            </td>
            <td style="width: 5%;"></td>
            <td class="info-box" style="width: 45%;">
                <div class="label">DÉTAILS CONTRÔLE</div>
                <div>Date : {{ $plan->check_date->format('d/m/Y') }}</div>
                <div>Par : {{ $plan->user->name ?? 'Technicien' }}</div>
            </td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th style="width: 60%;">Point de contrôle</th>
                <th style="width: 40%; text-align: center;">État</th>
            </tr>
        </thead>
        <tbody>
            @foreach($plan->data as $key => $value)
            <tr>
                <td class="label">
                    {{-- Transforme pneus_av en PNEUS AV --}}
                    {{ strtoupper(str_replace('_', ' ', $key)) }}
                </td>
                <td style="text-align: center;">
                    <span class="status {{ $value }}">
                        {{ str_replace('_', ' ', $value) }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Document généré automatiquement le {{ now()->format('d/m/Y H:i') }}<br>
        AutoLedger - Logiciel de gestion de flotte interne - License MIT - Nextway®
    </div>
</body>
</html>