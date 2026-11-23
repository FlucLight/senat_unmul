<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Surat</title>
    <style>
        @page {
            margin: {{ !empty($headerHtml) ? 5.0 : ($marginAtas ?? 3) }}cm {{ $marginKanan ?? 3 }}cm {{ ($marginBawah ?? 3) + (!empty($footerHtml) ? 4.2 : 0) }}cm {{ $marginKiri ?? 4 }}cm;
        }

        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 12pt;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }

        table {
            border-collapse: collapse;
        }

        p {
            margin: 0;
        }

        img {
            border: 0;
        }

        header {
            position: fixed;
            top: -4.5cm;
            left: 0;
            right: 0;
            height: 4.3cm;
        }

        footer {
            position: fixed;
            bottom: -3.8cm;
            left: 0;
            right: 0;
            height: 3.6cm;
        }
    </style>
</head>
<body>
    @if (!empty($headerHtml))
        <header>{!! $headerHtml !!}</header>
    @endif
    @if (!empty($footerHtml))
        <footer>{!! $footerHtml !!}</footer>
    @endif

    <main>{!! $konten !!}</main>
</body>
</html>
