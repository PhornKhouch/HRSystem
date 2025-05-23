<?php
session_start();
include("../../../Config/conect.php");
if (!$con) {
    die("Database connection failed");
}

// Get list of active employees
$sql = "SELECT EmpCode, EmpName FROM hrstaffprofile WHERE Status = 'Active' ORDER BY EmpName";
$result = mysqli_query($con, $sql);
$employees = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $employees[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PaySlip Report</title>
    <!-- Add Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Add Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Add SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css" rel="stylesheet">
    <!-- Add Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="../../../Style/career.css">
</head>
<body>
    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">PaySlip Report</h5>
            </div>
            <div class="card-body">
                <form id="payslipForm" class="row g-3">
                    <div class="col-md-6">
                        <label for="empCode" class="form-label">Employee Code</label>
                        <select class="form-control" id="empCode" required>
                            <option value="">Select Employee</option>
                            <?php foreach ($employees as $employee): ?>
                                <option value="<?php echo htmlspecialchars($employee['EmpCode']); ?>">
                                    <?php echo htmlspecialchars($employee['EmpCode'] . ' - ' . $employee['EmpName']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="payMonth" class="form-label">Month</label>
                        <input type="month" class="form-control" id="payMonth" required>
                    </div>
                    <div class="col-12">
                        <button type="button" id="viewPaySlip" class="btn btn-primary">
                            <i class="fas fa-search me-2"></i>Run PaySlip
                        </button>
                    </div>
                </form>


                <div id="payslipContent" class="mt-4 d-none">
                    <div class="payslip-header">
                        <div class="company-logo mb-3">
                            <img id="companyLogo" src="" alt="Company Logo" class="img-fluid company-logo-img">
                        </div>
                        <h1 id="companyName">COMPANY NAME</h1>
                        <h2>PAYSLIP</h2>
                        <p class="text-muted">Period: <span id="payslipPeriod"></span></p>
                    </div>

                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Employee Code</span>
                            <span class="info-value" id="empCodeDisplay"></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Employee Name</span>
                            <span class="info-value" id="empNameDisplay"></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Department</span>
                            <span class="info-value" id="departmentDisplay"></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Position</span>
                            <span class="info-value" id="positionDisplay"></span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="amount-section">
                                <h3>Earnings</h3>
                                <div class="amount-row">
                                    <span class="amount-label">Basic Salary</span>
                                    <span class="amount-value" id="basicSalary">0.00</span>
                                </div>
                                <div class="amount-row">
                                    <span class="amount-label">Allowance</span>
                                    <span class="amount-value" id="allowance">0.00</span>
                                </div>
                                <div class="amount-row">
                                    <span class="amount-label">Bonus</span>
                                    <span class="amount-value" id="bonus">0.00</span>
                                </div>
                                <div class="amount-row total-row">
                                    <span class="amount-label">Total Earnings</span>
                                    <span class="amount-value" id="totalEarnings">0.00</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="amount-section">
                                <h3>Deductions</h3>
                                <div class="amount-row">
                                    <span class="amount-label">NSSF</span>
                                    <span class="amount-value" id="nssfAmount">0.00</span>
                                </div>
                                <div class="amount-row">
                                    <span class="amount-label">Other Deductions</span>
                                    <span class="amount-value" id="otherDeductions">0.00</span>
                                </div>
                                <div class="amount-row total-row">
                                    <span class="amount-label">Total Deductions</span>
                                    <span class="amount-value" id="totalDeductions">0.00</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="net-pay mt-4">
                        <div class="net-pay-label">Net Pay</div>
                        <div class="net-pay-value" id="netPay">0.00</div>
                    </div>

                    <div class="export-buttons justify-content-end mt-4">
                        <button type="button" id="exportExcel" class="btn btn-success btn-export">
                            <i class="fas fa-file-excel"></i>
                            <span>Export to Excel</span>
                        </button>
                        <button type="button" id="exportPDF" class="btn btn-danger btn-export">
                            <i class="fas fa-file-pdf"></i>
                            <span>Export to PDF</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

        <!-- Add jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Add Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Add Moment.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <!-- Add html2pdf.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <!-- Add SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>

    <script>
        $(document).ready(function() {
            // Format number with commas and 2 decimal places
            function formatNumber(number) {
                return number ? parseFloat(number).toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }) : '0.00';
            }

            // Format month for display
            function formatMonth(monthStr) {
                return moment(monthStr).format('MMMM YYYY');
            }

            // Load PaySlip data
            function loadPaySlip() {
                const empCode = $('#empCode').val();
                const month = $('#payMonth').val();

                if (!empCode || !month) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Missing Information',
                        text: 'Please select both Employee and Month'
                    });
                    return;
                }

                $.ajax({
                    url: '../../../action/Report/fetch_payslip.php',
                    type: 'POST',
                    data: {
                        empCode: empCode,
                        month: month
                    },
                    success: function(response) {
                        if (response.error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.error
                            });
                            return;
                        }

                        // Update PaySlip content
                        $('#payslipPeriod').text(formatMonth(month));
                        $('#companyName').text(response.CompanyName);
                        if (response.CompanyLogo) {
                            $('#companyLogo').attr('src', 'https://club-code.netlify.app/img/clubcode.jpg').show();
                            $('#companyLogo').css({"height":"100px"});
                        } else {
                            $('#companyLogo').hide();
                        }
                        $('#empCodeDisplay').text(response.EmpCode);
                        $('#empNameDisplay').text(response.EmployeeName);
                        $('#departmentDisplay').text(response.DepartmentName);
                        $('#positionDisplay').text(response.PositionName);
                        $('#basicSalary').text(formatNumber(response.Salary));
                        $('#allowance').text(formatNumber(response.Allowance));
                        $('#bonus').text(formatNumber(response.Bonus));
                        $('#nssfAmount').text(formatNumber(response.NSSF));
                        $('#otherDeductions').text(formatNumber(response.Dedction));

                        // Calculate totals
                        const totalEarnings = parseFloat(response.Salary) + parseFloat(response.Allowance) + parseFloat(response.Bonus);
                        const totalDeductions = parseFloat(response.NSSF) + parseFloat(response.Dedction);
                        const netPay = totalEarnings - totalDeductions;

                        $('#totalEarnings').text(formatNumber(totalEarnings));
                        $('#totalDeductions').text(formatNumber(totalDeductions));
                        $('#netPay').text(formatNumber(netPay));

                        // Show PaySlip content
                        $('#payslipContent').removeClass('d-none');
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to load PaySlip data'
                        });
                    }
                });
            }

            // Export to Excel
            $('#exportExcel').on('click', function() {
                const empCode = $('#empCode').val();
                const month = $('#payMonth').val();
                
                if (!empCode || !month) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Missing Information',
                        text: 'Please select both employee and month before exporting.'
                    });
                    return;
                }
                
                window.location.href = '../../../action/Report/export_payslip_excel.php?empCode=' + empCode + '&month=' + month;
            });

            $('#exportPDF').on('click', function() {
                const empCode = $('#empCode').val();
                const month = $('#payMonth').val();
                
                if (!empCode || !month) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Missing Information',
                        text: 'Please select both employee and month before exporting.'
                    });
                    return;
                }

                // Create a table-formatted version for PDF
                const pdfContent = document.createElement('div');
                pdfContent.innerHTML = `
                    <div style="padding: 20px; font-family: Arial, sans-serif;">
                        <div style="text-align: center; margin-bottom: 20px;">
                            <img src="${$('#companyLogo').attr('src')}" style="height: 80px; margin-bottom: 10px;">
                            <h2 style="margin: 5px 0;">${$('#companyName').text()}</h2>
                            <h3 style="margin: 5px 0;">PAYSLIP</h3>
                            <p>Period: ${$('#payslipPeriod').text()}</p>
                        </div>
                        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                            <tr>
                                <td style="padding: 8px; border: 1px solid #ddd; width: 30%;">Employee Code:</td>
                                <td style="padding: 8px; border: 1px solid #ddd;">${$('#empCodeDisplay').text()}</td>
                            </tr>
                            <tr>
                                <td style="padding: 8px; border: 1px solid #ddd;">Employee Name:</td>
                                <td style="padding: 8px; border: 1px solid #ddd;">${$('#empNameDisplay').text()}</td>
                            </tr>
                            <tr>
                                <td style="padding: 8px; border: 1px solid #ddd;">Department:</td>
                                <td style="padding: 8px; border: 1px solid #ddd;">${$('#departmentDisplay').text()}</td>
                            </tr>
                            <tr>
                                <td style="padding: 8px; border: 1px solid #ddd;">Position:</td>
                                <td style="padding: 8px; border: 1px solid #ddd;">${$('#positionDisplay').text()}</td>
                            </tr>
                        </table>
                        <div style="display: flex; gap: 20px;">
                            <div style="flex: 1;">
                                <table style="width: 100%; border-collapse: collapse;">
                                    <tr style="background: #f8f9fa;">
                                        <th colspan="2" style="padding: 12px; border: 1px solid #ddd; text-align: left;">Earnings</th>
                                    </tr>
                                    <tr>
                                        <td style="padding: 8px; border: 1px solid #ddd;">Basic Salary</td>
                                        <td style="padding: 8px; border: 1px solid #ddd; text-align: right;">${$('#basicSalary').text()}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 8px; border: 1px solid #ddd;">Allowance</td>
                                        <td style="padding: 8px; border: 1px solid #ddd; text-align: right;">${$('#allowance').text()}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 8px; border: 1px solid #ddd;">Bonus</td>
                                        <td style="padding: 8px; border: 1px solid #ddd; text-align: right;">${$('#bonus').text()}</td>
                                    </tr>
                                    <tr style="font-weight: bold;">
                                        <td style="padding: 8px; border: 1px solid #ddd;">Total Earnings</td>
                                        <td style="padding: 8px; border: 1px solid #ddd; text-align: right;">${$('#totalEarnings').text()}</td>
                                    </tr>
                                </table>
                            </div>
                            <div style="flex: 1;">
                                <table style="width: 100%; border-collapse: collapse;">
                                    <tr style="background: #f8f9fa;">
                                        <th colspan="2" style="padding: 12px; border: 1px solid #ddd; text-align: left;">Deductions</th>
                                    </tr>
                                    <tr>
                                        <td style="padding: 8px; border: 1px solid #ddd;">NSSF</td>
                                        <td style="padding: 8px; border: 1px solid #ddd; text-align: right;">${$('#nssfAmount').text()}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 8px; border: 1px solid #ddd;">Other Deductions</td>
                                        <td style="padding: 8px; border: 1px solid #ddd; text-align: right;">${$('#otherDeductions').text()}</td>
                                    </tr>
                                    <tr style="font-weight: bold;">
                                        <td style="padding: 8px; border: 1px solid #ddd;">Total Deductions</td>
                                        <td style="padding: 8px; border: 1px solid #ddd; text-align: right;">${$('#totalDeductions').text()}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
                            <tr style="background: #e9ecef; font-weight: bold;">
                                <td style="padding: 12px; border: 1px solid #ddd;">Net Pay</td>
                                <td style="padding: 12px; border: 1px solid #ddd; text-align: right;">${$('#netPay').text()}</td>
                            </tr>
                        </table>
                    </div>
                `;
                
                const options = {
                    margin: [0.5, 0.5],
                    filename: `PaySlip_${$('#empNameDisplay').text()}_${$('#payslipPeriod').text()}.pdf`,
                    image: { type: 'jpeg', quality: 1 },
                    html2canvas: { 
                        scale: 2,
                        useCORS: true,
                        logging: false
                    },
                    jsPDF: { 
                        unit: 'in', 
                        format: 'a4', 
                        orientation: 'portrait'
                    }
                };

                // Generate PDF
                html2pdf().set(options).from(pdfContent).save();
            });

            // Handle view PaySlip button click
            $('#viewPaySlip').on('click', function() {
                loadPaySlip();
            });
        });
    </script>
</body>
</html>
