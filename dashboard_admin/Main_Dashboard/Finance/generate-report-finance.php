<?php
require('../../../fpdf186/fpdf.php');
include '../../connect.php';

class PDF extends FPDF
{
    // Page header
    function Header()
    {
        // Logo
        $this->Image('../../LogIn_SignUp_Admin/Images/mira.png',10,6,30);
        // Arial bold 15
        $this->SetFont('Arial','B',15);
        // Move to the right
        $this->Cell(80);
        // Title
        $this->Cell(30,10,'Finance Statement',0,0,'C');
        // Line break
        $this->Ln(20);
    }

    // Page footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // Page number
        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }

    // Load data
    function LoadData($conn)
    {
        // Query for total sales and expense
        $result1 = mysqli_query($conn, "SELECT SUM(totalAmount) AS totalSales FROM tbl_order");
        $result2 = mysqli_query($conn, "SELECT SUM(totalExpense) AS totalExpense FROM tbl_expenses");
        $data = [];
        $data['totalSales'] = mysqli_fetch_assoc($result1)['totalSales'];
        $data['totalExpense'] = mysqli_fetch_assoc($result2)['totalExpense'];

        // Query for sales and expense per product
        $totalSalesperProduct = "SELECT
            tbl_products.productID,
            tbl_products.productName,
            COALESCE(SUM(tbl_orderitems.subTotal), 0) AS totalSubTotal
            FROM
                tbl_orderitems
                JOIN tbl_products ON tbl_products.productID = tbl_orderitems.productID
            GROUP BY
                tbl_products.productID, tbl_products.productName
            ORDER BY
                tbl_products.productID";
        $result1 = mysqli_query($conn, $totalSalesperProduct);

        $totalExpensePerProduct = "SELECT
            tbl_products.productID,
            tbl_products.productName,
            COALESCE(SUM(tbl_expenses.totalExpense), 0) AS totalExpense
            FROM
                tbl_expenses
                JOIN tbl_products ON tbl_products.productID = tbl_expenses.productID
            GROUP BY
                tbl_products.productID, tbl_products.productName
            ORDER BY
                tbl_products.productID";
        $result2 = mysqli_query($conn, $totalExpensePerProduct);

        $products = [];
        while ($row1 = mysqli_fetch_assoc($result1)) {
            $row2 = mysqli_fetch_assoc($result2);
            $row1['totalExpense'] = $row2['totalExpense'];
            $products[] = $row1;
        }

        $data['products'] = $products;
        return $data;
    }

    // Table with data
    function FancyTable($data)
    {
        // Colors, line width and bold font
        $this->SetFillColor(255,0,0);
        $this->SetTextColor(255);
        $this->SetDrawColor(128,0,0);
        $this->SetLineWidth(.3);
        $this->SetFont('','B');
        // Header
        $this->Cell(40,7,'Total Sales',1,0,'C',true);
        $this->Cell(40,7,'Total Expense',1,0,'C',true);
        $this->Cell(40,7,'Net Income',1,0,'C',true);
        $this->Ln();
        // Color and font restoration
        $this->SetFillColor(224,235,255);
        $this->SetTextColor(0);
        $this->SetFont('');
        // Data
        $this->Cell(40,6,number_format($data['totalSales'], 2),1);
        $this->Cell(40,6,number_format($data['totalExpense'], 2),1);
        $this->Cell(40,6,number_format($data['totalSales'] - $data['totalExpense'], 2),1);
        $this->Ln();
        // Product Table
        $this->Ln(10);
        $this->SetFont('Arial','B',12);
        $this->Cell(0,10,'Total Sales, Total Expense, and Net Income per Product',0,1,'C');
        $this->SetFont('Arial','B',10);
        $this->Cell(20,7,'ID',1);
        $this->Cell(60,7,'Product Name',1);
        $this->Cell(30,7,'Total Sales',1);
        $this->Cell(30,7,'Total Expense',1);
        $this->Cell(30,7,'Net Income',1);
        $this->Ln();
        $this->SetFont('Arial','',10);
        foreach($data['products'] as $product)
        {
            $this->Cell(20,6,$product['productID'],1);
            $this->Cell(60,6,$product['productName'],1);
            $this->Cell(30,6,number_format($product['totalSubTotal'], 2),1);
            $this->Cell(30,6,number_format($product['totalExpense'], 2),1);
            $this->Cell(30,6,number_format($product['totalSubTotal'] - $product['totalExpense'], 2),1);
            $this->Ln();
        }
    }
}

// Create instance of the PDF class
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$data = $pdf->LoadData($conn);
$pdf->FancyTable($data);
$pdf->Output();
?>

### Step 3: Add Download Button in Your HTML

In your HTML file, add a button that links to `generate_pdf.php`.

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- existing head content -->
</head>
<body>
    <!-- existing body content -->

    <div class="download-button">
        <button onclick="window.location.href='generate_pdf.php'">Download PDF Report</button>
    </div>

    <!-- existing script tags -->
</body>
</html>
