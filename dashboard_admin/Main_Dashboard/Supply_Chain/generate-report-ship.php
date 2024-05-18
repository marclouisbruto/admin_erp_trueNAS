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
        $this->SetFont('Arial','B',15);
        $this->Cell(80);
        $this->Cell(30,10,'Shipping Statement Report',0,0,'C');
        $this->Ln(20);
    }

    // Page footer
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial','I',8);
        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }
}

// Create new PDF document
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',12);

// Add column headers
$pdf->Cell(40,10,'Product Name',1);
$pdf->Cell(30,10,'Quantity',1);
$pdf->Cell(30,10,'Subtotal',1);
$pdf->Cell(40,10,'Status',1);
$pdf->Cell(50,10,'Order Date',1);
$pdf->Ln();

// Fetch data from the database
$query = "
SELECT tbl_order.*, tbl_products.productName, tbl_orderitems.*
FROM tbl_products
JOIN tbl_orderitems ON tbl_products.productID = tbl_orderitems.productID
JOIN tbl_order ON tbl_orderitems.orderID = tbl_order.orderID
WHERE tbl_order.status IN ('To Ship', 'Shipping', 'Delivered')
ORDER BY tbl_order.orderID ASC
";
$result = mysqli_query($conn, $query);

if ($result) {
    $pdf->SetFont('Arial','',12);
    while ($row = mysqli_fetch_assoc($result)) {
        $productName = $row['productName'];
        $quantity = $row['quantity'];
        $subtotal = $row['subTotal'];
        $status = $row['status'];
        $orderDate = date('m-d-Y H:i:s', strtotime($row['orderDate']));

        $pdf->Cell(40,10,$productName,1);
        $pdf->Cell(30,10,$quantity.'kg',1);
        $pdf->Cell(30,10,'Php '.number_format($subtotal, 2),1);
        $pdf->Cell(40,10,$status,1);
        $pdf->Cell(50,10,$orderDate,1);
        $pdf->Ln();
    }
} else {
    $pdf->Cell(0,10,'No records found',1,1,'C');
}

// Output the PDF
$pdf->Output('D', 'Shipping_Statement_Report.pdf');
?>
