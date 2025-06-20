<?php
require_once('tcpdf/tcpdf.php');

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetMargins(12, 12, 12);
$pdf->AddPage();

$html = <<<HTML
<style>
  body {
    font-family: DejaVu Sans, sans-serif;
    font-size: 10.5px;
  }
  .cs-container {
    border: 1px solid #e0e0e0;
    padding: 18px;
    border-radius: 8px;
    background: #ffffff;
  }
  .cs-header-table {
    width: 100%;
    margin-bottom: 20px;
  }
  .cs-header-table td {
    vertical-align: middle;
    padding: 0;
    border: none;
  }
  .cs-meta p {
    margin: 4px 0;
  }
  .cs-logo {
    text-align: right;
  }
  .cs-title {
    font-size: 18px;
    font-weight: bold;
    color: #2c3e50;
  }
  table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 14px;
  }
  th, td {
    border: 1px solid #ccc;
    padding: 10px 12px;
    vertical-align: top;
  }
  th {
    background: #eef6ff;
    color: #2c3e50;
    font-weight: 600;
    font-size: 11px;
  }
  .cs-footer-table td {
    border: none;
    padding: 6px 12px;
  }
  .cs-bold { font-weight: bold; }
  .cs-right { text-align: right; }
</style>

<div class="cs-container">
  <table class="cs-header-table">
    <tr>
      <td style="width: 50%; vertical-align: middle;">
        <div class="cs-meta">
          <p><strong>Invoice No:</strong> #SM75692</p>
          <p><strong>Date:</strong> 05.01.2022</p>
          <p><strong>Status:</strong> Paid</p>
        </div>
      </td>
      <td style="width: 50%; text-align: right; vertical-align: middle; height: 100px;">
        <div class="cs-logo" style="vertical-align: middle; height: 100px;">
          <img src="assets/images/logo-dark.png" height="10" style="display: block;" align="middle">

        </div>
      </td>
    </tr>
  </table>

  <table style="border: none;">
    <tr>
      <td style="border: none; width: 50%; vertical-align: top;">
        <strong>Invoice To:</strong><br>
        Jennifer Richards<br>
        365 Bloor Street East, Toronto,<br>
        Ontario, M4W 3L4,<br>
        Canada
      </td>
      <td style="border: none; width: 50%; vertical-align: top; text-align: right;">
        <strong>Pay To:</strong><br>
        Invoika Technologies<br>
        237 Roanoke Road, North York,<br>
        Ontario, Canada<br>
        info@invoika.com
      </td>
    </tr>
  </table>

  <h3 class="cs-title" style="margin-top: 20px;">Items</h3>
  <table>
    <thead>
      <tr>
        <th>#</th>
        <th>Item</th>
        <th>Description</th>
        <th>Qty</th>
        <th>Price</th>
        <th>Total</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>1</td>
        <td>Web Design</td>
        <td>Corporate website design</td>
        <td>1</td>
        <td>$500</td>
        <td>$500</td>
      </tr>
      <tr>
        <td>2</td>
        <td>Hosting</td>
        <td>One-year web hosting</td>
        <td>1</td>
        <td>$100</td>
        <td>$100</td>
      </tr>
      <tr>
        <td>3</td>
        <td>SEO</td>
        <td>Basic search engine optimization</td>
        <td>1</td>
        <td>$150</td>
        <td>$150</td>
      </tr>
    </tbody>
  </table>

  <table class="cs-footer-table" style="margin-top: 20px; width: 100%;">
    <tr>
      <td style="width: 70%;"></td>
      <td style="width: 30%;">
        <table style="width: 100%;">
          <tr>
            <td class="cs-bold">Subtotal</td>
            <td class="cs-right">$750</td>
          </tr>
          <tr>
            <td class="cs-bold">Tax (18%)</td>
            <td class="cs-right">$135</td>
          </tr>
          <tr>
            <td class="cs-bold">Discount</td>
            <td class="cs-right">$50</td>
          </tr>
          <tr>
            <td class="cs-bold">Shipping</td>
            <td class="cs-right">$20</td>
          </tr>
          <tr>
            <td class="cs-bold">Total</td>
            <td class="cs-right"><strong>$855</strong></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>

  <div style="margin-top: 30px;">
    <p><strong>Notes:</strong> All invoices are due within 7 days. Please contact accounts@invoika.com with any questions regarding this invoice.</p>
  </div>
</div>
HTML;

$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('invoice.pdf', 'I');
