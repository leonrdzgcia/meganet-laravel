<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <style>
        .page-break {
            page-break-after: always;
        }

        .conteiner-font {
            font-family: "Helvetica Neue", sans-serif;
        }

        .banner-top {
            margin-top: 25px;
            text-align: right;
            background-color: #E7F7FE;
            padding: 1%;
        }

        .column-header {
            color: #308CD2;
        }

        .info-text {
            color: grey;
            font-size: 14px;
        }

        table, td, th {
            text-align: left;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        .tr-button {
            font-size: 14px;
            background-color: #E7F7FE;
        }

        .table-no-pading{
            padding: 0;
            margin: 0;
        }

        .td-strong{
            font-weight: bold;
        }

        th, td {
            padding: 15px;
        }

        .r-border {
            border-bottom: 1px solid #ddd;
        }

    </style>
    <title>Pago</title>
</head>
<body>
<div>
        <div style="width: 55%; float:left margin-top: 15px; padding-bottom: 45px;">
            <img src="{{ public_path('/images/logo_meganet.jpg') }}" alt="" height="70">
        </div>
<div style="margin-top: 3px; margin-bottom: 45px">
    <div style="width: 45%; float:right; text-align: left;font-size: 12px; ">
        <span style="font-weight: bold"> Meganet Telecomunicaciones S.A. de C.V.</span>   <br>
        <span>Company ID : Meganet AV HDA LA PURISIMA MZ3 LT 54</span><br>
        <span>CASA A EX HDA SANTA INES</span><br>
        <span>NEXTLALPAN Mexico 55790</span><br>
    </div>
</div>
</div>

<div class="conteiner-font">
    <div class="banner-top column-header">
        ADEUDO <strong>{{ $data['debit'] }}</strong>
    </div>

    <div style="margin-top: 15px">
        <div style="width: 60%; float:left">
            <strong>{{ $data['client_name_with_fathers_names'] }}</strong> <br>
            <div class="info-text">{{ $data['street'] }}<br>
                {{ $data['state'] }} <br>
                {{ $data['municipality'] }} <br>
                {{ $data['colony'] }} <br>
                IN {{ $data['zip'] }} <br>
            </div>
        </div>

            @if($data['payment'] && $data['estado'] != 'Cancelada')
                <img src="{{ public_path('/images/paid.png') }}" height="150" style="float: left; margin-top:25px; margin-left:-200px;">
            @endif
            @if($data['estado'] == 'Cancelada')
                <img src="{{ public_path('/images/cancelled.png') }}" height="150" style=" float: left; margin-top:25px; margin-left:-200px;">
            @endif

        <div style="width: 40%; float:right; text-align: right; font-size: 13px;">
            <span class="info-text">Fecha de Factura </span> {{ $data['created_at'] }} <br>
            <span class="info-text">Fecha de Vencimiento </span> {{ $data['pay_up'] ? $data['pay_up'] : '--' }} <br>
        </div>
    </div>

</div>
    <div style="margin-top: 15%">
    <table class="table">
        <thead>
        <tr class="r-border column-header">
            <td>#</td>
            <td>DESCRIPCIÓN DEL ARTÍCULO</td>
            <td>IVA %</td>
            <td>IVA</td>
            <td>MONTO</td>
        </tr>
        </thead>
        <tbody>
        @foreach($data['client_services'] as $service)
        <tr>
            <td>{{ $service['number'] }}</td>
            <td>{{ $service['service_name'] }}</td>
            <td>{{ $service['iva_porcent'] }}</td>
            <td>{{ $service['iva'] }}</td>
            <td>{{ $service['monto'] }}</td>
        </tr>
        @endforeach
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr class="tr-button">
            <td></td>
            <td></td>
            <td></td>
            <td>Sub Total</td>
            <td>{{ $data['sub_total']}}</td>
        </tr>
        <tr class="tr-button">
            <td></td>
            <td></td>
            <td></td>
            <td>Mexico IVA</td>
            <td>{{ $data['total_iva']}}</td>
        </tr>
        <tr class="tr-button td-strong">
            <td></td>
            <td></td>
            <td></td>
            <td>Total</td>
            <td>{{ $data['total']}}</td>
        </tr>
        <tr class="tr-button column-header">
            <td></td>
            <td></td>
            <td></td>
            <td>ADEUDO</td>
            <td>{{ $data['debit']}}</td>
        </tr>
        </tbody>
    </table>
</div>


<div class="conteiner-font">
    <span style="font-size: 12px">Para</span>
    <div>
        <div style="width: 60%; float:left; font-size: 12px">
            <strong>{{ $data['client_name_with_fathers_names'] }}</strong> <br>
            <div class="info-text">_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _<br>
            </div>
        </div>

        <div style="width: 40%; float:right; text-align: left">
            <span style="font-size: 24px" > Recibo de Pago </span>
        </div>
    </div>
</div>

<div style="margin-top: 10%; width: 40%;">
    <table style="font-size: 14px">
        <tr>
            <td class="table-no-pading">Factura#</td>
            <td class="table-no-pading">:  {{ $data['number']}} </td>
        </tr>
        <tr>
            <td class="table-no-pading">Fecha de Factura</td>
            <td class="table-no-pading">:  {{ $data['created_at']}}</td>
        </tr>
        <tr>
            <td class="table-no-pading">Adeudo</td>
            <td class="table-no-pading">:   {{ $data['debit']}}</td>
        </tr>
     @if($data['estado'] == 'Cancelada')
        <tr>
            <td class="table-no-pading">Traslando de débito a factura:</td>
            <td class="table-no-pading">:   {{ $data['note']}}</td>
        </tr>
       @endif
    </table>
</div>

<div style="margin-top: 2%; width: 35%;">
    <table style="font-size: 14px">
        <tr>
            <td style="font-weight: bold;font-size: 13px ;  text-align: center ;background-color: #E3E3E3; width: 30%; padding: 5px 10px 5px 10px; border: 1px solid  #BFBFBF">Monto Adjunta</td>
            <td style="border: 1px solid #BFBFBF; padding: 2px;"></td>
        </tr>
    </table>
</div>


</body>
</html>



