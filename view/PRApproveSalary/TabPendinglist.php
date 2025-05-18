<div class="table-responsive">
    <table id="approvedTable" class="table table-bordered table-striped w-100">
        <thead class="table-light">
            <tr>
                <th>Month</th>
                <th>Total Salary</th>
                <th>Total Allowance</th>
                <th>Total OT</th>
                <th>Total Bonus</th>
                <th>Total Deduction</th>
                <th>Total Gross</th>
                <th>Net Salary</th>
                <th>Status</th>
                <th>Remark</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Loop through the data and create table rows
            $sql = "SELECT 
        sum(s.Salary) as TotalSalary,
        sum(s.Allowance) as TotalAllowance,
        sum(s.OT) as TotalOT,
        sum(s.Bonus) as TotalBonus,
        sum(s.Dedction) as TotalDed,
        sum(s.Grosspay) as TotalGross,
        Sum(s.NetSalary) as NetSalary,
        A.InMonth,
        A.status,
        A.Remark,
        A.ID
        FROM hisgensalary S
        INNER JOIN prapprovesalary A ON S.InMonth = A.InMonth 
        Where A.status='Pending' 
         ";
            // Execute the query
            $stmt = $con->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();
            // Fetch the results
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['InMonth'] ?? '') . "</td>";
                echo "<td>" . htmlspecialchars($row['TotalSalary'] ?? '') . "</td>";
                echo "<td>" . htmlspecialchars($row['TotalAllowance'] ?? '') . "</td>";
                echo "<td>" . htmlspecialchars($row['TotalOT'] ?? '') . "</td>";
                echo "<td>" . htmlspecialchars($row['TotalBonus'] ?? '') . "</td>";
                echo "<td>" . htmlspecialchars($row['TotalDed'] ?? '') . "</td>";
                echo "<td>" . htmlspecialchars($row['TotalGross'] ?? '') . "</td>";
                echo "<td>" . htmlspecialchars($row['NetSalary'] ?? '') . "</td>";
                echo "<td>" . htmlspecialchars($row['status'] ?? '') . "</td>";
                echo "<td>" . htmlspecialchars($row['Remark'] ?? '') . "</td>";
               ?>
                    <td>
                        <a href="../../action/PRApproveSalary/approve.php?id=<?php echo $row['ID']; ?>" class="btn btn-success btn-sm approve-btn" data-id="<?php echo $row['ID']; ?>">
                                 <i class="fas fa-check"></i> Approve                          </a>
                          <button class="btn btn-danger btn-sm reject-btn" data-id="<?php echo $row['ID']; ?>">
                             <i class="fas fa-times"></i> Reject
                          </button>
                    </td>
               <?php
                echo "</tr>";
            }
            // Close the statement
            $stmt->close();
            // The connection will be closed elsewhere after all operations are complete
            ?>

        </tbody>
    </table>
</div>


