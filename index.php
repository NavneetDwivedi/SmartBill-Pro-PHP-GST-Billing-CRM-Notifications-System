<?php include('header.php'); 

$clientResult = $conn->query("SELECT COUNT(*) AS total FROM clients");
$clientData = $clientResult->fetch_assoc();
$total_clients = $clientData['total'] ?? 0;

$pendingResult = $conn->query("SELECT COUNT(*) AS total FROM invoices WHERE payment_status = 'Unpaid'");
$pendingData = $pendingResult->fetch_assoc();
$pending_invoices = $pendingData['total'] ?? 0;

$totalRevenueResult = $conn->query("SELECT SUM(total_invoice_value) AS revenue FROM invoices WHERE payment_status = 'Paid'");
$totalRevenueData = $totalRevenueResult->fetch_assoc();
$total_revenue = $totalRevenueData['revenue'] ?? 0;

$startOfWeek = date('Y-m-d', strtotime('monday this week'));
$endOfWeek   = date('Y-m-d', strtotime('sunday this week'));

$stmt = $conn->prepare("SELECT COUNT(*) FROM clients WHERE DATE(incorporation_date) BETWEEN ? AND ?");
$stmt->bind_param("ss", $startOfWeek, $endOfWeek);
$stmt->execute();
$stmt->bind_result($clientsThisWeek);
$stmt->fetch();
$stmt->close();

$stmt = $conn->prepare("SELECT COUNT(*) FROM invoices WHERE payment_status = 'Unpaid' AND DATE(invoice_date) BETWEEN ? AND ?");
$stmt->bind_param("ss", $startOfWeek, $endOfWeek);
$stmt->execute();
$stmt->bind_result($unpaidInvoicesThisWeek);
$stmt->fetch();
$stmt->close();

$stmt = $conn->prepare("SELECT COUNT(*) FROM invoices WHERE DATE(invoice_date) BETWEEN ? AND ?");
$stmt->bind_param("ss", $startOfWeek, $endOfWeek);
$stmt->execute();
$stmt->bind_result($invoicesThisWeek);
$stmt->fetch();
$stmt->close();

// Monthly Revenue Data
$monthlyRevenueData = [];
$currentYear = date('Y');
for ($m = 1; $m <= 12; $m++) {
  $month = str_pad($m, 2, '0', STR_PAD_LEFT);
  $likeDate = "$currentYear-$month%";
  $stmt = $conn->prepare("SELECT SUM(total_invoice_value) as total FROM invoices WHERE payment_status = 'Paid' AND invoice_date LIKE ?");
  $stmt->bind_param("s", $likeDate);
  $stmt->execute();
  $stmt->bind_result($total);
  $stmt->fetch();
  $monthlyRevenueData[] = round($total ?? 0, 2);
  $stmt->close();
}
?>

