<?php
    include ("../../action/Dashboard/fetch.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workforce Performance Dashboard</title>
    <link href="dashboard.css" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>
<body>
    <div class="container-fluid py-4">
        <div class="dashboard-header">
            <h4 class="mb-0">
                <i class="fas fa-chart-line text-primary"></i> Workforce Performance Dashboard
            </h4>
        </div>

        <!-- Key Metrics -->
        <div class="row g-3">
            <div class="col-md-3">
                <div class="metric-card head-count">
                    <div class="metric-icon bg-primary-soft">
                        <i class="fas fa-users text-white"></i>
                    </div>
                    <div class="metric-value"><?php echo $headCount; ?></div>
                    <div class="metric-label">Head Count</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="metric-card age">
                    <div class="metric-icon bg-success-soft">
                        <i class="fas fa-user-clock text-white"></i>
                    </div>
                    <div class="metric-value"><?php echo $avgAge; ?> years</div>
                    <div class="metric-label">Average Employee Age</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="metric-card gender">
                    <div class="metric-icon bg-purple-soft">
                        <i class="fas fa-venus-mars text-white"></i>
                    </div>
                    <div class="metric-value">0</div>
                    <div class="metric-label">Female</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="metric-card hr-ratio">
                    <div class="metric-icon bg-danger-soft">
                        <i class="fas fa-user-tie text-white"></i>
                    </div>
                    <div class="metric-value"><?php echo number_format($hrRatio, 2); ?>%</div>
                    <div class="metric-label">HR to Employee Ratio</div>
                </div>
            </div>
        </div>

        <div class="dashboard-header">
            <h4 class="mb-0">
                <i class="fas fa-chart-line text-primary"></i> Leave Performance Dashboard
            </h4>
        </div>

        <!-- Key Metrics -->
        <div class="row g-3">
            <div class="col-md-3">
                <div class="metric-card head-count">
                    <div class="metric-icon bg-primary-soft">
                        <i class="fas fa-users text-white"></i>
                    </div>
                    <div class="metric-value"><?php echo $TotalLeaveRequest; ?></div>
                    <div class="metric-label">Leave  Request</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="metric-card age">
                    <div class="metric-icon bg-success-soft">
                        <i class="fas fa-user-clock text-white"></i>
                    </div>
                    <div class="metric-value"><?php echo $PendingLeave; ?></div>
                    <div class="metric-label">Pending</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="metric-card gender">
                    <div class="metric-icon bg-purple-soft">
                        <i class="fas fa-venus-mars text-white"></i>
                    </div>
                    <div class="metric-value"><?php echo $ApprovedLeave; ?></div>
                    <div class="metric-label">Approved</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="metric-card hr-ratio">
                    <div class="metric-icon bg-danger-soft">
                        <i class="fas fa-user-tie text-white"></i>
                    </div>
                    <div class="metric-value"><?php echo $RejectedLeave; ?></div>
                    <div class="metric-label">Rejected</div>
                </div>
            </div>
        </div>

        <!-- Charts -->
        <div class="row g-3">
            <div class="col-md-8">
                <div class="chart-container">
                    <h5>
                        <i class="fas fa-chart-bar text-primary" style="background: #e3f2fd;"></i>
                        Employee count by department
                    </h5>
                    <div style="height: 300px;">
                        <canvas id="departmentChart">

                        </canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="chart-container">
                    <h5>
                        <i class="fas fa-chart-pie text-success" style="background: #e8f5e9;"></i>
                        Salary Distribution
                    </h5>
                    <div style="height: 300px;">
                        <canvas id="salaryChart">

                        </canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Employee Details Table -->
        <div class="row g-3">
            <div class="col-12">
                <div class="employee-table">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="mb-0"><i class="fas fa-user-plus text-primary me-2"></i>Recent Employees</h5>
                        <button class="btn btn-primary btn-sm" onclick="window.location.href='../StaffProfile/index.php'">
                            <i class="fas fa-plus me-2"></i>Add Employee
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th>Department</th>
                                    <th>Position</th>
                                    <th>Start Date</th>
                                    <th>Experience</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentEmployees as $employee): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded-circle p-2 me-3">
                                                <i class="fas fa-user text-primary"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold"><?php echo htmlspecialchars($employee['name']); ?></div>
                                                <div class="small text-muted">
                                                    <?php echo htmlspecialchars($employee['EmpCode']); ?> · 
                                                    <a href="mailto:<?php echo htmlspecialchars($employee['Email']); ?>" class="text-decoration-none">
                                                        <?php echo htmlspecialchars($employee['Email']); ?>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">
                                            <?php echo htmlspecialchars($employee['Department']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars($employee['Position']); ?></td>
                                    <td>
                                        <div class="text-muted small">
                                            <?php echo date('d M Y', strtotime($employee['hire_date'])); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary-soft text-primary">
                                            <?php echo $employee['experience']; ?> year(s)
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    // Department Chart
    const departmentCtx = document.getElementById('departmentChart').getContext('2d');
    new Chart(departmentCtx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode(array_column($departmentData, 'Department')); ?>,
            datasets: [{
                label: 'Employees',
                data: <?php echo json_encode(array_column($departmentData, 'count')); ?>,
                backgroundColor: ['#0d6efd', '#dc3545', '#198754', '#ffc107', '#6f42c1', '#fd7e14', '#20c997', '#0dcaf0'],
                borderRadius: 6,
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        font: {
                            size: 12
                        }
                    },
                    grid: {
                        display: true,
                        drawBorder: false
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 12
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: '#000',
                    padding: 12,
                    titleFont: {
                        size: 14
                    },
                    bodyFont: {
                        size: 13
                    }
                }
            }
        }
    });

    // Salary Range Chart
    const salaryCtx = document.getElementById('salaryChart').getContext('2d');
    new Chart(salaryCtx, {
        type: 'doughnut',
        data: {
            labels: <?php echo json_encode(array_column($salaryData, 'salary_range')); ?>,
            datasets: [{
                data: <?php echo json_encode(array_column($salaryData, 'count')); ?>,
                backgroundColor: [
                    '#0d6efd',
                    '#20c997',
                    '#6610f2',
                    '#dc3545'
                ],
                borderWidth: 0,
                cutout: '75%'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        pointStyle: 'circle',
                        font: {
                            size: 12
                        }
                    }
                },
                tooltip: {
                    backgroundColor: '#000',
                    padding: 12,
                    titleFont: {
                        size: 14
                    },
                    bodyFont: {
                        size: 13
                    }
                }
            }
        }
    });
    </script>
</body>
</html>