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

        .info-text {
            color: grey;
            font-size: 10px;
        }

        /* table, td, th {
            text-align: left;
        } */

        /* table {
            border-collapse: collapse;
            width: fit-content;
            margin-left: 30px;
            font-size: 12px;
        } */

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
            padding: 0;
            font-size: 12px;
        }

        .r-border {
            border-bottom: 1px solid #ddd;
        }
        .uppercase {
            text-transform: uppercase;
        }

    </style>
    <title>Slip de Pago</title>
</head>
<body>
    <div>
{{--        <img src="{{ public_path('/images/logo_meganet.jpg') }}" alt="" height="45">--}}
    </div>

        <table>
            <tbody>
            <tr>
            <th colspan="3" style="text-align:center; padding-bottom: 10px; margin-top:15px; font-size: 14px">Meganet Telecomunicaciones S.A. de C.V.</th>
            </tr>
            <tr>
            <td colspan="3" style="text-align:center;">Atencion a clientes: 55-42-10-62-77</td>
            </tr>
            <tr>
            <td colspan="3" style="text-align:center;">Whatsapp SOLO PAGOS: 55-25-71-67-18</td>
            </tr>
            <tr>
            <td colspan="3" style="text-align:center;">OXXO Dep a Tarjeta:5579-0890-0023-7860  </td>
            </tr>
            <tr >
            <td colspan="3" style="text-align:center;">Banco: Santander </td>
            </tr>
            <tr>
            <td colspan="3" style="padding-top: 15px">Puede encontrarnos en:</td>
            </tr>

            <tr>
            <td colspan="3">Av. Hda La Purisima Mz 3 Lt 54 Casa A</td>
            </tr>
            <tr>
            <td colspan="3">Ex Hda Santa Ines Nextlalpan Edo Mex</td>
            </tr>
            <tr>
            <td style="text-align:left; with:50px; padding-top: 10px;" colspan="3">Numero de Cuenta:&nbsp;<u>{{ $data['payment_id'] }}<u></td>
            </tr>

            <tr>
                <td style="text-align:left; with:10px" colspan="3">Tiket:&nbsp;<u>{{ $data['ticket_number'] }}<u></td>
                </tr>
            <tr>
            <td style="text-align:left;" colspan="3">Fecha:&nbsp;<u>{{ $data['date'] }}<u></td>
            </tr>
            <tr class="uppercase" >
            <td  colspan="3" style="padding-top: 10px; font-weight: bold; border-bottom: 1px solid">Nombre: {{ $data['full_name'] }}</td>
            </tr>
            <tr >
            <td style="padding-top: 15px"></td>
            <td style="text-align:right; padding-top: 15px;">ABONÓ:</td>
            <td style="padding-top: 15px; text-align:center; border-bottom: 1px solid"> $ {{ $data['amount'] }}</td>
            </tr>
            </tr>
            <tr >
            <td ></td>
            <td style="text-align:right;">SERVICIO: Internet </td>
            <td  style="border-bottom: 1px solid" >&nbsp; {{ $data['services'] }}</td>
            </tr>
            </tr>
            <tr>
            <td></td>
            <td style="text-align:right;">VÁLIDO:</td>
            <td style="border-bottom: 1px solid">&nbsp;{{ $data['payment_period'] }}</td>
            </tr>
            </tr>
            <tr >
            <td ></td>
            <td style="text-align:right;">FECHA DE CORTE: </td>
            <td style="border-bottom: 1px solid">&nbsp;{{ $data['pay_up'] }}</td>
            </tr>
            </tr>
            <tr >
            <td colspan="3" style="text-align:center; padding-top: 25px; font-weight: bold">Gracias por SU VISITA</td>
            </tr>
            <tr >
            <td colspan="3" style="text-align:center;font-weight: bold">¡¡Que tenga un EXELENTE DIA!!</td>
            </tr>
            <tr >
            <td colspan="3" style="text-align:center;font-weight: bold">Esto es un Tiket de Pago</td>
            </tr>

            </tbody>
            </table>
</body>
</html>