<div class="main-content">
  <div class="page-content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Dashboard</h4>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-xl-12">
          <div class="card dash-mini">
            <div class="card-header border-0 align-items-center d-flex">
              <h4 class="card-title mb-0 flex-grow-1">This Week's Overview</h4>
            </div>
            <div class="card-body pt-1">
              <div class="row">
                <div class="col-lg-4 mini-widget pb-3 pb-lg-0">
                  <div class="d-flex align-items-end">
                    <div class="flex-grow-1">
                      <h2 class="mb-0 fs-24" id="clients-count">0</h2>
                      <h5 class="text-muted fs-16 mt-2 mb-0">Clients Added</h5>
                      <p class="text-muted mt-3 pt-1 mb-0"><span class="badge bg-info me-1"><?=$clientsThisWeek?> new</span></p>
                    </div>
                    <div class="flex-shrink-0 text-end dash-widget">
                      <div id="mini-chart1" class="apex-charts" style="height:80px;"></div>
                    </div>
                  </div>
                </div>

                <div class="col-lg-4 mini-widget py-3 py-lg-0">
                  <div class="d-flex align-items-end">
                    <div class="flex-grow-1">
                      <h2 class="mb-0 fs-24" id="unpaid-count">0</h2>
                      <h5 class="text-muted fs-16 mt-2 mb-0">Unpaid Invoices</h5>
                      <p class="text-muted mt-3 pt-1 mb-0"><span class="badge bg-danger me-1"><?=$unpaidInvoicesThisWeek?> due</span></p>
                    </div>
                    <div class="flex-shrink-0 text-end dash-widget">
                      <div id="mini-chart2" class="apex-charts" style="height:80px;"></div>
                    </div>
                  </div>
                </div>

                <div class="col-lg-4 mini-widget pt-3 pt-lg-0">
                  <div class="d-flex align-items-end">
                    <div class="flex-grow-1">
                      <h2 class="mb-0 fs-24" id="invoices-count">0</h2>
                      <h5 class="text-muted fs-16 mt-2 mb-0">Invoices Sent</h5>
                      <p class="text-muted mt-3 pt-1 mb-0"><span class="badge bg-success me-1"><?=$invoicesThisWeek?> issued</span></p>
                    </div>
                    <div class="flex-shrink-0 text-end dash-widget">
                      <div id="mini-chart3" class="apex-charts" style="height:80px;"></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <h5 class="card-title mb-0">Monthly Revenue Overview</h5>
            </div>
            <div class="card-body">
              <div id="revenue-chart" class="apex-charts" style="height: 320px;"></div>
            </div>
          </div>
        </div>

        
        <div class="col-md-6">
          <div class="card mini-stats-wid">
            <div class="card-body">
              <div class="d-flex">
                <div class="flex-grow-1">
                  <p class="text-muted fw-medium">Total Clients</p>
                  <h4 class="mb-0"><?=$total_clients?></h4>
                </div>
                <div class="flex-shrink-0 align-self-center">
                  <div class="mini-stat-icon avatar-sm rounded-circle bg-primary">
                    <span class="avatar-title"><i class="las la-users font-size-24 text-white"></i></span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="card mini-stats-wid">
            <div class="card-body">
              <div class="d-flex">
                <div class="flex-grow-1">
                  <p class="text-muted fw-medium">Pending Invoices</p>
                  <h4 class="mb-0"><?=$pending_invoices?></h4>
                </div>
                <div class="flex-shrink-0 align-self-center">
                  <div class="mini-stat-icon avatar-sm rounded-circle bg-warning">
                    <span class="avatar-title"><i class="las la-file-invoice-dollar font-size-24 text-white"></i></span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script src="https://cdn.jsdelivr.net/npm/countup.js@2.0.7/dist/countUp.umd.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
  new ApexCharts(document.querySelector("#mini-chart1"), {
    chart: { type: 'bar', height: 80, sparkline: { enabled: true } },
    series: [{ data: [5, 10, 7, 8, 12, 6, 9] }],
    colors: ['#34c38f']
  }).render();

  new ApexCharts(document.querySelector("#mini-chart2"), {
    chart: { type: 'bar', height: 80, sparkline: { enabled: true } },
    series: [{ data: [3, 5, 2, 6, 4, 7, 3] }],
    colors: ['#f46a6a']
  }).render();

  new ApexCharts(document.querySelector("#mini-chart3"), {
    chart: { type: 'bar', height: 80, sparkline: { enabled: true } },
    series: [{ data: [6, 9, 11, 8, 13, 10, 12] }],
    colors: ['#556ee6']
  }).render();

  new ApexCharts(document.querySelector("#revenue-chart"), {
    chart: { type: 'area', height: 320, toolbar: { show: false } },
    series: [{ name: "Revenue", data: <?= json_encode($monthlyRevenueData) ?> }],
    xaxis: {
      categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
      labels: { style: { fontSize: '13px' } }
    },
    colors: ['#00c292'],
    dataLabels: { enabled: false },
    stroke: { curve: 'smooth', width: 2 },
    fill: {
      type: 'gradient',
      gradient: {
        shadeIntensity: 1,
        opacityFrom: 0.5,
        opacityTo: 0.1,
        stops: [0, 90, 100]
      }
    },
    tooltip: {
      y: {
        formatter: function (val) {
          return "₹" + val.toLocaleString();
        }
      }
    }
  }).render();

  const options = { duration: 1.5 };
  new countUp.CountUp('clients-count', <?=$clientsThisWeek?>, options).start();
  new countUp.CountUp('unpaid-count', <?=$unpaidInvoicesThisWeek?>, options).start();
  new countUp.CountUp('invoices-count', <?=$invoicesThisWeek?>, options).start();
});
</script>

<?php include('footer.php'); ?>
