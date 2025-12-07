<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>{{ config('app.name', 'Voicy Assistant') }}</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="color-scheme" content="light">
<meta name="supported-color-schemes" content="light">
<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
    background-color: #f3f4f6;
    color: #111827;
    line-height: 1.6;
}

.wrapper {
    width: 100%;
    background-color: #f3f4f6;
    padding: 20px 0;
}

.content {
    max-width: 600px;
    margin: 0 auto;
    background-color: #ffffff;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.header {
    background: linear-gradient(135deg, #0ea5e9 0%, #a855f7 100%);
    padding: 30px 20px;
    text-align: center;
}

.logo-text {
    font-size: 28px;
    font-weight: 700;
    color: #ffffff;
    text-decoration: none;
    letter-spacing: -0.5px;
}

.body {
    padding: 0;
}

.inner-body {
    width: 100%;
    max-width: 570px;
    margin: 0 auto;
}

.content-cell {
    padding: 40px 30px;
}

.footer {
    background-color: #f9fafb;
    padding: 20px 30px;
    text-align: center;
    border-top: 1px solid #e5e7eb;
}

.footer-text {
    font-size: 12px;
    color: #6b7280;
    line-height: 1.5;
}

.button {
    display: inline-block;
    padding: 12px 30px;
    background-color: #0ea5e9;
    color: #ffffff !important;
    text-decoration: none;
    border-radius: 6px;
    font-weight: 600;
    font-size: 16px;
    margin: 20px 0;
}

.button:hover {
    background-color: #0284c7;
}

@media only screen and (max-width: 600px) {
    .inner-body {
        width: 100% !important;
    }

    .footer {
        width: 100% !important;
    }

    .content-cell {
        padding: 30px 20px !important;
    }

    .button {
        width: 100% !important;
        text-align: center;
    }
}

@media only screen and (max-width: 500px) {
    .button {
        width: 100% !important;
    }
}
</style>
</head>
<body>

<table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td align="center">
<table class="content" width="100%" cellpadding="0" cellspacing="0" role="presentation">
{{ $header ?? '' }}

<!-- Email Body -->
<tr>
<td class="body" width="100%" cellpadding="0" cellspacing="0" style="border: hidden !important;">
<table class="inner-body" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation">
<!-- Body content -->
<tr>
<td class="content-cell">
{{ Illuminate\Mail\Markdown::parse($slot) }}

{{ $subcopy ?? '' }}
</td>
</tr>
</table>
</td>
</tr>

{{ $footer ?? '' }}
</table>
</td>
</tr>
</table>
</body>
</html>
